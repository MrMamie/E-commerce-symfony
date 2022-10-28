<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StripeController extends AbstractController
{
    /**
     * @Route("/commande/create-session/{reference}", name="stripe_create_session")
     */
    public function index(ManagerRegistry $entityManager,Cart $cart, $reference)
    {
        $product_for_stripe = [];
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';

        $order = $entityManager->getRepository(Order::class)->findOneBy(['reference'=>$reference]);
        
        if (!$order) {
            new JsonResponse(['error'=>'order']);
        }
        //création des produit dans le tunnel d'achat
        foreach ($order->getOrderDetails()->getValues() as $product) {
            $product_object = $entityManager->getRepository(Product::class)->findOneBy(['name'=>$product->getProduct()]);
            $product_for_stripe[] = [
                'price_data' => [
                    'currency' => 'eur', //devise 
                    'unit_amount' => $product->getPrice(), //prix du produit
                    'product_data' => [
                        'name' => $product->getProduct(), //nom du produit
                        'images' => [$YOUR_DOMAIN . "/uploads/" . $product_object->getIllustration()], //image du produit
                    ],
                ],
                'quantity' => $product->getQuantity(),
            ];
        }
//ajout prix livraison dans le tunnel d'achat
        $product_for_stripe[] = [
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => $order->getCarrierPrice(),
                'product_data' => [
                    'name' => $order->getCarrierName(),
                    'images' => [$YOUR_DOMAIN ],
                ],
            ],
            'quantity' => 1,
        ];
        // mise en place de la clé API stripe 
        Stripe::setApiKey('sk_test_51Llww5HCbDsC7qTK6cdjimAt8qUB4apukPhVeiKe3A7QnHaVfUj1DJNYiHGaVrV05LZ2J5y7S7FyA4oCWC7PPEJS00kRZYSdJ2');


// création du tunnel d'achat
        $checkout_session = Session::create([
            'customer_email'=>$this->getUser()->getEmail(), //ajout du mail dans stripe
            'line_items' => [[
                $product_for_stripe
            ]],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/commande/merci/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN . '/commande/erreur/{CHECKOUT_SESSION_ID}',
        ]);

        $order->setStripeSessionId($checkout_session->id);
        $entityManager->getManager()->flush();

        return $this->redirect($checkout_session->url);
    }
}
