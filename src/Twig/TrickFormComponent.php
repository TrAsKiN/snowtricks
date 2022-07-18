<?php

namespace App\Twig;

use App\Entity\Trick;
use App\Form\TrickType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\LiveCollectionTrait;

#[AsLiveComponent('trick_form')]
class TrickFormComponent extends AbstractController
{
    use LiveCollectionTrait;
    use DefaultActionTrait;

    #[LiveProp]
    public ?Trick $trickForm = null;

    public bool $isEditing = false;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(TrickType::class, $this->trickForm);
    }
}
