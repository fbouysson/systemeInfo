<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Messages
 *
 * @ORM\Table(name="messages")
 * @ORM\Entity
 */
class Messages
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_messages", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idMessages;

    /**
     * @var int
     *
     * @ORM\Column(name="id_user", type="integer", nullable=false)
     */
    private $idUser;

    /**
     * @var int
     *
     * @ORM\Column(name="id_salon", type="integer", nullable=false)
     */
    private $idSalon;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="string", length=255, nullable=false)
     */
    private $message;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=45, nullable=false)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=3, nullable=false)
     */
    private $type;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date;

    /**
     * Messages constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->date = new DateTime();
    }

    public function getIdMessages(): ?int
    {
        return $this->idMessages;
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

    public function getIdSalon(): ?int
    {
        return $this->idSalon;
    }

    public function setIdSalon(int $idSalon): self
    {
        $this->idSalon = $idSalon;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * @param DateTime $date
     */
    public function setDate(DateTime $date): void
    {
        $this->date = $date;
    }
}
