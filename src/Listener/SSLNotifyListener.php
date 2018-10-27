<?php
/**
 * Created by PhpStorm.
 * User: pozyc
 * Date: 27.10.2018
 * Time: 09:25
 */

namespace App\Listener;


use App\Entity\Notify;
use App\Entity\SSLCheck;
use App\Event\NotifyEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use WowApps\SlackBundle\DTO\SlackMessage;
use WowApps\SlackBundle\Service\SlackBot;

class SSLNotifyListener
{

    private $em;
    private $twig;
    private $mailer;
    private $parameters;
    private $slackbot;

    /**
     * SSLNotifyListener constructor.
     * @param $em
     * @param $twig
     * @param $mailer
     */
    public function __construct(EntityManagerInterface $em, \Twig_Environment $twig, \Swift_Mailer $mailer, ParameterBagInterface $parameter, SlackBot $slackBot)
    {
        $this->em = $em;
        $this->twig = $twig;
        $this->mailer = $mailer;
        $this->parameters = $parameter;
        $this->slackbot = $slackBot;
    }

    public function onNotify(NotifyEvent $event)
    {
        $what = $event->getWhat();
        $channel = $event->getChannel();

        if (($what == Notify::NOTIFY_WHAT_SSL) && ($channel == Notify::NOTIFY_CHANNEL_EMAIL)) {
            $this->notifyEmail();
        }
        if (($what == Notify::NOTIFY_WHAT_SSL) && ($channel == Notify::NOTIFY_CHANNEL_SLACK)) {
            $this->notifySlack();
        }
    }

    private function notifySlack()
    {
        $msg = new SlackMessage();
        $msg->setText($this->generateMessageForSlack());
        $msg->setSender("Przypominacz ");
        $msg->setShowQuote(true);
        $msg->setQuoteType(SlackBot::QUOTE_DANGER);
        $msg->setQuoteTitle('Problem z Certyfikatami SSL');
        $msg->setQuoteTitleLink('http://glajc.pl');

        if($this->slackbot->sendMessage($msg)){
            $notify = new Notify();
            $notify->setChannel(Notify::NOTIFY_CHANNEL_Slack);
            $notify->setDate(new \DateTime());
            $notify->setWhat(Notify::NOTIFY_WHAT_SSL);
            $notify->setMessage($this->generateMessageForSlack());
            $this->em->persist($notify);
            $this->em->flush();
        }
    }


    private function generateMessageForSlack()
    {
        $msg = '';
        $ssls = $this->em->getRepository("App:SSLCheck")->findAll();
        foreach ($ssls as $ssl) {
            if ($ssl->getSslstatus() != true) {
                $msg .= '' . $ssl->getUrl() . ' <br/>';
            }
        }
        return $msg;
    }

    /**
     *
     */
    private function notifyEmail()
    {
        $ssls = $this->em->getRepository("App:SSLCheck")->findAll();
        $msg = (new \Swift_Message('UPTIME strony - SSL informacja '))
            ->setFrom($this->parameters->get('email_from'))
            ->setTo($this->parameters->get('email_to'))
            ->setBody(
                $this->twig->render(
                    'email/ssl_notify.html.twig',
                    array('ssls' => $ssls)
                ),
                'text/html'
            );

        if ($this->mailer->send($msg)) {
            $notify = new Notify();
            $notify->setChannel(Notify::NOTIFY_CHANNEL_EMAIL);
            $notify->setDate(new \DateTime());
            $notify->setWhat(Notify::NOTIFY_WHAT_SSL);
            $notify->setMessage('.');
            $this->em->persist($notify);
            $this->em->flush();
        }
    }

}