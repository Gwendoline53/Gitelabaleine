<?php
// src/Form/FormulaireType.php

namespace App\Form;

use App\Entity\Message; // adapte selon ton entity
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class FormulaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fromEmail', EmailType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entrer votre adresse mail...',
                    'required' => true,
                ],
            ])
            ->add('phoneNumber', TelType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Entrer votre numéro de téléphone...',
                ],
            ])
            ->add('subject', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    'Question' => 'question',
                    'Réservation' => 'reservation',
                    'Autre' => 'autre',
                ],
                'placeholder' => 'Sélectionnez un sujet',
                'attr' => ['class' => 'styled-select', 'required' => true],
            ])
            ->add('chambre', ChoiceType::class, [
                'label' => 'Choisissez votre chambre',
                'choices' => [
                    'Chambre Bleue' => 'chambre_bleue',
                    'Suite Jardin' => 'suite_jardin',
                    'Chambre Vue Mer' => 'vue_mer',
                    'Chambre Parentale' => 'parentale',
                ],
                'placeholder' => 'Sélectionnez une chambre',
                'required' => false,     // optionnel, car visible uniquement si sujet = reservation
                'mapped' => false,       // si tu ne veux pas lier à l'entité Message (à adapter selon usage)
                'attr' => ['class' => 'styled-select'],
            ])
            ->add('body', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entrez votre demande...',
                    'rows' => 8,
                    'required' => true,
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }
}
