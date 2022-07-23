<?php

namespace App\Twig\Components;

use App\Entity\Media;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('delete_media_modal')]
class DeleteMediaModalComponent
{
    public ?Media $media = null;
    public bool $isButton = false;
}
