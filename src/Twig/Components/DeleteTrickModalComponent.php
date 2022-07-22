<?php

namespace App\Twig\Components;

use App\Entity\Trick;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('delete_trick_modal')]
class DeleteTrickModalComponent
{
    public ?Trick $trick;
    public bool $isButton = false;
}
