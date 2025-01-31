<?php

namespace App\Twig\Components\Planning;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class Slot
{
    public string $type;
    public string $time;
    public int $maxSlots = 0;
    public array $slots = [];

    public function getSlotCapacity(): string
    {
        return sprintf('%s/%s', count($this->slots), $this->maxSlots);
    }

    public function isFull(): bool
    {
        return count($this->slots) === $this->maxSlots;
    }

    public function getSlotColor(): string
    {
        if (str_contains($this->type, 'Cours')) {
            return 'slot-special';
        }

        if (count($this->slots) < $this->maxSlots) {
            return 'slot-available';
        }

        return 'slot-full';
    }
}
