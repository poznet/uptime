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

class NotifyManager
{
    private $parameters;
    private $em;

    /**
     * NotifyManager constructor.
     * @param $parameters
     */
    public function __construct(EntityManagerInterface $entityManager, ParameterBagInterface $parameters)
    {
        $this->em = $entityManager;
        $this->parameters = $parameters;
    }

    public function generateMessage(Ping $ping){
        $website=$ping->getWebsite();
        $msg=' Your website <a href="http://'.$website->getUrl.'">'.$website->getUrl.'</a> seems to be down';
    }



    public function resolveSlackNotification(Ping $ping){
        return $this->resolveSlackNotificationTime($ping) && $this->resolveSlackNotificationTolerance($ping);
    }

    public function resolveSlackNotificationTime(Ping $ping)
    {
        $interval = $this->parameters->get('notification_interval')['slack'];
        $last = $this->em->getRepository("App:Notify")->findOneBy(['website' => $ping->getWebsite(), 'channel' => Notify::NOTIFY_CHANNEL_SLACK], ['id' => 'DESC']);
        if (!$last instanceof Notify)
            return true;

        $data = $last->getDate();
        $now = new \DateTime();
        $change = $data->diff($now);
        $hours = $change->format('g');

        if ($hours > $interval)
            return true;
        return false;

    }

    public function resolveSlackNotificationTolerance(Ping $ping)
    {
        $website = $ping->getWebsite();
        $tolerance = $this->parameters->get('tolerance');
        $www = $this->em->getRepository("App:Ping")->findBy(['website' => $website->getId()], ['id' => 'DESC'], $tolerance);
        $status = true;
        foreach ($www as $w) {
            $status = $status && $w->getStatus();
        }
        if ($status === false) {
            return true;

        }
        return false;

    }


}