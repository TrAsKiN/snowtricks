<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\PasswordResetRequestType;
use App\Form\PasswordResetType;
use App\Repository\UserRepository;
use Exception;
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
    #[Route('/', name: 'app_password_request')]
    public function request(
        Request $request,
        UserRepository $userRepository,
        MailerInterface $mailer
    ): Response {
        $form = $this->createForm(PasswordResetRequestType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userRepository->findOneBy(['username' => $form->get('username')->getData()]);
            if ($user) {
                try {
                    $token = bin2hex(random_bytes(16));
                    $user->setToken($token);
                    $userRepository->add($user, true);
                    $email = (new TemplatedEmail())
                        ->to($user->getEmail())
                        ->subject("Request for password reset")
                        ->htmlTemplate('emails/reset-password.html.twig')
                        ->context([
                            'token' => $token,
                        ])
                    ;
                    try {
                        $mailer->send($email);
                    } catch (TransportExceptionInterface $e) {
                    }
                } catch (Exception $e) {
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

    #[Route('/{token}', name: 'app_password_reset')]
    public function reset(
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
