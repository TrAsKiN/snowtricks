<?php

namespace App\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('toast')]
class ToastComponent
{
    public string $message;
    public string $type = 'success';
}
