<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Zonedacces
 *
 * @ORM\Table(name="zonedacces")
 * @ORM\Entity
 */
class Zonedacces
{
    /**
     * @var int
     *
     * @ORM\Column(name="idzone", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")




     */
    private $idzone;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=30, nullable=false)
     * *@Assert\NotBlank(message= "Nom is required"))
     * @Assert\Length(
     *      min = 2,
     *      max = 30,
     *      minMessage = "Le nom doit avoir min {{ limit }} characteres",
     *      maxMessage = "Le nom doit avoir max {{ limit }} characteres"
     * )



     */

    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="horaireouverture", type="string", length=30, nullable=false)
     * *@Assert\NotBlank(message= "Horaire ouverture is required"))
     * @Assert\Length(
     *      min = 2,
     *      max = 30,
     *      minMessage = "Ce champ doit avoir min {{ limit }} characteres",
     *      maxMessage = "Ce champ doit avoir max {{ limit }} characteres"
     * )

     */
    private $horaireouverture;

    /**
     * @var string
     *
     * @ORM\Column(name="horairecloture", type="string", length=30, nullable=false)
     * *@Assert\NotBlank(message= "Horaire cloture is required"))
     *  * @Assert\Length(
     *      min = 2,
     *      max = 30,
     *      minMessage = "Ce champ doit avoir min {{ limit }} characteres",
     *      maxMessage = "Ce champ doit avoir max {{ limit }} characteres"
     * )
     */
    private $horairecloture;


    public function getIdzone(): ?int
    {
        return $this->idzone;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getHoraireouverture(): ?string
    {
        return $this->horaireouverture;
    }

    public function setHoraireouverture(string $horaireouverture): self
    {
        $this->horaireouverture = $horaireouverture;

        return $this;
    }

    public function getHorairecloture(): ?string
    {
        return $this->horairecloture;
    }

    public function setHorairecloture(string $horairecloture): self
    {
        $this->horairecloture = $horairecloture;

        return $this;
    }

    public function __toString():string
    {
        // TODO: Implement __toString() method.
        return $this->nom;
    }



}
