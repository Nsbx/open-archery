<?php

namespace App\Controller;

use App\Traits\UtilsTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PlanningController extends AbstractController
{
    use UtilsTrait;

    private int $maxWeeksInYear;

    public function __construct()
    {
        $this->maxWeeksInYear = $this->getIsoWeeksInYear((int) date('Y'));
    }

    #[Route('/planning/{weekNumber}', name: 'app_planning', defaults: ['weekNumber' => -1])]
    public function index(int $weekNumber): Response
    {
        if ($weekNumber === -1) {
            $weekNumber = (int) date('W');
        }

        if ($weekNumber > $this->maxWeeksInYear) {
            return $this->redirectToRoute('app_planning', ['weekNumber' => $this->maxWeeksInYear]);
        }

        if ($weekNumber < 1) {
            return $this->redirectToRoute('app_planning', ['weekNumber' => 1]);
        }

        return $this->render('planning/index.html.twig', [
            'weekNumber' => $weekNumber,
        ]);
    }
}
