<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function home(
        Request $request,
        TrickRepository $trickRepository
    ): Response {
        $page = $request->query->getInt('page', 1);
        $offset = ($page - 1) * TrickRepository::PAGINATOR_PER_PAGE;
        $paginator = $trickRepository->getTrickPaginator($offset);
        return $this->render('home/home.html.twig', [
            'tricks' => $paginator,
            'page' => $page,
            'max' => TrickRepository::PAGINATOR_PER_PAGE,
        ]);
    }
}
