<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Classe\Mail;
use App\Entity\Order;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderSuccessController extends AbstractController
{

    
    private $entityManager;
    public function __construct(ManagerRegistry $entityManager)
    {
        $this->entityManager = $entityManager->getManager();
    }

    /**
     * @Route("/commande/merci/{stripeSessionId}", name="order_validate")
     */
    public function index($stripeSessionId, Cart $cart): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneBy(['stripeSessionId'=>$stripeSessionId]);
        if(!$order || $order->getUser() != $this->getUser()){
            return $this->redirectToRoute('home');
        }
      
        if(!$order->getIsPaid()){
            $cart->remove();
            $order->setIsPaid(1);
            $this->entityManager->flush();

            $mail = new Mail();
            $content= 'Bonjour ' . $order->getUser()->getFirstname(). '<br><br> Bienvenue <br><br>'.  'Merci pour votre commande,  amet consectetur, adipisicing elit. Fugit consequuntur esse, neque odio doloribus omnis. Maxime, qui minus error porro voluptatibus architecto totam. Ad eos doloribus corporis! Illo, recusandae excepturi.
            Itaque velit quae ipsam, fuga minima, id laborum amet molestias reprehenderit eius, est provident delectus culpa similique nostrum obcaecati repellat dolorum explicabo eum dicta iusto dolore nisi! Pariatur, eum porro.';
            $mail->send($order->getUser()->getEmail(), $order->getUser()->getFirstname(), 'Votre commande est bien validée',$content);

        }

        return $this->render('order_success/index.html.twig', [
            'order' => $order,
        ]);
    }
}
