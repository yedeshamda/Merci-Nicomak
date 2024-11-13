<?php

namespace App\Entity;

use App\Repository\MerciRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTimeInterface;

/**
 * @ORM\Entity(repositoryClass=MerciRepository::class)
 */
class Merci
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Employee::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $fromEmployee;

    /**
     * @ORM\ManyToOne(targetEntity=Employee::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $toEmployee;

    /**
     * @ORM\Column(type="text")
     */
    private $message;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFromEmployee(): ?Employee
    {
        return $this->fromEmployee;
    }

    public function setFromEmployee(Employee $fromEmployee): self
    {
        $this->fromEmployee = $fromEmployee;

        return $this;
    }

    public function getToEmployee(): ?Employee
    {
        return $this->toEmployee;
    }

    public function setToEmployee(Employee $toEmployee): self
    {
        $this->toEmployee = $toEmployee;

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

    public function getDate(): ?DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}