<?php
/**
 * Created by PhpStorm.
 * User: pozyc
 * Date: 22.09.2018
 * Time: 10:51
 */

namespace App\Service;


use App\Entity\Ping;
use App\Entity\Website;
use App\Event\PingEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class PingService
{
    private $em;
    private $dispatcher;

    /**
     * PingService constructor.
     * @param $em
     */
    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $eventDispatcher)
    {
        $this->em = $em;
        $this->dispatcher = $eventDispatcher;
    }

    public function ping(Website $www)
    {
        $url = 'http://' . $www->getUrl();
        $code = $this->curlCheck($url);
        $ping = new Ping();
        $ping->setResponseCode($code);
        $ping->setStatus($this->getStatusFromCode($code));
        $ping->setWebsite($www);
        if (!$this->getStatusFromCode($code)) {
            //http  dont  anwser right
            $url = 'https://' . $www->getUrl();
            $sslcode = $this->curlCheck($url);
            $sslstatus = $this->getStatusFromCode($sslcode);
            if ($sslstatus == true) {
                $ping->setStatus($sslstatus);
                $ping->setSsl(true);
                $code = $sslcode;
            }

        }
        $this->em->persist($ping);
        $this->em->flush();

        $event = new PingEvent($ping);
        $this->dispatcher->dispatch(PingEvent::ping_event, $event);

        return $code;
    }

    private function curlCheck($url)
    {
        $handle = curl_init($url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($handle, CURLOPT_FOLLOWLOCATION, true);
        $response = curl_exec($handle);
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        curl_close($handle);
        return $httpCode;
    }

    private function getStatusFromCode($code)
    {
        if (($code >= 200) and ($code < 400))
            return true;
        return false;


    }
}