<?php
/**
 * Created by PhpStorm.
 * User: pozyc
 * Date: 27.10.2018
 * Time: 09:25
 */

namespace App\Listener;


use App\Entity\Notify;
use App\Event\NotifyEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class SSLNotifyListener
{

    private $em;
    private $twig;
    private $mailer;
    private $parameters;

    /**
     * SSLNotifyListener constructor.
     * @param $em
     * @param $twig
     * @param $mailer
     */
    public function __construct(EntityManagerInterface $em, \Twig_Environment $twig, \Swift_Mailer $mailer, ParameterBagInterface $parameter)
    {
        $this->em = $em;
        $this->twig = $twig;
        $this->mailer = $mailer;
        $this->parameters = $parameter;
    }

    public function onNotify(NotifyEvent $event)
    {
        $what = $event->getWhat();
        $channel = $event->getChannel();

        if (($what == Notify::NOTIFY_WHAT_SSL) && ($channel == Notify::NOTIFY_CHANNEL_EMAIL)) {
            $this->notifyEmail();
        }
    }


    private function notifyEmail()
    {
        $ssls = $this->em->getRepository("App:SSLCheck")->findAll();
        $msg = (new \Swift_Message('UPTIME strony - SSL informacja '))
            ->setFrom($this->parameters->get('email_from'))
            ->setTo($this->parameters->get('email_to'))
            ->setBody(
                $this->twig->render(
                // templates/emails/registration.html.twig
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