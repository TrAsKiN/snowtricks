<?php

namespace App\Form;

use App\Entity\Image;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageType extends MediaType
{
    public function __construct(
        private readonly string $uploadPath,
        private readonly LoggerInterface $logger
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('image', FileType::class, [
                'mapped' => false,
                'by_reference' => false,
                'label' => false,
                'help' => "Your image should not exceed 2 MiB.",
                'attr' => [
                    'data-live-ignore' => true,
                ],
            ])
        ;
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $image = $form->get('image')->getData();
            if ($image && $image->isValid()) {
                $filename = bin2hex(random_bytes(6)) . '.' . $image->guessExtension();
                try {
                    $image->move($this->uploadPath, $filename);
                } catch (FileException $e) {
                    $this->logger->warning($e->getMessage());
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
