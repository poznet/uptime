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
use WowApps\SlackBundle\Service\SlackBot;

class SlackNotifyListener
{

    private $slackbot;
    private $em;

    public function __construct(EntityManagerInterface $entityManager,SlackBot $slackBot)
    {
        $this->slackbot = $slackBot;
        $this->=$em;
    }

    public function notify(PingEvent $event){

    }



}