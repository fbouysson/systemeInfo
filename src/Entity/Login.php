<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Login
 *
 * @ORM\Table(name="login")
 * @ORM\Entity
 */
class Login implements UserInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_login", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idLogin;

    /**
     * @var int
     *
     * @ORM\Column(name="login_id_user", type="integer", nullable=false)
     */
    private $loginIdUser;

    /**
     * @var string
     *
     * @ORM\Column(name="login_username", type="string", length=45, nullable=false)
     */
    private $loginUsername;

    /**
     * @var string
     *
     * @ORM\Column(name="login_password", type="string", length=45, nullable=false)
     */
    private $loginPassword;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\UserUCO", inversedBy="login")
     */
    //private $user;

    public function getIdLogin(): ?int
    {
        return $this->idLogin;
    }

    public function getLoginIdUser(): ?int
    {
        return $this->loginIdUser;
    }

    public function setLoginIdUser(int $loginIdUser): self
    {
        $this->loginIdUser = $loginIdUser;

        return $this;
    }

    public function getLoginUsername(): ?string
    {
        return $this->loginUsername;
    }

    public function setLoginUsername(string $loginUsername): self
    {
        $this->loginUsername = $loginUsername;

        return $this;
    }

    public function getLoginPassword(): ?string
    {
        return $this->loginPassword;
    }

    public function setLoginPassword(string $loginPassword): self
    {
        $this->loginPassword = $loginPassword;

        return $this;
    }


    /**
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return ['ROLE_USER'];
     *     }
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string|null The encoded password if any
     */
    public function getPassword()
    {
        return $this->loginPassword;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->loginUsername;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        return null;
    }

    /*public function getUser(): ?UserUCO
    {
        return $this->user;
    }

    public function setUser(?UserUCO $user): self
    {
        $this->user = $user;

        return $this;
    }*/
}
