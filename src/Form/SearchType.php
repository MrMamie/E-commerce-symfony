<?php 

namespace App\Form;

use App\Classe\Search;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{    
        
    /**
     * buildForm
     *permet de créer les champs du formulaire
     * @param  mixed $builder
     * @param  mixed $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options) //créateur de formulaire
    {
        $builder
            ->add('string',TextType::class,['label'=>'Rechercher','required'=>false,
        'attr' => [
            'placeholder' => 'votre recherche ...'
        ]],
        )
        ->add('categories',EntityType::class,[ //input doit reflété la class category 
            'label' =>false,
            'required'=> false,// permet de ne pas cocher toutes les cases
            'class' => Category::class, //class dont l'input doit prendre les noms
            'multiple'=>true, //choix multiple
            'expanded'=>true // vue en checkbox

        ])
        ->add('submit', SubmitType::class,[
            'label'=>'Filtrer',
            'attr'=>[
                'class' => 'btn-block btn-info' //ajouts d'une class dans l'input
            ]
        ]) 
        ;
    }
    
    /**
     * configureOptions
     *
     * permet de lier la formulaire a la classe search
     * @return
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Search::class, // mettre la class a mappé
            'method'=> 'GET', //mettre la méthode souhaité
            'crsf_protection'=> false, //permet de désactiver le crypter URL
        ]);
    }

    public function getBlockPrefix()//renvoi un tableau préfixé par le nom de la class dans url
    {                                 
        return '';  // on retourne vide pour éviter cela
    }
}