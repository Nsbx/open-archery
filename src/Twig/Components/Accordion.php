<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class Accordion
{
    public string $id;

    public function __construct()
    {
        $this->id = uniqid('accordion-');
    }
}
