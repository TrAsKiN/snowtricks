<?php

namespace App\Twig\Components;

use App\Entity\Image;
use App\Entity\Trick;
use App\Repository\MediaRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('featured_image')]
class FeaturedImageComponent
{
    public ?Trick $trick = null;
    public bool $thumbnail = false;

    public function __construct(
        private readonly MediaRepository $mediaRepository
    ) {
    }

    public function getImage(): ?Image
    {
        $media = $this->mediaRepository->findBy(['trick' => $this->trick?->getId()]);
        foreach ($media as $medium) {
            if ($medium instanceof Image) {
                return $medium;
            }
        }
        return null;
    }
}
