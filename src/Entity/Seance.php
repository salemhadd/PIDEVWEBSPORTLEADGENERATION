<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Seance
 *
 * @ORM\Table(name="seance", indexes={@ORM\Index(name="fk_seance2", columns={"activiteid"})})
 * @ORM\Entity
 */
class Seance
{
    /**
     * @var int
     *
     * @ORM\Column(name="IdSeance", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idseance;

    /**
     * @var int
     *
     * @ORM\Column(name="Capacite", type="integer", nullable=false)
     *@Assert\NotBlank(message="Capacite is required")
     */
    private $capacite;

    /**
     * @var int
     *
     * @ORM\Column(name="IdCoach", type="integer", nullable=false)
     *@Assert\NotBlank(message="Id Coach is required")
     */
    private $idcoach;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Range(
     *      min = "now",
     *      max = "+1 month"
     * )
     */
    private $dates;

    /**
     * @var \Activite
     *
     * @ORM\ManyToOne(targetEntity="Activite")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="activiteid", referencedColumnName="id")
     * })
     *@Assert\NotBlank(message="Activite is required")
     */
    private $activiteid;

    /**
     * @return int
     */
    public function getIdseance(): int
    {
        return $this->idseance;
    }




    public function getCapacite(): ?int
    {
        return $this->capacite;
    }


    public function setCapacite(int $capacite): self
    {
        $this->capacite = $capacite;
        return $this;
    }


    public function getIdcoach(): ?int
    {
        return $this->idcoach;
    }


    public function setIdcoach(int $idcoach): self
    {
        $this->idcoach = $idcoach;
        return $this;
    }

    /**
     *
     * @return \DateTime
     */
    public function getDates(): ?\DateTimeInterface
    {
        return $this->dates;
    }


    public function setDates(?\DateTimeInterface $dates): self
    {
        $this->dates = $dates;
        return $this;
    }


    public function getActiviteid(): ?Activite
    {
        return $this->activiteid;
        return $this;
    }


    public function setActiviteid(?Activite $activiteid): self
    {
        $this->activiteid = $activiteid;
        return $this;
    }

    public function __toString():string
    {
        // TODO: Implement __toString() method.
        return $this->activiteid;
    }


}
