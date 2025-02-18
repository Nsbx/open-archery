<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Validation;

final class RegisterController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    #[Route('/register/{userNickname}/{secret}', name: 'app_register', )]
    public function index(
        #[MapEntity(mapping: ['userNickname' => 'nickname'])]
        User $user,
        UuidV4 $secret,
        Request $request,
    ): Response {
        if (!$secret->equals($user->getSecret())) {
            $this->addFlash('error', 'Le secret est incorrect, veuillez contacter votre administrateur.');
        }

        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');

            $validator = Validation::createValidator();
            $errors = $validator->validate($email, [
                new Email()
            ]);

            if (count($errors) > 0) {
                $this->addFlash('error', 'Email invalide, veuillez réessayer ou contacter votre administrateur.');
            } else {
                $user->setEmail($email);
                $user->setPassword(new UuidV4());
                $this->em->persist($user);
                $this->em->flush();
                $this->addFlash('success', 'Inscription terminé, vous pouvez vous connecter.');
                return $this->redirectToRoute('app_login');
            }
        }

        return $this->render('register/index.html.twig', [
            'userNickname' => $user->getNickname(),
            'secret' => $secret,
        ]);
    }
}
