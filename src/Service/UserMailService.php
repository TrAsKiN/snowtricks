<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserMailService
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly LoggerInterface $logger
    ) {
    }

    public function sendRegistrationMail(UserInterface $user): void
    {
        try {
            $this->mailer->send(
                (new TemplatedEmail())
                    ->to($user->getEmail())
                    ->subject("Account validation")
                    ->htmlTemplate('emails/account-validation.html.twig')
                    ->context([
                        'token' => $user->getToken(),
                    ])
            );
        } catch (TransportExceptionInterface $e) {
            $this->logger->warning($e->getMessage());
        }
    }

    public function sendResetPasswordMail(UserInterface $user): void
    {
        try {
            $this->mailer->send(
                (new TemplatedEmail())
                    ->to($user->getEmail())
                    ->subject("Request for password reset")
                    ->htmlTemplate('emails/reset-password.html.twig')
                    ->context([
                        'token' => $user->getToken(),
                    ])
            );
        } catch (TransportExceptionInterface $e) {
            $this->logger->warning($e->getMessage());
        }
    }
}
