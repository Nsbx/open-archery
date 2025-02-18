<?php

namespace App\Controller;

use App\Entity\SlotInstance;
use App\Entity\User;
use App\Traits\UtilsTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PlanningController extends AbstractController
{
    use UtilsTrait;

    private int $maxWeeksInYear;

    public function __construct(private EntityManagerInterface $em)
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

    // TODO : Change name of the route
    #[Route('/planning/unregister-user/{slotInstance}/{user}/{weekNumber}', name: 'app_unregister_user')]
    public function unregister(SlotInstance $slotInstance, User $user, int $weekNumber = 0): Response
    {
        if ($this->getUser() !== $user) {
            return $this->redirectToRoute('app_planning');
        }

        if ($slotInstance->getRegistrations()->contains($user)) {
            $slotInstance->removeRegistration($user);
            $this->em->flush();
        }

        return $this->redirectToRoute('app_planning', ['weekNumber' => $weekNumber]);
    }

    // TODO : Change name of the route
    #[Route('/planning/register-user/{slotInstance}/{user}/{weekNumber}', name: 'app_register_user')]
    public function register(SlotInstance $slotInstance, User $user, int $weekNumber = 0): Response
    {
        switch (true) {
            case $this->getUser() !== $user:
            case $slotInstance->getRegistrations()->count() === $slotInstance->getMaxCapacity():
                return $this->redirectToRoute('app_planning');
        }

        if (!$slotInstance->getRegistrations()->contains($user)) {
            $slotInstance->addRegistration($user);
            $this->em->flush();
        }

        return $this->redirectToRoute('app_planning', ['weekNumber' => $weekNumber]);
    }
}
