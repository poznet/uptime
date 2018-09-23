<?php
/**
 * Created by PhpStorm.
 * User: jospeh
 * Date: 22.09.18
 * Time: 22:11
 */

namespace App\Service;


use App\Entity\Notify;
use App\Entity\Ping;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use WowApps\SlackBundle\DTO\SlackMessage;
use WowApps\SlackBundle\Service\SlackBot;

class NotifyManager
{
    private $parameters;
    private $em;
    private $slackbot;

    /**
     * NotifyManager constructor.
     * @param $parameters
     */
    public function __construct(EntityManagerInterface $entityManager, ParameterBagInterface $parameters, SlackBot $slackBot)
    {
        $this->em = $entityManager;
        $this->parameters = $parameters;
        $this->slackbot = $slackBot;
    }

    public function generateMessage(Ping $ping)
    {
        $website = $ping->getWebsite();
        $last=$this->em->getRepository("App:Ping")->findOneBy(['website'=>$ping->getWebsite(),'status'=>true],['id'=>'DESC']);

        $msg = date("d-m-Y G:i:s").' - Your website seems to be down';

        if($last instanceof Ping)
            $msg.= ' since '.$last->getDate()->format("d-m-Y G:i:s");
        return $msg;
    }

    public function notify(Ping $ping, $channel)
    {
        $n = new Notify();
        $n->setChannel($channel);
        $n->setMessage($this->generateMessage($ping));
        $n->setWebsite($ping->getWebsite());
        $this->em->persist($n);
        $this->em->flush();

        if ($channel == Notify::NOTIFY_CHANNEL_SLACK)
            $this->sendSlacknotification($ping);
    }


    public function resolveSlackNotification(Ping $ping)
    {
         if($ping->getStatus()!=true)
         return $this->resolveSlackNotificationTime($ping) and $ping->getWebsite()->getSlack() ;
    }

    public function resolveSlackNotificationTime(Ping $ping)
    {
        $interval = $this->parameters->get('notification_interval')['slack'];
        $last = $this->em->getRepository("App:Notify")->findOneBy(['website' => $ping->getWebsite(), 'channel' => Notify::NOTIFY_CHANNEL_SLACK], ['id' => 'DESC']);
        if (!$last instanceof Notify)
            return true;

        $data = $last->getDate();
        $now = new \DateTime();
        $change = $now->diff($data);
        $hours = $change->format('%h');

        if ($hours > $interval)
            return true;
        return false;

    }



    private function sendSlacknotification(Ping $ping)
    {
        $msg = new SlackMessage();
        $msg->setText($this->generateMessage($ping));
        $msg->setSender("Przypominacz ");
        $msg->setShowQuote(true);
        $msg->setQuoteType(SlackBot::QUOTE_DANGER);
        $msg->setQuoteTitle($ping->getWebsite()->getUrl());
        $msg->setQuoteTitleLink('https://'.$ping->getWebsite()->getUrl());
        $this->slackbot->sendMessage($msg);


    }

}