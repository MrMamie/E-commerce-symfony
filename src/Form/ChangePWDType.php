<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePWDType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class,[
                'disabled'=> true,
                'label' => 'Mon Mail'
            ])
            ->add('firstname',TextType::class,[
                'disabled' => true,
                'label' => 'Mon nom'])

            ->add('lastname',TextType::class,[
                'disabled' => true,
                'label' => 'Mon prénom'])

            ->add('old_password',PasswordType::class,[
                'label' => 'mot de passe actuel',
                'mapped' => false,
                'attr' =>[
                    'placeholder'=> 'veuillez saisir votre mot de passe'
                ]
            ])

            ->add('new_password', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'invalid_message' => 'Les mots de passe ne sont pas indentique.',
                'label' => 'Votre nouveau mot de passe',
                'required' => true,
                'first_options' => ['label' => 'Nouveau mot de passe', 'attr' => ['placeholder' => 'Saisir votre nouveau mot de passe']],
                'second_options' => ['label' => 'Confirmez mot de passe', 'attr' => ['placeholder' => 'Confirmez votre nouveau mot de passe']]
            ])
            ->add('submit', SubmitType::class, ['label' => "Mettre à jour"]);

                    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
