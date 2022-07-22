<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Form\TrickType;
use App\Repository\TrickRepository;
use DateTimeImmutable;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/tricks')]
class TrickController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/new', name: 'app_trick_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        TrickRepository $trickRepository,
        SluggerInterface $slugger
    ): Response {
        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trick->setAuthor($this->getUser());
            $trick->setSlug($slugger->slug($trick->getName())->lower());
            $trickRepository->add($trick, true);
            $this->addFlash('success', "The Trick has been created!");
            return $this->redirectToRoute('app_home', ['_fragment' => 'tricks'], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('trick/new.html.twig', [
            'trick' => $trick,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', name: 'app_trick_show', methods: ['GET'])]
    public function show(
        Trick $trick
    ): Response {
        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/edit/{id}', name: 'app_trick_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Trick $trick,
        TrickRepository $trickRepository
    ): Response {
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trick->setUpdatedAt(new DateTimeImmutable());
            $trickRepository->add($trick, true);
            $this->addFlash('success', "The Trick has been modified!");
            return $this->redirectToRoute('app_home', ['_fragment' => 'tricks'], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('trick/edit.html.twig', [
            'trick' => $trick,
            'form' => $form,
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/delete/{id}', name: 'app_trick_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Trick $trick,
        TrickRepository $trickRepository
    ): Response {
        if ($this->isCsrfTokenValid(sprintf('delete%s', $trick->getId()), $request->request->get('_token'))) {
            $trickRepository->remove($trick, true);
            $this->addFlash('danger', "The Trick has been removed!");
        }

        return $this->redirectToRoute('app_home', ['_fragment' => 'tricks'], Response::HTTP_SEE_OTHER);
    }
}
