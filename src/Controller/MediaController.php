<?php

namespace App\Controller;

use App\Entity\Media;
use App\Repository\MediaRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MediaController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/media/{id}', name: 'app_media_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Media $media,
        MediaRepository $mediaRepository
    ): Response {
        if ($this->isCsrfTokenValid(sprintf('delete%s', $media->getId()), $request->request->get('_token'))) {
            $mediaRepository->remove($media, true);
            $this->addFlash('danger', "The Media has been removed!");
        }

        return $this->redirectToRoute('app_home', ['_fragment' => 'tricks'], Response::HTTP_SEE_OTHER);
    }
}
