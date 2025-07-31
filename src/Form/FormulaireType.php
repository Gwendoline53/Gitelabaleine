<?php

// src/Form/ContactType.php

namespace App\Form;

use App\Entity\Message;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormulaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fromEmail', EmailType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entrer votre adresse mail...',
                    'class' => 'input-group',
                ]
            ])
            ->add('phoneNumber', TelType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Entrer votre numéro de téléphone...',
                    'class' => 'input-group',
                ]
            ])
            ->add('subject', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    'Question' => 'Questions',
                    'Réservation' => 'Réservation',
                    'Autre' => 'Autre',
                ],
                'attr' => [
                    'class' => 'input-group',
                ]
            ])
            ->add('body', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entrez votre demande...',
                    'rows' => 8,
                    'class' => 'textarea-group',
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }
}