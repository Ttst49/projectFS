<?php

namespace App\Entity;

use App\Repository\VenueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: VenueRepository::class)]
class Venue
{
    #[Groups(["venue","event"])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(["venue","event"])]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $address = null;

    #[Groups(["venue"])]
    #[ORM\ManyToOne(inversedBy: 'venues')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Profile $owner = null;

    #[Groups(["venue"])]
    #[ORM\Column]
    private ?int $SIRET = null;

    #[Groups(["venue"])]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Groups(["venue"])]
    #[ORM\Column]
    private ?int $seatCapacity = null;

    #[Groups(["venue"])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    /**
     * @var Collection<int, Event>
     */
    #[ORM\OneToMany(targetEntity: Event::class, mappedBy: 'Venue', orphanRemoval: true)]
    private Collection $events;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->events = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getOwner(): ?Profile
    {
        return $this->owner;
    }

    public function setOwner(?Profile $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function getSIRET(): ?int
    {
        return $this->SIRET;
    }

    public function setSIRET(int $SIRET): static
    {
        $this->SIRET = $SIRET;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSeatCapacity(): ?int
    {
        return $this->seatCapacity;
    }

    public function setSeatCapacity(int $seatCapacity): static
    {
        $this->seatCapacity = $seatCapacity;

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

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): static
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->setVenue($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): static
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getVenue() === $this) {
                $event->setVenue(null);
            }
        }

        return $this;
    }
}
