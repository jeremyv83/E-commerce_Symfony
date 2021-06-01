<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', null, [
                'label' => 'Prénom',
                'disabled' => true,
            ])
            ->add('lastName', null, [
                'label' => 'Nom',
                'disabled' => true,
            ])
            ->add('email', null, [
                'label' => 'Email',
                'disabled' => true,
            ])
            ->add('old_password', PasswordType::class, [
                'mapped' => false,
                'label' => 'Votre mot de passe',
                'attr' => [
                    'placeholder' => 'Mot de passe actuel'
                ]
            ])
            ->add('new_password', RepeatedType::class, [
                'mapped' => false,
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passes ne sont pas identiques',
                'first_options' => [
                    'label' => 'Nouveau mot de passe',
                    'attr' => [
                        'placeholder' => 'Mot de passe'
                    ]
                ],
                'second_options' => [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Répéter mot de passe'
                    ]
                ],
                'required' => true,
                'options' => [
                    'attr' => [
                        'class' => 'password-field'
                    ]
                ],
                
            ])
            ->add('modif', SubmitType::class, [
                'label' => 'Modifier'
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
