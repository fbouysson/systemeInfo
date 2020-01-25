<?php

namespace App\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * AffectationSalon
 *
 * @ORM\Table(name="affectation_salon")
 * @ORM\Entity
 */
class AffectationSalon
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_salon", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idSalon;

    /**
     * @var int
     *
     * @ORM\Column(name="id_user", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idUser;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="dateArrivee", type="datetime", nullable=true)
     */
    private $datearrivee;

    public function __construct()
    {
        $this->setDatearrivee (new DateTime());
    }

    public function getIdSalon(): ?int
    {
        return $this->idSalon;
    }

    public function setIdSalon(int $idSalon): self
    {
        $this->idSalon = $idSalon;
        return $this;
    }

    public function getIdUser(): ?int
    {
        return $this->idUser;
    }

    public function setIdUser(int $idUser): self
    {
        $this->idUser = $idUser;
        return $this;
    }

    public function getDatearrivee(): ?DateTimeInterface
    {
        return $this->datearrivee;
    }

    public function setDatearrivee(DateTimeInterface $datearrivee): self
    {
        $this->datearrivee = $datearrivee;

        return $this;
    }


}
