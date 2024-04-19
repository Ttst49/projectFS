<?php

namespace App\Entity;

use App\Repository\ProfileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ProfileRepository::class)]
class Profile
{
    #[Groups(["artist","user","event"])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(["artist","user","event"])]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Groups(["artist","user","event"])]
    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[Groups(["artist","user","event"])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[Groups(["artist","venue","event"])]
    #[ORM\OneToOne(inversedBy: 'profile', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $relatedTo = null;

    /**
     * @var Collection<int, Venue>
     */
    #[ORM\OneToMany(targetEntity: Venue::class, mappedBy: 'owner', orphanRemoval: true)]
    private Collection $venues;

    /**
     * @var Collection<int, Ticket>
     */
    #[ORM\OneToMany(targetEntity: Ticket::class, mappedBy: 'owner', orphanRemoval: true)]
    private Collection $tickets;

    /**
     * @var Collection<int, Donation>
     */
    #[ORM\OneToMany(targetEntity: Donation::class, mappedBy: 'artist', orphanRemoval: true)]
    private Collection $donations;

    #[Groups(["user"])]
    #[ORM\Column(length: 255)]
    private ?string $role = null;

    /**
     * @var Collection<int, Event>
     */
    #[ORM\ManyToMany(targetEntity: Event::class, mappedBy: 'artists')]
    private Collection $eventsAsArtist;

    /**
     * @var Collection<int, Event>
     */
    #[ORM\ManyToMany(targetEntity: Event::class, inversedBy: 'spectators')]
    private Collection $eventAsSpectator;

    /**
     * @var Collection<int, Event>
     */
    #[ORM\OneToMany(targetEntity: Event::class, mappedBy: 'owner', orphanRemoval: true)]
    private Collection $eventsAsOwner;

    /**
     * @var Collection<int, self>
     */
    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'followedArtists')]
    private Collection $followedArtists;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

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

    public function getRelatedTo(): ?User
    {
        return $this->relatedTo;
    }

    public function setRelatedTo(User $relatedTo): static
    {
        $this->relatedTo = $relatedTo;

        return $this;
    }

    public function __construct(){
        $this->role = "spectator";
        $this->createdAt = new \DateTime();
        $this->name = "not defined yet";
        $this->lastName = "not defined yet";
        $this->venues = new ArrayCollection();
        $this->tickets = new ArrayCollection();
        $this->donations = new ArrayCollection();
        $this->eventsAsArtist = new ArrayCollection();
        $this->eventAsSpectator = new ArrayCollection();
        $this->eventsAsOwner = new ArrayCollection();
        $this->followedArtists = new ArrayCollection();
    }

    /**
     * @return Collection<int, Venue>
     */
    public function getVenues(): Collection
    {
        return $this->venues;
    }

    public function addVenue(Venue $venue): static
    {
        if (!$this->venues->contains($venue)) {
            $this->venues->add($venue);
            $venue->setOwner($this);
        }

        return $this;
    }

    public function removeVenue(Venue $venue): static
    {
        if ($this->venues->removeElement($venue)) {
            // set the owning side to null (unless already changed)
            if ($venue->getOwner() === $this) {
                $venue->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Ticket>
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(Ticket $ticket): static
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets->add($ticket);
            $ticket->setOwner($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): static
    {
        if ($this->tickets->removeElement($ticket)) {
            // set the owning side to null (unless already changed)
            if ($ticket->getOwner() === $this) {
                $ticket->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Donation>
     */
    public function getDonations(): Collection
    {
        return $this->donations;
    }

    public function addDonation(Donation $donation): static
    {
        if (!$this->donations->contains($donation)) {
            $this->donations->add($donation);
            $donation->setArtist($this);
        }

        return $this;
    }

    public function removeDonation(Donation $donation): static
    {
        if ($this->donations->removeElement($donation)) {
            // set the owning side to null (unless already changed)
            if ($donation->getArtist() === $this) {
                $donation->setArtist(null);
            }
        }

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEventsAsArtist(): Collection
    {
        return $this->eventsAsArtist;
    }

    public function addEventsAsArtist(Event $eventsAsArtist): static
    {
        if (!$this->eventsAsArtist->contains($eventsAsArtist)) {
            $this->eventsAsArtist->add($eventsAsArtist);
            $eventsAsArtist->addArtist($this);
        }

        return $this;
    }

    public function removeEventsAsArtist(Event $eventsAsArtist): static
    {
        if ($this->eventsAsArtist->removeElement($eventsAsArtist)) {
            $eventsAsArtist->removeArtist($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEventAsSpectator(): Collection
    {
        return $this->eventAsSpectator;
    }

    public function addEventAsSpectator(Event $eventAsSpectator): static
    {
        if (!$this->eventAsSpectator->contains($eventAsSpectator)) {
            $this->eventAsSpectator->add($eventAsSpectator);
        }

        return $this;
    }

    public function removeEventAsSpectator(Event $eventAsSpectator): static
    {
        $this->eventAsSpectator->removeElement($eventAsSpectator);

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEventsAsOwner(): Collection
    {
        return $this->eventsAsOwner;
    }

    public function addEventsAsOwner(Event $eventsAsOwner): static
    {
        if (!$this->eventsAsOwner->contains($eventsAsOwner)) {
            $this->eventsAsOwner->add($eventsAsOwner);
            $eventsAsOwner->setOwner($this);
        }

        return $this;
    }

    public function removeEventsAsOwner(Event $eventsAsOwner): static
    {
        if ($this->eventsAsOwner->removeElement($eventsAsOwner)) {
            // set the owning side to null (unless already changed)
            if ($eventsAsOwner->getOwner() === $this) {
                $eventsAsOwner->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getFollowedArtists(): Collection
    {
        return $this->followedArtists;
    }

    public function addFollowedArtist(self $followedArtist): static
    {
        if (!$this->followedArtists->contains($followedArtist)) {
            $this->followedArtists->add($followedArtist);
        }

        return $this;
    }

    public function removeFollowedArtist(self $followedArtist): static
    {
        $this->followedArtists->removeElement($followedArtist);

        return $this;
    }
}
