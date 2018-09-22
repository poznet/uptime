<?php
/**
 * Created by PhpStorm.
 * User: pozyc
 * Date: 22.09.2018
 * Time: 12:42
 */

namespace App\Listener;


use App\Event\PingEvent;
use Doctrine\ORM\EntityManagerInterface;
use WowApps\SlackBundle\DTO\SlackMessage;
use WowApps\SlackBundle\Service\SlackBot;

class SlackNotifyListener
{

    private $slackbot;
    private $em;
    private $manger;

    public function __construct(EntityManagerInterface $entityManager,SlackBot $slackBot)
    {
        $this->slackbot = $slackBot;
        $this->em=$entityManager;
    }

    public function notify(PingEvent $event){
        $ping=$event->getPing();
        $status=$ping->getStatus();

        if($status){
            $msg= new SlackMessage();
            $msg->setText('OK');
            $msg->setSender('Powiadamiacz');
            $this->slackbot->sendMessage($msg);

        }


    }



}