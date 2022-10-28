<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\Header;
use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $entityManager;

     public function __construct(ManagerRegistry $entityManager)
     {
        $this->entityManager = $entityManager->getManager();
     }

     
     
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $products = $this->entityManager->getRepository(Product::class)->findBy(['isBest'=>1]);
        $headers = $this->entityManager->getRepository(Header::class)->findAll();
        return $this->render('home/index.html.twig',['products'=>$products,'headers'=>$headers]
        );
    }
}
