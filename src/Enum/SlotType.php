<?php

declare(strict_types=1);

namespace App\Enum;

enum SlotType: string
{
    case COURS_DEBUTANT          = 'Cours Débutant';
    case COURS_CONFIRME          = 'Cours Confirmé';
    case SESSION_LIBRE           = 'Session Libre';
    case SESSION_SPECIAL_VACANCE = 'Session Special Vacance';
    case SESSION_COMPETITION     = 'Session Competition';
}
