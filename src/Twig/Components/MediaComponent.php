<?php

namespace App\Twig\Components;

use Doctrine\Common\Collections\Collection;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('media')]
class MediaComponent
{
    public ?Collection $media = null;
}
