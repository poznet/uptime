<?php
/**
 * Created by PhpStorm.
 * User: jospeh
 * Date: 21.10.18
 * Time: 11:54
 */

namespace App\Listener;


use App\Entity\SSLCheck;
use App\Event\SSLCheckEvent;
use Doctrine\ORM\EntityManagerInterface;
use Punkstar\Ssl\Reader;

class SSlCheckListener
{
    private $em;

    /**
     * SSlCheckListener constructor.
     * @param $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function onCheck(SSLCheckEvent $event){
        $reader=new Reader();
        $website=$event->getWebsite();

        $cert=$reader->readFromUrl($website->getUrl());
        $now=new \DateTime();
        if(($cert->validTo()<$now) or ($cert->issuer()["O"]=='none')) {
            $website->setSslstatus(false);
        }else{
            $website->setSslstatus(true);
            $website->setName($cert->issuer()["O"]);
            $website->setValidTill($cert->validTo());

        }
        $website->setLastCheck($now);

        $this->em->flush();

    }


}