<?php

namespace App\Classe;

use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart{

    private $session;
    private $entityManager;

    public function __construct(SessionInterface $session,ManagerRegistry $entityManager)
    {
        $this->session = $session;
        $this->entityManager =$entityManager;
    }

    public function add($id){
        $cart = $this->session->get('cart',[]); //permet de voir les valeur rentré dans carte
        if(!empty($cart[$id])){ 
            $cart[$id]++; // à l'index : num id on rajoute 1
        }else {
            $cart[$id] = 1;
        }
        $this->session->set('cart',$cart);

    }
        
    /**
     * permet de voir dans la session appelé cart
     *
     * @return void
     */
    public function get(){
        return $this->session->get('cart');
    }
    
        
    /**
     * permet de suprimer la session appelé cart 
     *
     * @return void
     */
    public function remove(){
        return $this->session->remove('cart');
    }

     /**
     * permet de suprimer la session appelé cart 
     *
     * @return void
     */
    public function delete($id){
        $cart = $this->session->get('cart',[]);
        unset($cart[$id]);
        return $this->session->set('cart',$cart);
    }

    public function decrease($id){
        $cart = $this->session->get('cart',[]);
        if($cart[$id] > 1){
            $cart[$id]--;
        }else{
            unset($cart[$id]);
        }
        
        return $this->session->set('cart',$cart);
    }

    public function getFull(){
        $cartComplete = [];
        
        if($this->get()){
           foreach((array)$this->get() as $id => $quantity){ 
            $produt_object = $this->entityManager->getRepository(Product::class)->findOneBy(['id'=>$id]);
            if(!$produt_object){
                $this->delete($id);
                continue;
            }
                $cartComplete[] = [
                'product' => $produt_object,
                'quantity'=> $quantity
                ];

            } 
        }
        return $cartComplete;
    }
}
