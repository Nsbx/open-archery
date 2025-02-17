<?php

namespace App\Entity;

use App\Repository\SlotInstanceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SlotInstanceRepository::class)]
class SlotInstance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column(length: 255)]
    private ?string $location = null;

    #[ORM\Column]
    private ?bool $allowRegistration = null;

    #[ORM\Column]
    private ?bool $requiresEquipment = null;

    #[ORM\Column (nullable: true)]
    private ?int $distance = null;

    #[ORM\Column]
    private ?int $minLevel = null;

    #[ORM\Column]
    private ?int $maxLevel = null;

    #[ORM\Column]
    private ?int $maxCapacity = null;

    #[ORM\Column]
    private ?bool $isCancelled = false;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cancelReason = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'slotInstances')]
    #[ORM\OrderBy(['nickname' => 'ASC'])]
    private Collection $registrations;

    public function __construct()
    {
        $this->registrations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function isAllowRegistration(): ?bool
    {
        return $this->allowRegistration;
    }

    public function setAllowRegistration(bool $allowRegistration): static
    {
        $this->allowRegistration = $allowRegistration;

        return $this;
    }

    public function isRequiresEquipment(): ?bool
    {
        return $this->requiresEquipment;
    }

    public function setRequiresEquipment(bool $requiresEquipment): static
    {
        $this->requiresEquipment = $requiresEquipment;

        return $this;
    }

    public function getDistance(): ?int
    {
        return $this->distance;
    }

    public function setDistance(?int $distance): static
    {
        $this->distance = $distance;

        return $this;
    }

    public function getMaxCapacity(): ?int
    {
        return $this->maxCapacity;
    }

    public function setMaxCapacity(int $maxCapacity): static
    {
        $this->maxCapacity = $maxCapacity;

        return $this;
    }

    public function isCancelled(): ?bool
    {
        return $this->isCancelled;
    }

    public function setIsCancelled(bool $isCancelled): static
    {
        $this->isCancelled = $isCancelled;

        return $this;
    }

    public function getCancelReason(): string
    {
        return (string) $this->cancelReason;
    }

    public function setCancelReason(?string $cancelReason): static
    {
        $this->cancelReason = $cancelReason;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getRegistrations(): Collection
    {
        return $this->registrations;
    }

    public function addRegistration(User $registration): static
    {
        if (!$this->registrations->contains($registration)) {
            $this->registrations->add($registration);
        }

        return $this;
    }

    public function removeRegistration(User $registration): static
    {
        $this->registrations->removeElement($registration);

        return $this;
    }

    public function getMinLevel(): ?int
    {
        return $this->minLevel;
    }

    public function setMinLevel(int $minLevel): static
    {
        $this->minLevel = $minLevel;

        return $this;
    }

    public function getMaxLevel(): ?int
    {
        return $this->maxLevel;
    }

    public function setMaxLevel(int $maxLevel): static
    {
        $this->maxLevel = $maxLevel;

        return $this;
    }
}
