<?php

namespace App\Twig\Components\Accordion;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class Item
{
    public string $id;
    public string $title;

    public function __construct()
    {
        $this->id = uniqid('accordion-item-');
    }
}
