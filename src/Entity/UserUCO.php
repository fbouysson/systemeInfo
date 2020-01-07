<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity
 */
class UserUCO
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_user", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idUser;

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
     * @var string|null
     *
     * @ORM\Column(name="user_email", type="string", length=45, nullable=true)
     */
    private $userEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="user_date_arrivee", type="string",length=10, nullable=false)
     */
    private $userDateArrivee;

    /**
     * @var string
     *
     * @ORM\Column(name="user_role", type="string", length=45, nullable=false, options={"default"="USER"})
     */
    private $userRole = 'USER';

    public function getIdUser(): ?int
    {
        return $this->idUser;
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

    public function getUserDateArrivee(): ?string
    {
        return $this->userDateArrivee;
    }

    public function setUserDateArrivee(string $userDateArrivee): self
    {
        $this->userDateArrivee = $userDateArrivee;

        return $this;
    }

    public function getUserRole(): ?string
    {
        return $this->userRole;
    }

    public function setUserRole(string $userRole): self
    {
        $this->userRole = $userRole;

        return $this;
    }

}
