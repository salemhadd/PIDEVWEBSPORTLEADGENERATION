<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * EmploiDuTemps
 *
 * @ORM\Table(name="emploi_du_temps", indexes={@ORM\Index(name="fk_sea", columns={"IdSeance"}), @ORM\Index(name="fk_zoo", columns={"idzone"})})
 * @ORM\Entity
 */
class EmploiDuTemps
{
    /**
     * @var int
     *
     * @ORM\Column(name="IdEmploi", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idemploi;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Range(
     *      min = "now",
     *      max = "+1 month"
     * )
     */
    private $datee;

    /**
     * @var \Seance
     *
     * @ORM\ManyToOne(targetEntity="Seance")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="IdSeance", referencedColumnName="IdSeance")
     * })
     *@Assert\NotBlank(message="Id Seance is required")
     */
    private $idseance;

    /**
     * @var \Zonedacces
     *
     * @ORM\ManyToOne(targetEntity="Zonedacces")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idzone", referencedColumnName="idzone")
     * })
     *@Assert\NotBlank(message="Id Zone is required")
     */
    private $idzone;

    /**
     * @return \DateTime
     */
    public function getDatee(): ?\DateTimeInterface
    {
        return $this->datee;
    }


    public function setDatee(?\DateTimeInterface $datee): self
    {
        $this->datee = $datee;
        return $this;
    }

    /**
     * @return int
     */
    public function getIdemploi(): ?int
    {
        return $this->idemploi;
    }





    public function getIdseance(): ?Seance
    {
        return $this->idseance;
    }


    public function setIdseance(?Seance $idseance): self
    {
        $this->idseance = $idseance;
        return $this;
    }


    public function getIdzone(): ?Zonedacces
    {
        return $this->idzone;
    }


    public function setIdzone(?Zonedacces $idzone): self
    {
        $this->idzone = $idzone;
        return $this;
    }


}
