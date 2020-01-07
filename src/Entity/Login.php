<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Login
 *
 * @ORM\Table(name="login")
 * @ORM\Entity
 */
class Login
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

}
