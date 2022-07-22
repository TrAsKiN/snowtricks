<?php

namespace App\Form;

use App\Entity\Video;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VideoType extends MediaType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('url', TextType::class, [
                'mapped' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'URL',
                ],
            ])
        ;
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $video = $event->getData();
            $form = $event->getForm();
            $url = parse_url($form->get('url')->getData());
            if (isset($url['query'])) {
                foreach (explode('&', $url['query']) as $query) {
                    preg_match('#^v=(.+)#', $query, $result);
                    if ($result) {
                        $video->setYoutube($result[1]);
                    }
                }
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Video::class,
        ]);
    }
}
