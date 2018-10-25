<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NotifyRepository")
 */
class Notify
{
    CONST NOTIFY_CHANNEL_SLACK='slack';
    CONST NOTIFY_CHANNEL_EMAIL='email';

    CONST NOTIFY_WHAT_UPTIME='uptime';
    CONST NOTIFY_WHAT_SSL='ssl';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $channel;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="text")
     */
    private $message;

    /**
     * @ORM\Column(type="text")
     */
    private $what=self::NOTIFY_WHAT_UPTIME;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Website", inversedBy="notifications")
     * @ORM\JoinColumn(name="websiteid", referencedColumnName="id")
     */
    private $website;

    public function __construct()
    {
        $this->date=new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChannel(): ?string
    {
        return $this->channel;
    }

    public function setChannel(string $channel): self
    {
        $this->channel = $channel;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getWebsite(): ?Website
    {
        return $this->website;
    }

    public function setWebsite(?Website $website): self
    {
        $this->website = $website;

        return $this;
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


}
