<?php

namespace App\Twig\Components\Planning;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class Day
{
    public \DateTime $date;

    public array $slots;

    public function __construct()
    {
        $this->date = new \DateTime();
    }
}
