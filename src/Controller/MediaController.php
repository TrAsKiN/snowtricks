<?php

namespace App\Controller;

use App\Entity\Image;
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
        MediaRepository $mediaRepository,
        string $uploadPath
    ): Response {
        $trick = $media->getTrick();
        if ($this->isCsrfTokenValid(sprintf('delete%s', $media->getId()), $request->request->get('_token'))) {
            if ($media instanceof Image) {
                unlink($uploadPath . '/' . $media->getFile());
            }
            $mediaRepository->remove($media, true);
            $this->addFlash('danger', "The Media has been removed!");
        }

        return $this->redirectToRoute('app_trick_edit', ['id' => $trick->getId()], Response::HTTP_SEE_OTHER);
    }
}
