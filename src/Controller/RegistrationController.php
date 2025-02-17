<?php

namespace App\Controller;

use App\Entity\SlotInstance;
use App\Entity\User;
use App\Twig\Components\Planning\Slot;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RegistrationController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    #[Route('/unregister-user/{slotInstance}/{user}/{weekNumber}', name: 'app_unregister_user')]
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

    #[Route('/register-user/{slotInstance}/{user}/{weekNumber}', name: 'app_register_user')]
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
