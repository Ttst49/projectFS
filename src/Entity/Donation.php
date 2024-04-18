<?php

namespace App\Entity;

use App\Repository\DonationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: DonationRepository::class)]
class Donation
{
    #[Groups(["artist"])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(["artist"])]
    #[ORM\ManyToOne(inversedBy: 'donations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Profile $artist = null;

    #[Groups(["artist"])]
    #[ORM\Column(length: 255)]
    private ?string $spectatorName = null;

    #[Groups(["artist"])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[Groups(["artist"])]
    #[ORM\Column]
    private ?float $amount = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArtist(): ?Profile
    {
        return $this->artist;
    }

    public function setArtist(?Profile $artist): static
    {
        $this->artist = $artist;

        return $this;
    }

    public function getSpectatorName(): ?string
    {
        return $this->spectatorName;
    }

    public function setSpectatorName(string $spectatorName): static
    {
        $this->spectatorName = $spectatorName;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function __construct(){
        $this->createdAt = new \DateTime();
    }
}
