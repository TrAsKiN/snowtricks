<?php

namespace App\Form;

use App\Repository\UserRepository;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PasswordResetRequestType extends AbstractType
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly LoggerInterface $logger
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class)
            ->add('request', SubmitType::class)
        ;
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();
            $user = $this->userRepository->findOneBy(['username' => $data['username']]);
            if (!$user) {
                return;
            }
            try {
                $token = bin2hex(random_bytes(16));
            } catch (Exception $e) {
                $this->logger->warning($e->getMessage());
            }
            $user->setToken($token);
            $this->userRepository->add($user, true);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
