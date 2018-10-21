<?php

namespace App\Entity;

use App\Helper\UrlHelper;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SSLCheckRepository")
 */
class SSLCheck
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
 * @ORM\Column(type="string", length=255)
 */
    private $url;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $last_check;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $sslstatus;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $valid_till;

    public  function __construct()
    {
        $this->created_at=new \DateTime();
        
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return 'https://'.$this->url;
    }

    public function setUrl(string $url): self
    {
        $url=UrlHelper::clearUrl($url);
        $this->url = $url;
        return $this;
    }

    public function getLastCheck(): ?\DateTimeInterface
    {
        return $this->last_check;
    }

    public function setLastCheck(?\DateTimeInterface $last_check): self
    {
        $this->last_check = $last_check;

        return $this;
    }

    

    public function getValidTill(): ?\DateTimeInterface
    {
        return $this->valid_till;
    }

    public function setValidTill(?\DateTimeInterface $valid_till): self
    {
        $this->valid_till = $valid_till;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSslstatus(): ?bool
    {
        return $this->sslstatus;
    }

    public function setSslstatus(?bool $sslstatus): self
    {
        $this->sslstatus = $sslstatus;

        return $this;
    }
}
