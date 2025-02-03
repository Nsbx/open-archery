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
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

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

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function updateNickname(): static
    {
        $this->nickname = sprintf('%s %s', $this->firstname, $this->lastname);

        return $this;
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

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        $this->updateNickname();

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $lastname = trim(preg_replace('/\s+/', ' ', $lastname));

        $nameParts = explode(' ', $lastname);

        $initials = array_map(static function($part) {
            return mb_strtoupper(mb_substr($part, 0, 1));
        }, $nameParts);

        $this->lastname = implode('.', $initials);

        $this->updateNickname();

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
}
