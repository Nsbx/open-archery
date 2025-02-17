<?php

namespace App\Twig\Components;

use App\Repository\SlotInstanceRepository;
use App\Traits\UtilsTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class Planning
{
    use DefaultActionTrait;

    public int $weekNumber = 0;

    public function __construct(private SlotInstanceRepository $slotRepository) {
    }

    #[LiveProp]
    public function getSlotsGroupedByDay(): array
    {
        return $this->slotRepository->findSlotInstancesByWeekNumber($this->weekNumber);
    }
}
