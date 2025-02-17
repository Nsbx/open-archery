<?php

declare(strict_types=1);

namespace App\Traits;

trait UtilsTrait
{
    private function getIsoWeeksInYear($year): int {
        $date = new \DateTime();
        $date->setISODate((int) $year, 53);
        return ($date->format("W") === "53" ? 53 : 52);
    }
}
