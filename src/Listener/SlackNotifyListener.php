<?php
/**
 * Created by PhpStorm.
 * User: pozyc
 * Date: 22.09.2018
 * Time: 12:42
 */

namespace App\Listener;


use App\Entity\Notify;
use App\Entity\Ping;
use App\Event\PingEvent;
use App\Service\NotifyManager;
use Doctrine\ORM\EntityManagerInterface;
use WowApps\SlackBundle\DTO\SlackMessage;
use WowApps\SlackBundle\Service\SlackBot;

class SlackNotifyListener
{


    private $manger;

    public function __construct(NotifyManager $manager)
    {

        $this->manger=$manager;
    }

    public function notify(PingEvent $event){
        $ping=$event->getPing();
        $status=$ping->getStatus();

        if($this->manger->resolveSlackNotification($ping)){
            $this->manger->notify($ping,Notify::NOTIFY_CHANNEL_SLACK);
        }else{

        }


    }



}