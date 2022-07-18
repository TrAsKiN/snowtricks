<?php

namespace App\Form;

use App\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageType extends AbstractType
{
    public function __construct(
        private readonly string $uploadPath
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('image', FileType::class, [
                'mapped' => false,
                'attr' => [
                    'data-live-ignore' => true,
                ]
            ])
        ;
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            if ($image = $form->get('image')->getData()) {
                $filename = bin2hex(random_bytes(6)) . '.' . $image->guessExtension();
                try {
                    $image->move($this->uploadPath, $filename);
                } catch (FileException $e) {
                    // unable to move file
                }
                $event->getData()->setFile($filename);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
        ]);
    }
}
