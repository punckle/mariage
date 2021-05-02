<?php

namespace App\Entity;

use App\Repository\GuestPlusOneRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GuestPlusOneRepository::class)
 */
class GuestPlusOne
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Guest::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $guest;

    /**
     * @ORM\OneToOne(targetEntity=PlusOne::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $plusOne;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGuest(): ?Guest
    {
        return $this->guest;
    }

    public function setGuest(Guest $guest): self
    {
        $this->guest = $guest;

        return $this;
    }

    public function getPlusOne(): ?PlusOne
    {
        return $this->plusOne;
    }

    public function setPlusOne(PlusOne $plusOne): self
    {
        $this->plusOne = $plusOne;

        return $this;
    }
}
