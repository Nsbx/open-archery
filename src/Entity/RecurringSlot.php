<?php

namespace App\Entity;

use App\Repository\RecurringSlotRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecurringSlotRepository::class)]
class RecurringSlot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $dayOfWeek = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $startTime = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $endTime = null;

    #[ORM\Column]
    private ?int $maxCapacity = null;

    #[ORM\Column]
    private ?int $minLevel = null;

    #[ORM\Column]
    private ?int $maxLevel = null;

    #[ORM\Column]
    private ?bool $requiresEquipment = null;

    #[ORM\Column]
    private ?bool $allowRegistration = null;

    #[ORM\Column(nullable: true)]
    private ?int $distance = null;

    #[ORM\Column(length: 255)]
    private ?string $location = null;

    #[ORM\Column]
    private ?bool $enabled = true;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'recurringSlots')]
    private Collection $permanentRegistrations;

    public function __construct()
    {
        $this->permanentRegistrations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDayOfWeek(): ?int
    {
        return $this->dayOfWeek;
    }

    public function setDayOfWeek(int $dayOfWeek): static
    {
        $this->dayOfWeek = $dayOfWeek;

        return $this;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTimeInterface $startTime): static
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->endTime;
    }

    public function setEndTime(\DateTimeInterface $endTime): static
    {
        $this->endTime = $endTime;

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

    public function isRequiresEquipment(): ?bool
    {
        return $this->requiresEquipment;
    }

    public function setRequiresEquipment(bool $requiresEquipment): static
    {
        $this->requiresEquipment = $requiresEquipment;

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

    public function getDistance(): ?int
    {
        return $this->distance;
    }

    public function setDistance(int $distance): static
    {
        $this->distance = $distance;

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

    public function isEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): static
    {
        $this->enabled = $enabled;

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
    public function getPermanentRegistrations(): Collection
    {
        return $this->permanentRegistrations;
    }

    public function addPermanentRegistration(User $permanentRegistration): static
    {
        if (!$this->permanentRegistrations->contains($permanentRegistration)) {
            $this->permanentRegistrations->add($permanentRegistration);
        }

        return $this;
    }

    public function removePermanentRegistration(User $permanentRegistration): static
    {
        $this->permanentRegistrations->removeElement($permanentRegistration);

        return $this;
    }

    public function generateSlotInstance(int $weekNumber): SlotInstance
    {
        $year = date('Y');

        $startDate = new \DateTime();
        $startDate->setISODate($year, $weekNumber, $this->dayOfWeek);
        $startDate->setTime((int) $this->startTime->format('H'), (int) $this->startTime->format('i'));

        $endDate = clone $startDate;
        $endDate->setTime((int) $this->endTime->format('H'), (int) $this->endTime->format('i'));

        $slotInstance = new SlotInstance();
        $slotInstance
            ->setStartDate($startDate)
            ->setEndDate($endDate)
            ->setLocation($this->location)
            ->setAllowRegistration($this->allowRegistration)
            ->setRequiresEquipment($this->requiresEquipment)
            ->setDistance($this->distance)
            ->setMaxCapacity($this->maxCapacity)
            ->setMinLevel($this->minLevel)
            ->setMaxLevel($this->maxLevel)
            ->setType($this->type)
        ;

        foreach ($this->permanentRegistrations as $permanentRegistration) {
            $slotInstance->addRegistration($permanentRegistration);
        }

        return $slotInstance;
    }
}
