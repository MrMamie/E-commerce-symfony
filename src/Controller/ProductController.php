<?php

namespace App\Controller;

use App\Classe\Search;
use App\Entity\Product;
use App\Form\SearchType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    private $entityManager;

        
    /**
     * __construct
     *permet d'utiliser doctrine
     * @param  mixed $entityManager
     * @return void
     */
    public function __construct(ManagerRegistry $entityManager)
    {
        $this->entityManager = $entityManager->getManager();
    }

    /**
     * @Route("/nos-produits", name="app_products")
     */
    public function index(Request $request): Response
    {
       
        $search = new Search; //instancier la class search
        $form = $this->createForm(SearchType::class,$search); //crèe le formulaire en le liant à l'objet search
        $form->handleRequest($request); //écoute du formulaire
        if ($form->isSubmitted() && $form->isValid()){ //si formulaire est valide et submit
            //barre de recherche on crée la méthode findWithSearch dans le repo product
            $products = $this->entityManager->getRepository(Product::class)->findWithSearch($search);
        }else{
            $products = $this->entityManager->getRepository(Product::class)->findAll(); //récupère les produits de la base de données
        };
        //createView  crée et envoi la vu du formulaire dans twig avec la variable form
        return $this->render('product/index.html.twig',['products'=> $products, 'form'=>$form->createView()]);
    }
    /**
     * {slug} permet de récupèrer ce qui est tapé dans l'url
     * @Route("/produit/{slug}", name="app_product")
     */
    public function show($slug): Response
    {
        //requête select par rapport au slug
        $product = $this->entityManager->getRepository(Product::class)->findOneBy(['slug'=>$slug]);
        $products = $this->entityManager->getRepository(Product::class)->findBy(['isBest'=>1]);

        if (!$product){ //si le produit existe pas ou mal écrit redirection page produit
            return $this->redirectToRoute('app_products');
        }
        return $this->render('product/show.html.twig',['product'=> $product,'products'=>$products]);
    }
}
