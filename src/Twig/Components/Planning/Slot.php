<?php

namespace App\Twig\Components\Planning;

use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class Slot
{
    public int        $id;
    public string     $type;
    public string     $time;
    public int        $maxCapacity       = 0;
    public Collection $participants;
    public int        $minLevel          = 1;
    public int        $maxLevel          = 3;
    public bool       $allowRegistration = false;
    public bool       $requiresEquipment = false;
    public bool       $isCancelled       = false;
    public string     $cancelReason      = '';
    public int        $weekNumber        = 0;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
    }

    public function getSlotCapacity(): string
    {
        return sprintf('%s/%s', count($this->participants), $this->maxCapacity);
    }

    public function getSlotStatus(): string
    {
        switch (true) {
            case $this->isFull():
                return 'full';
            case !str_contains($this->type, 'Cours'):
                return 'special';
            case $this->isCancelled:
                return 'cancelled';
            default:
                return 'available';
        }
    }

    public function getLevelRange(): string
    {
        if ($this->minLevel === $this->maxLevel) {
            return (string) $this->minLevel;
        }

        return sprintf('%s-%s', $this->minLevel, $this->maxLevel);
    }

    public function isFull(): bool
    {
        return count($this->participants) === $this->maxCapacity;
    }

    public function checkUserIsRegistered(User $user): bool
    {
        return $this->participants->contains($user);
    }

    public function checkUserLevel(int $level): bool
    {
        return $level >= $this->minLevel && $level <= $this->maxLevel;
    }

    public function userCanRegister(User $user): bool
    {
        if ($this->isFull()) {
            return false;
        }

        if ($this->isCancelled) {
            return false;
        }

        if (!$this->allowRegistration) {
            return false;
        }

        if ($this->requiresEquipment && !$user->hasEquipment()) {
            return false;
        }

        if (!$this->checkUserLevel($user->getLevel())) {
            return false;
        }

        return true;
    }
}
