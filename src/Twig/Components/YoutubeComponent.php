<?php

namespace App\Twig\Components;

use App\Entity\Video;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('youtube')]
class YoutubeComponent
{
    public ?Video $video = null;
}
