<?php

namespace App\Form;

use App\Entity\GuestPlusOne;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GuestFormInvitationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Prénom(s)'
                ]
            ])
            ->add('lastName', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Nom(s) de famille'
                ]
            ])
            ->add('kid', CheckboxType::class, [
                'required' => false,
                'label' => 'Enfant'
            ])
            ->add('apero', CheckboxType::class, [
                'required' => false,
                'label' => 'Cocktail'
            ])
            ->add('dinner', CheckboxType::class, [
                'required' => false,
                'label' => 'Dîner'
            ])
            ->add('comment', TextareaType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Commentaire'
                ]
            ])
            ->add('save', SubmitType::class, ['label' => 'Ajouter'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GuestPlusOne::class,
        ]);
    }
}
