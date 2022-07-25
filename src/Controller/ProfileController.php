<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Security\ProfileVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profile')]
class ProfileController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/', name: 'app_profile_show', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function show(
        Request $request,
        UserRepository $userRepository,
        UserPasswordHasherInterface $userPasswordHasher
    ): Response {
        /** @var User $user */
        $user = $this->getUser();
        $this->denyAccessUnlessGranted(ProfileVoter::EDIT, $user);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$userPasswordHasher->isPasswordValid($user, $form->get('plainPassword')->getData())) {
                $this->addFlash('danger', "Invalid credentials!");
                return $this->redirectToRoute('app_profile_show', [], Response::HTTP_SEE_OTHER);
            }
            if (!empty($form->get('newPassword')->getData())) {
                $userRepository->upgradePassword($user, $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('newPassword')->getData()
                ));
            }
            $userRepository->add($user, true);
            $this->addFlash('success', "Your profile has been updated!");
            return $this->redirectToRoute('app_profile_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('profile/show.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
}
