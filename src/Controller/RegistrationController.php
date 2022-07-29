<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Service\UserMailService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager,
        UserMailService $mailService,
        LoggerInterface $logger
    ): Response {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            try {
                $user->setToken(bin2hex(random_bytes(16)));
            } catch (Exception $e) {
                $logger->warning($e->getMessage());
            }
            $entityManager->persist($user);
            $entityManager->flush();
            $mailService->sendRegistrationMail($user);
            $this->addFlash('success', "An email has been sent to you to validate your account!");
            return $this->redirectToRoute('app_home');
        }

        return $this->renderForm('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    #[Route('/validation/{token}', name: 'app_validation', methods: [Request::METHOD_GET])]
    public function validation(
        User $user,
        UserRepository $userRepository
    ): Response {
        $user->setStatus(User::VALIDATED);
        $user->setToken(null);
        $userRepository->add($user, true);
        $this->addFlash('success', "Your account is validated!");
        return $this->redirectToRoute('app_home');
    }
}
