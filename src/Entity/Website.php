<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\DateType;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WebsiteRepository")
 */
class Website
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
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="boolean")
     */
    private $email = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $slack = false;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ping", mappedBy="website", orphanRemoval=true)
     */
    private $pings;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Notify", mappedBy="website", orphanRemoval=true)
     */
    private $notifications;


    public function __construct()
    {
        $this->created_at = new \DateTime('now');
        $this->responseCode = new ArrayCollection();
        $this->pings = new ArrayCollection();
        $this->notifications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        if (substr($url, 0, 7) == 'http://') {
            $this->url = substr($url, 7);
            return $this;
        }
        if (substr($url, 0, 8) == 'https://') {
            $this->url = substr($url, 8);
            return $this;
        }

        $this->url = $url;
        return $this;

    }


    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getEmail(): ?bool
    {
        return $this->email;
    }

    public function setEmail(bool $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getSlack(): ?bool
    {
        return $this->slack;
    }

    public function setSlack(bool $slack): self
    {
        $this->slack = $slack;

        return $this;
    }

    /**
     * @return Collection|Ping[]
     */
    public function getResponseCode(): Collection
    {
        return $this->responseCode;
    }

    public function addResponseCode(Ping $responseCode): self
    {
        if (!$this->responseCode->contains($responseCode)) {
            $this->responseCode[] = $responseCode;
            $responseCode->setWebsiteId($this);
        }

        return $this;
    }

    public function removeResponseCode(Ping $responseCode): self
    {
        if ($this->responseCode->contains($responseCode)) {
            $this->responseCode->removeElement($responseCode);
            // set the owning side to null (unless already changed)
            if ($responseCode->getWebsiteId() === $this) {
                $responseCode->setWebsiteId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Ping[]
     */
    public function getPings(): Collection
    {
        return $this->pings;
    }

    public function addPing(Ping $ping): self
    {
        if (!$this->pings->contains($ping)) {
            $this->pings[] = $ping;
            $ping->setWebsiteId($this);
        }

        return $this;
    }

    public function removePing(Ping $ping): self
    {
        if ($this->pings->contains($ping)) {
            $this->pings->removeElement($ping);
            // set the owning side to null (unless already changed)
            if ($ping->getWebsiteId() === $this) {
                $ping->setWebsiteId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Notify[]
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notify $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications[] = $notification;
            $notification->setWebsite($this);
        }

        return $this;
    }

    public function removeNotification(Notify $notification): self
    {
        if ($this->notifications->contains($notification)) {
            $this->notifications->removeElement($notification);
            // set the owning side to null (unless already changed)
            if ($notification->getWebsite() === $this) {
                $notification->setWebsite(null);
            }
        }

        return $this;
    }
}
