<?php

namespace App\Entity;

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
     * @var \DateTime
     *
     * @ORM\Column(name="dateArrivée", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $datearriv�e = 'CURRENT_TIMESTAMP';

    public function getIdSalon(): ?int
    {
        return $this->idSalon;
    }

    public function getIdUser(): ?int
    {
        return $this->idUser;
    }

    public function getDatearriv�e(): ?\DateTimeInterface
    {
        return $this->datearriv�e;
    }

    public function setDatearriv�e(\DateTimeInterface $datearriv�e): self
    {
        $this->datearriv�e = $datearriv�e;

        return $this;
    }


}
