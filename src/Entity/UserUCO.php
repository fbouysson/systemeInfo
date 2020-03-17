<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 *
 * @ORM\Entity(repositoryClass="App\Repository\UserUCORepository")
 */
class UserUCO implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, nullable=false, unique=true)
     */
    private $username;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=45, nullable=false, unique=true)
     */
    private $userEmail;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime",length=10, nullable=false)
     */

    private $userDateArrivee;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    public function __construct()
    {
        $this->setUserDateArrivee(new DateTime());
    }

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", nullable=false)
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

    public function getUserEmail(): ?string
    {
        return $this->userEmail;
    }

    public function setUserEmail(?string $userEmail): self
    {
        $this->userEmail = $userEmail;

        return $this;
    }

    public function getUserDateArrivee(): DateTime
    {
        return $this->userDateArrivee;
    }

    public function setUserDateArrivee(DateTime $userDateArrivee): self
    {
        $this->userDateArrivee = $userDateArrivee;

        return $this;
    }
}
