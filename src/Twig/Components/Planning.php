<?php

namespace App\Twig\Components;

use App\Repository\PlanningSlotRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class Planning
{
    public function __construct(private PlanningSlotRepository $slotRepository)
    {
    }

    public function getSlotsGroupedByDay(): array
    {
        return $this->slotRepository->findWeekSlots(new \DateTime('next week'));
    }
}
