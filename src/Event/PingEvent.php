<?php
/**
 * Created by PhpStorm.
 * User: pozyc
 * Date: 22.09.2018
 * Time: 11:13
 */

namespace App\Event;


use App\Entity\Ping;
use Symfony\Component\EventDispatcher\Event;

class PingEvent extends Event
{

    CONST ping_event='website.ping';

    private $ping;

    /**
     * PingEvent constructor.
     * @param $ping
     */
    public function __construct(Ping $ping)
    {
        $this->ping = $ping;
    }


    /**
     * @return mixed
     */
    public function getPing()
    {
        return $this->ping;
    }

    /**
     * @param mixed $ping
     */
    public function setPing(Ping $ping)
    {
        $this->ping = $ping;
    }



}