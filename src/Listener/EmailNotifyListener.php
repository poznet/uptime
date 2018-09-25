<?php
/**
 * Created by PhpStorm.
 * User: pozyc
 * Date: 25.09.2018
 * Time: 10:22
 */

namespace App\Listener;


use App\Event\PingEvent;
use Doctrine\ORM\EntityManagerInterface;

class EmailNotifyListener
{
    private $em;
    private $mailer;
    private $twig;

    public function __construct(EntityManagerInterface $entityManager,\Swift_Mailer $mailer,\Twig $twig)
    {
        $this->em = $entityManager;
        $this->twig=$twig;
        $this->mailer=$mailer;
    }

    public function notify(PingEvent $event)
    {
        $ping=$event->getPing();


    }


}