<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CompteStatut
 *
 * @ORM\Table(name="compte_statut")
 * @ORM\Entity
 */
class CompteStatut
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
     * @var bool
     *
     * @ORM\Column(name="compte_statut", type="boolean", nullable=false)
     */
    private $compteStatut = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="token", type="string", length=256, nullable=true)
     */
    private $token;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=256, nullable=false)
     */
    private $username;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompteStatut(): ?bool
    {
        return $this->compteStatut;
    }

    public function setCompteStatut(bool $compteStatut): self
    {
        $this->compteStatut = $compteStatut;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
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


}
