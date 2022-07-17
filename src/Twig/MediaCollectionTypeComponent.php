<?php

namespace App\Twig;

use App\Entity\Media;
use App\Form\MediaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\LiveCollectionTrait;

#[AsLiveComponent('media_collection_type')]
class MediaCollectionTypeComponent extends AbstractController
{
    use LiveCollectionTrait;
    use DefaultActionTrait;

    #[LiveProp]
    public Media $media;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(MediaType::class, $this->media);
    }
}
