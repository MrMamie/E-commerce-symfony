<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    private $entityManager;

    public function __construct(ManagerRegistry $entityManager)
    {
        $this->entityManager = $entityManager->getManager();
    }
    
    /**
     * @Route("/mon-panier", name="cart")
     */
    public function index(Cart $cart): Response
    {
    
        return $this->render('cart/index.html.twig', ['cart' =>$cart->getFull()] );
    }

    /**
    * attention la variable de récupèration doit porter le même nom que celle de url {id} = $id
    * @Route("/mon-panier/add/{id}", name="add_to_cart")
    */
    public function add(Cart $cart, $id): Response
    {
        $cart->add($id);
        return $this->redirectToRoute('cart');
    }

    /**
    * @Route("/mon-panier/remove", name="remove_my_cart")
    */
    public function remove(Cart $cart): Response
    {
        $cart->remove();
        return $this->redirectToRoute('app_products');
    }

    /**
     * @Route("/mon-panier/delete/{id}", name="delete_to_cart")
     */
    public function delete(Cart $cart,$id): Response
    {
        $cart->delete($id);
        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/mon-panier/decrease/{id}", name="decrease_to_cart")
     */
    public function decrease(Cart $cart,$id): Response
    {
        $cart->decrease($id);
        return $this->redirectToRoute('cart');
    }
}
