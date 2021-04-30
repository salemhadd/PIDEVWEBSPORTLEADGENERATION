<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Abonnement
 *
 * @ORM\Table(name="abonnement", indexes={@ORM\Index(name="fk_activite", columns={"activite_id"})})
 * @ORM\Entity
 */
class Abonnement
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="prix", type="string", length=30, nullable=false)
     *  *@Assert\NotBlank(message= "Prix is required"))
     * @Assert\Length(
     *      min = 2,
     *      max = 30,
     *      minMessage = "Ce champ doit avoir min {{ limit }} characteres",
     *      maxMessage = "Ce champ doit avoir max {{ limit }} characteres"
     * )
     */
    private $prix;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=30, nullable=false)
     *  *@Assert\NotBlank(message= "Type is required"))
     * @Assert\Length(
     *      min = 2,
     *      max = 30,
     *      minMessage = "Ce champ doit avoir min {{ limit }} characteres",
     *      maxMessage = "Ce champ doit avoir max {{ limit }} characteres"
     * )
     */
    private $type;

    /**
     * @var \Activite
     *
     * @ORM\ManyToOne(targetEntity="Activite")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="activite_id", referencedColumnName="id")
     * })
     */
    private $activite;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getActivite(): ?Activite
    {
        return $this->activite;
    }

    public function setActivite(?Activite $activite): self
    {
        $this->activite = $activite;

        return $this;
    }
    public function __toString(): string
    {
        return $this->nom;
    }


}
