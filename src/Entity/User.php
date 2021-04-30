<?php

namespace App\Entity;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use PhpParser\Node\Scalar\String_;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity
  * @UniqueEntity(fields={"email"},
 * message = "L'email que avez indiqué est dèja utilisé")
 */
class User implements UserInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\OneToMany(targetEntity=Avis::class, mappedBy="$iduser",cascade={"all"},orphanRemoval=true)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="username est obligatoire")

     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=140, nullable=false)
     * @Assert\NotBlank(message="lastname est obligatoire")
     */
    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=140, nullable=false)
     * @Assert\NotBlank(message="firstname est obligatoire")
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=140, nullable=false)
     * @Assert\NotBlank(message="image est obligatoire")
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=140, nullable=false)
     * @Assert\NotBlank(message="email est obligatoire")
     * @Assert\Email(message = "The email '{{ value }}' is not a valid
    email.")
     */

    private $email;

    /**
     * @var string

     * @ORM\Column(type="string", length=140, nullable=true)
     * @Assert\Length(min="8",minMessage="Votre mot de passe doit faire minimum 8 caractères")
     * @Assert\EqualTo(propertyPath="confirm_password",message="
    Votre mot de passe doit etre le meme que celui que vous confirmez")
     */

    private $password;
    /**

     * @Assert\EqualTo(propertyPath="password",message="
    Votre mot de passe doit etre le meme que celui que vous confirmez")
     */
    public $confirm_password;



    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];



    /**
     * @var string
     *
     * @ORM\Column(name="birthDay", type="string", length=140, nullable=false)
     * @Assert\NotBlank(message="birthday est obligatoire")
     * @Assert\Date(message="birthday invalide")
     * @var string A "Y-m-d" formatted value
     */
    private $birthday;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=140, nullable=false)
     * @Assert\NotBlank(message="phone est obligatoire")
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="pays", type="string", length=140, nullable=false)
     * @Assert\NotBlank(message="pays est obligatoire")
     */
    private $pays;

    /**
     * @var string
     *
     * @ORM\Column(name="adress", type="string", length=140, nullable=false)
     * @Assert\NotBlank(message="adress est obligatoire")
     */
    private $adress;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=140, nullable=false)
     * @Assert\NotBlank(message="gender est obligatoire")
     */
    private $gender;

    /**
     * @var int
     *
     * @ORM\Column(name="validation", type="integer", nullable=false)
     * @Assert\NotBlank(message="validation est obligatoire")
     */
    private $validation;

    /**
     * @var string
     *
     * @ORM\Column(name="idcode", type="string", length=140, nullable=false)
     * @Assert\NotBlank(message="idcode est obligatoire")
     */
    private $idcode;





    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }



    public function setRoles(string $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getBirthday(): ?string
    {
        return $this->birthday;
    }

    public function setBirthday(string $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }


    public function setPays(string $pays): self
    {
        $this->pays = $pays;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getValidation(): ?int
    {
        return $this->validation;
    }



    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }




    public function setValidation(int $validation): self
    {
        $this->validation = $validation;

        return $this;
    }

    public function getIdcode(): ?string
    {
        return $this->idcode;
    }

    public function setIdcode(string $idcode): self
    {
        $this->idcode = $idcode;

        return $this;
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function __toString(): string
    {
        return $this->username;
    }

}
