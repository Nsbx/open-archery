<?php

namespace App\Controller;

use App\Notifier\CustomLoginLinkNotification;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\LoginLink\LoginLinkHandlerInterface;

final class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function requestLoginLink(
        NotifierInterface $notifier,
        LoginLinkHandlerInterface $loginLinkHandler,
        UserRepository $userRepository,
        Request $request
    ): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->getPayload()->get('email');
            $user  = $userRepository->findOneBy(['email' => $email]);

            if ($user === null) {
                $this->addFlash('error', 'Cette adresse email n\'existe pas.');
                return $this->redirectToRoute('app_login');
            }

            $loginLinkDetails = $loginLinkHandler->createLoginLink($user);

            $notification = new CustomLoginLinkNotification(
                $loginLinkDetails,
                'Link to Connect',
            );

            $recipient = new Recipient($user->getEmail());

            $notifier->send($notification, $recipient);

            $this->addFlash('success', 'Lien magique envoyé, vous pouvez fermer la page et ouvrir le lien dans votre mail.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/login.html.twig');
    }

    #[Route('/login_check', name: 'app_login_check')]
    public function check(Request $request): never
    {
        throw new \LogicException('This code should never be reached');
    }
}
