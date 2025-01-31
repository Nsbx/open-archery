<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[ORM\Column(type: Types::GUID, nullable: true)]
    private ?string $passkey = null;

    #[ORM\Column]
    private ?bool $isAdmin = null;

    #[ORM\Column(length: 255)]
    private ?string $level = null;

    #[ORM\Column]
    private ?int $experienceYears = null;

    #[ORM\Column]
    private ?int $registrationYear = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    /**
     * @var Collection<int, PlanningSlot>
     */
    #[ORM\ManyToMany(targetEntity: PlanningSlot::class, mappedBy: 'participants')]
    private Collection $planningSlots;

    public function __construct()
    {
        $this->planningSlots = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        // Stores only the first letter of the last name for GDPR compliance.
        $this->lastName = mb_substr($lastName, 0, 1);

        return $this;
    }

    public function getPasskey(): ?string
    {
        return $this->passkey;
    }

    public function generatePasskey(): void
    {
        $this->passkey = Uuid::v4();
    }

    public function clearPasskey(): void
    {
        $this->passkey = null;
    }

    public function isAdmin(): ?bool
    {
        return $this->isAdmin;
    }

    public function setIsAdmin(bool $isAdmin): static
    {
        $this->isAdmin = $isAdmin;

        return $this;
    }

    public function getLevel(): ?string
    {
        return $this->level;
    }

    public function setLevel(string $level): static
    {
        $this->level = $level;

        return $this;
    }

    public function getExperienceYears(): ?int
    {
        return $this->experienceYears;
    }

    public function setExperienceYears(int $experienceYears): static
    {
        $this->experienceYears = $experienceYears;

        return $this;
    }

    public function getRegistrationYear(): ?int
    {
        return $this->registrationYear;
    }

    public function setRegistrationYear(int $registrationYear): static
    {
        $this->registrationYear = $registrationYear;

        return $this;
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

    /**
     * @return Collection<int, PlanningSlot>
     */
    public function getPlanningSlots(): Collection
    {
        return $this->planningSlots;
    }

    public function addPlanningSlot(PlanningSlot $planningSlot): static
    {
        if (!$this->planningSlots->contains($planningSlot)) {
            $this->planningSlots->add($planningSlot);
            $planningSlot->addParticipant($this);
        }

        return $this;
    }

    public function removePlanningSlot(PlanningSlot $planningSlot): static
    {
        if ($this->planningSlots->removeElement($planningSlot)) {
            $planningSlot->removeParticipant($this);
        }

        return $this;
    }

    public function getNickname(): ?string
    {
        return sprintf('%s %s.', $this->firstName, $this->lastName);
    }
}
