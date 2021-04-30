<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Reclamation
 *
 * @ORM\Table(name="reclamation", indexes={@ORM\Index(name="IDX_CE606404C54C8C93", columns={"type_id"}), @ORM\Index(name="IDX_CE606404D5E86FF", columns={"etat_id"}), @ORM\Index(name="IDX_CE606404A76ED395", columns={"user_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\ReclamationRepository")
 */
class Reclamation
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
     *  @Assert\NotBlank(message="le Contenu est obligatoire")
     * @ORM\Column(name="contenu", type="string", length=255, nullable=false)
     */
    private $contenu;

    /**
     * @var \DateTime|null
     * @Assert\NotBlank(message="la Date est obligatoire")
     * @ORM\Column(name="daterec", type="date", nullable=true, options={"default"="NULL"})
     */
    private $daterec ;

    /**
     * @var \User
     * @Assert\NotBlank(message="le User est obligatoire")
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var \TypeReclamation
     * @Assert\NotBlank(message="le Type est obligatoire")
     * @ORM\ManyToOne(targetEntity="TypeReclamation", inversedBy="reclamations")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     * })
     */
    private $type;

    /**
     * @var \Etatreclamation
     * @Assert\NotBlank(message="l'etat est obligatoire")
     * @ORM\ManyToOne(targetEntity="Etatreclamation" , inversedBy="reclamations")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="etat_id", referencedColumnName="id")
     * })
     */
    private $etat;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getDaterec(): ?\DateTimeInterface
    {
        return $this->daterec;
    }

    public function setDaterec(?\DateTimeInterface $daterec): self
    {
        $this->daterec = $daterec;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getType(): ?TypeReclamation
    {
        return $this->type;
    }

    public function setType(?TypeReclamation $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getEtat(): ?Etatreclamation
    {
        return $this->etat;
    }

    public function setEtat(?Etatreclamation $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function __toString(): string
    {
        return $this->contenu;
    }

}
