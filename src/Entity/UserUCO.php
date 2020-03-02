<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="UserUCO")
 * @ORM\Entity(repositoryClass="App\Repository\UserUCORepository")
 * @UniqueEntity(fields={"username"}, message="There is already an account with this username")
 */
class UserUCO implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(name="id_user", type="integer", nullable=false)
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="user_nom", type="string", length=45, nullable=true)
     */
    private $userNom;

    /**
     * @var string|null
     *
     * @ORM\Column(name="user_prenom", type="string", length=45, nullable=true)
     */
    private $userPrenom;

    /**
     * @ORM\Column(name="login_username", type="string", length=180, nullable=false, unique=true)
     */
    private $username;

    /**
     * @var string|null
     *
     * @ORM\Column(name="user_email", type="string", length=45, nullable=true, unique=true)
     */
    private $userEmail;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="user_date_arrivee", type="datetime",length=10, nullable=false)
     */

    private $userDateArrivee;

    /**
     * @ORM\Column(name="roles" ,type="json")
     */
    private $roles = [];

    public function __construct()
    {
        $this->setUserDateArrivee(new DateTime());
    }

    /**
     * @var string The hashed password
     * @ORM\Column(name="login_password", type="string",type="string", nullable=false)
     * @Assert\Regex(pattern="/^(?=.*[A-z])(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\W+)\S{6,}$/", match=true, message="Le mot de passe doit contenir une majuscule, une minuscule et un caractère spécial")
     */
    private $password;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUserNom(): ?string
    {
        return $this->userNom;
    }

    public function setUserNom(?string $userNom): self
    {
        $this->userNom = $userNom;

        return $this;
    }

    public function getUserPrenom(): ?string
    {
        return $this->userPrenom;
    }

    public function setUserPrenom(?string $userPrenom): self
    {
        $this->userPrenom = $userPrenom;

        return $this;
    }

    public function getUserEmail(): ?string
    {
        return $this->userEmail;
    }

    public function setUserEmail(?string $userEmail): self
    {
        $this->userEmail = $userEmail;

        return $this;
    }

    public function getUserDateArrivee(): ?DateTime
    {
        return $this->userDateArrivee;
    }

    public function setUserDateArrivee(DateTime $userDateArrivee): self
    {
        $this->userDateArrivee = $userDateArrivee;

        return $this;
    }
}
