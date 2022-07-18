<?php

namespace App\Components;

use App\Entity\Trick;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('delete_modal')]
class DeleteModalComponent
{
    public Trick $trick;
}
