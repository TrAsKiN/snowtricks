<?php

namespace App\Form;

use App\Entity\Tag;
use App\Entity\Trick;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\LiveComponent\Form\Type\LiveCollectionType;

class TrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'rows' => '10',
                ],
            ])
            ->add('tags', EntityType::class, [
                'label' => 'Group',
                'class' => Tag::class,
                'choice_label' => 'name',
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('images', LiveCollectionType::class, [
                'label' => 'Add images',
                'entry_type' => ImageType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'mapped' => false,
            ])
            ->add('videos', LiveCollectionType::class, [
                'label' => 'Add videos',
                'entry_type' => VideoType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'mapped' => false,
            ])
        ;
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $trick = $event->getData();
            $form = $event->getForm();
            foreach ($form->get('images')->getData() as $image) {
                $trick->addMedium($image);
            }
            foreach ($form->get('videos')->getData() as $video) {
                $trick->addMedium($video);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}
