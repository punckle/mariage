<?php

namespace App\Form;

use App\Entity\Guest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GuestEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Email'
                ]
            ])
            ->add('firstName', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Prénom(s)'
                ]
            ])
            ->add('lastName', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Nom(s) de famille'
                ]
            ])
            ->add('initialNbPeople', IntegerType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Nombre de personnes prévues'
                ]
            ])
            ->add('finalNbPeople', IntegerType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Nombre de personnes au total'
                ]
            ])
            ->add('code', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Code'
                ]
            ])
            ->add('codeActif', CheckboxType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Code actif'
                ]
            ])
            ->add('isInvitedApero', CheckboxType::class, [
                'required' => false,
                'label' => 'Apéro'
            ])->add('isInvitedDinner', CheckboxType::class, [
                'required' => false,
                'label' => 'Dîner'
            ])
            ->add('save', SubmitType::class, ['label' => 'Modifier'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Guest::class,
        ]);
    }
}
