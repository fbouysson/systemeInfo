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
     * @ORM\Column(name="dateArrivÃ©e", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $datearrivã©e = 'CURRENT_TIMESTAMP';

    public function getIdSalon(): ?int
    {
        return $this->idSalon;
    }

    public function getIdUser(): ?int
    {
        return $this->idUser;
    }

    public function getDatearrivã©e(): ?\DateTimeInterface
    {
        return $this->datearrivã©e;
    }

    public function setDatearrivã©e(\DateTimeInterface $datearrivã©e): self
    {
        $this->datearrivã©e = $datearrivã©e;

        return $this;
    }


}
