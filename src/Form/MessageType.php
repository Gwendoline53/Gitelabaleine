<?php

namespace App\Form;

use App\Entity\Message;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fromEmail', EmailType::class, [
                'label' => false,
            ])
            ->add('phoneNumber', TelType::class, [
                'label' => false,
            ])
            ->add('subject', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    'Questions' => 'Questions',
                    'Autre' => 'Autre',
                ],
                'placeholder' => 'Choisissez un sujet...',
            ])
            ->add('body', TextareaType::class, [
                'label' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }
}