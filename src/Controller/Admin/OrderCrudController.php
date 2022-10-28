<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }
/**
 * Fonction qui modifie les actions (éditer,supprimer...)
 */
public function configureActions(Actions $actions): Actions
{
    return $actions
        ->add('index','detail');
}
    // pour savoir les différents fields existant aller sur : https://symfony.com/bundles/EasyAdminBundle/current/fields.html
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            DateTimeField::new('createdAt', 'Passée le'),//le 1er nom attribut de l'entity choisie le 2em para modifie le label
           TextField::new('User.fullName', 'Utilisateur'),
           MoneyField::new('total')->setCurrency('EUR'),
           TextField::new('carrierName','Transporteur'),
           MoneyField::new('carrierPrice','Frais de port')->setCurrency('EUR'),
           BooleanField::new('isPaid', 'Payée'),
           ArrayField::new('orderDetails','Produit achetés')->hideOnIndex()
        ];
    }
    
}
