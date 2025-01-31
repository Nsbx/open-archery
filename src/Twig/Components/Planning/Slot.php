<?php

namespace App\Twig\Components\Planning;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class Slot
{
    public string $type;
    public string $time;
    public int $maxCapacity = 0;
    public Collection $participants;
    public bool $isOpen = false;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
    }

    public function getSlotCapacity(): string
    {
        return sprintf('%s/%s', count($this->participants), $this->maxCapacity);
    }

    public function isFull(): bool
    {
        return count($this->participants) === $this->maxCapacity;
    }

    public function getSlotColor(): string
    {
        if (str_contains($this->type, 'Cours')) {
            return 'slot-special';
        }

        if (count($this->participants) < $this->maxCapacity) {
            return 'slot-available';
        }

        return 'slot-full';
    }
}
