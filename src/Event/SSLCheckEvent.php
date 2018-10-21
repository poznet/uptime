<?php
/**
 * Created by PhpStorm.
 * User: jospeh
 * Date: 21.10.18
 * Time: 11:31
 */

namespace App\Event;


use App\Entity\SSLCheck;
use Symfony\Component\EventDispatcher\Event;

class SSLCheckEvent extends Event
{
    CONST ssl_event='ssl.check';

    private $website;

    /**
     * SSLCheckEvent constructor.
     * @param $website
     */
    public function __construct( SSLCheck $website)
    {
        $this->website = $website;
    }

    /**
     * @return mixed
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * @param mixed $website
     */
    public function setWebsite(SSLCheck $website)
    {
        $this->website = $website;
    }



}