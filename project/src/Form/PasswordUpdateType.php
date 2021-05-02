<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PasswordUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('newPassword', PasswordType::class, [
                'attr' => [
                    'label' => false,
                    'placeholder' => 'Votre nouveau mot de passe'
                ]
            ])
            ->add('confirmPassword', PasswordType::class, [
                'attr' => [
                    'label' => false,
                    'placeholder' => "Confirmez votre nouveau mot de passe"
                ]
            ])
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
