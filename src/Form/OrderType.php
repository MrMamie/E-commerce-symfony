<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Carrier;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
       
        $user = $options['user']; //récupération de l'objet user dans le $option
       

        $builder
            ->add('adresses', EntityType::class,[
                'label' => false,
                'class' => Address::class,  // attention créer une methode __toString dans l'entity addresse
                'choices' => $user->getAddresses(),
                'multiple' =>false,
                'expanded' => true
            ])
            ->add('carrier', EntityType::class,[
                'label' => 'Choisissez votre transporteur',
                'class' => Carrier::class, //attention créer une methode __toString dans l'entity carrier
                'multiple' =>false,
                'expanded' => true
            ])
            ->add('submit', SubmitType::class,[
                'label'=> 'Valider ma commande',
                'attr'=>[
                    'class'=> 'btn btn-success btn-block'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'user'=> array() //variable récupéré dans OrderController
        ]);
    }
}
