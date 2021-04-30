<?php

namespace App\Entity;

use App\Repository\NotifUserRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NotifUserRepository::class)
 */
class NotifUser
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $notifuser;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNotifuser(): ?string
    {
        return $this->notifuser;
    }

    public function setNotifuser(string $notifuser): self
    {
        $this->notifuser = $notifuser;

        return $this;
    }
}
