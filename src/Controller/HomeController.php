<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function home(
        TrickRepository $trickRepository
    ): Response {
        $tricks = $trickRepository->findBy([], ['createdAt' => 'ASC', 'name' => 'ASC']);
        return $this->render('home/home.html.twig', compact('tricks'));
    }
}
