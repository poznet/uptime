<?php
/**
 * Created by PhpStorm.
 * User: pozyc
 * Date: 25.10.2018
 * Time: 15:33
 */

namespace App\Event;


use Symfony\Component\EventDispatcher\Event;

class NotifyEvent extends Event
{
    CONST NAME='notify';

    private $what;
    private $channel;

    /**
     * NotifyEvent constructor.
     * @param $what
     * @param $channel
     */
    public function __construct($what, $channel)
    {
        $this->what = $what;
        $this->channel = $channel;
    }


    /**
     * @return mixed
     */
    public function getWhat()
    {
        return $this->what;
    }

    /**
     * @param mixed $what
     */
    public function setWhat($what)
    {
        $this->what = $what;
    }

    /**
     * @return mixed
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @param mixed $channel
     */
    public function setChannel($channel)
    {
        $this->channel = $channel;
    }



}