<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Salons
 *
 * @ORM\Table(name="salons")
 * @ORM\Entity
 */
class Salons
{
    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\Column(name="id_salon", type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name_salon", type="string", length=45, nullable=false)
     */
    private $nameSalon;

    /**
     * @var int
     *
     * @ORM\Column(name="id_createur_salon", type="integer", nullable=false)
     */
    private $idCreateurSalon;

    /**
     * @var string
     *
     * @ORM\Column(name="icon_salon", type="string", length=45, nullable=true)
     */
    private $iconSalon = '';

    public function __construct()
    {
        $this->logs = new ArrayCollection();
    }

    public function getIdSalon(): ?int
    {
        return $this->id;
    }

    public function getNameSalon(): ?string
    {
        return $this->nameSalon;
    }

    public function setNameSalon(string $nameSalon): self
    {
        $this->nameSalon = $nameSalon;

        return $this;
    }

    public function getIdCreateurSalon(): ?int
    {
        return $this->idCreateurSalon;
    }

    public function setIdCreateurSalon(int $idCreateurSalon): self
    {
        $this->idCreateurSalon = $idCreateurSalon;

        return $this;
    }

    public function getIconSalon(): ?string
    {
        return $this->iconSalon;
    }

    public function setIconSalon(string $iconSalon): self
    {
        $this->iconSalon = $iconSalon;

        return $this;
    }

}
