<?php
/**
 * Created by PhpStorm.
 * User: pozyc
 * Date: 25.09.2018
 * Time: 10:22
 */

namespace App\Listener;

use App\Event\PingEvent;
use App\Entity\Notify;
use App\Service\NotifyManager;


class EmailNotifyListener
{

    private $manger;

    public function __construct(NotifyManager $manager)
    {
        $this->manger=$manager;
    }

    public function notify(PingEvent $event)
    {
        $ping=$event->getPing();

        if($this->manger->resolveEmailNotification($ping)){
            $this->manger->notify($ping,Notify::NOTIFY_CHANNEL_EMAIL);
        }else{

        }


    }


}