<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\PasswordResetRequestType;
use App\Form\PasswordResetType;
use App\Repository\UserRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reset-password')]
class ResetPasswordController extends AbstractController
{
    #[Route('/', name: 'app_password_request', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function resetPasswordRequest(
        Request $request,
        UserRepository $userRepository,
        MailerInterface $mailer,
        LoggerInterface $logger
    ): Response {
        $form = $this->createForm(PasswordResetRequestType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userRepository->findOneBy(['username' => $form->get('username')->getData()]);
            if ($user) {
                try {
                    $mailer->send(
                        (new TemplatedEmail())
                            ->to($user->getEmail())
                            ->subject("Request for password reset")
                            ->htmlTemplate('emails/reset-password.html.twig')
                            ->context([
                                'token' => $user->getToken(),
                            ])
                    );
                } catch (TransportExceptionInterface $e) {
                    $logger->warning($e->getMessage());
                }
            }
            $this->addFlash(
                'success',
                "If this account exists, you will receive an email to reset your password."
            );
        }

        return $this->renderForm('reset_password/request.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{token}', name: 'app_password_reset', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function resetPassword(
        Request $request,
        UserRepository $userRepository,
        User $user,
        UserPasswordHasherInterface $userPasswordHasher
    ): Response {
        $form = $this->createForm(PasswordResetType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->upgradePassword($user, $userPasswordHasher->hashPassword(
                $user,
                $form->get('plainPassword')->getData()
            ));
            $user->setToken(null);
            $userRepository->add($user, true);
            $this->addFlash('success', "Your password has been reset!");
        }

        return $this->renderForm('reset_password/request.html.twig', [
            'form' => $form,
        ]);
    }
}
