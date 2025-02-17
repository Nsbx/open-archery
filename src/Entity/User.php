<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_NICKNAME', fields: ['nickname'])]
class User implements UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $nickname = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column]
    private ?int $level = null;

    #[ORM\Column]
    private ?bool $hasEquipment = null;

    /**
     * @var Collection<int, RecurringSlot>
     */
    #[ORM\ManyToMany(targetEntity: RecurringSlot::class, mappedBy: 'permanentRegistrations')]
    private Collection $recurringSlots;

    /**
     * @var Collection<int, SlotInstance>
     */
    #[ORM\ManyToMany(targetEntity: SlotInstance::class, mappedBy: 'registrations')]
    private Collection $slotInstances;

    // Not used but needed for passwordless system
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $password = null;

    public function __construct()
    {
        $this->recurringSlots = new ArrayCollection();
        $this->slotInstances = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->nickname;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): static
    {
        $this->level = $level;

        return $this;
    }

    public function hasEquipment(): ?bool
    {
        return $this->hasEquipment;
    }

    public function setHasEquipment(bool $hasEquipment): static
    {
        $this->hasEquipment = $hasEquipment;

        return $this;
    }

    /**
     * @return Collection<int, RecurringSlot>
     */
    public function getRecurringSlots(): Collection
    {
        return $this->recurringSlots;
    }

    public function addRecurringSlot(RecurringSlot $recurringSlot): static
    {
        if (!$this->recurringSlots->contains($recurringSlot)) {
            $this->recurringSlots->add($recurringSlot);
            $recurringSlot->addPermanentRegistration($this);
        }

        return $this;
    }

    public function removeRecurringSlot(RecurringSlot $recurringSlot): static
    {
        if ($this->recurringSlots->removeElement($recurringSlot)) {
            $recurringSlot->removePermanentRegistration($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, SlotInstance>
     */
    public function getSlotInstances(): Collection
    {
        return $this->slotInstances;
    }

    public function addSlotInstance(SlotInstance $slotInstance): static
    {
        if (!$this->slotInstances->contains($slotInstance)) {
            $this->slotInstances->add($slotInstance);
            $slotInstance->addRegistration($this);
        }

        return $this;
    }

    public function removeSlotInstance(SlotInstance $slotInstance): static
    {
        if ($this->slotInstances->removeElement($slotInstance)) {
            $slotInstance->removeRegistration($this);
        }

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getInitials(): string
    {
        $initials = '';
        $nameParts = explode(' ', $this->nickname);
        foreach ($nameParts as $namePart) {
            $initials .= $namePart[0];
        }
        return $initials;
    }

    public function __toString(): string
    {
        return $this->nickname;
    }
}
