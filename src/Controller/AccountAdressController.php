<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Address;
use App\Form\AddressType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountAdressController extends AbstractController
{

    private $entityManager;

    public function __construct(ManagerRegistry $entityManager)
    {
        $this->entityManager = $entityManager->getManager();

    }

    /**
     * @Route("/compte/adresse", name="app_account_address")
     */
    public function index(): Response
    {
        return $this->render('account/address.html.twig');
    }


    /**
     * @Route("/compte/ajouter-une-adresse", name="account_address_add")
     */
    public function add(Cart $cart, Request $request): Response
    {


        $address = new Address;
        $form = $this->createForm(AddressType::class,$address);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $address->setUser($this->getUser());
            $this->entityManager->persist($address);
            $this->entityManager->flush();
            if ($cart->get()) {
                return $this->redirectToRoute('order');
            }
            return $this->redirectToRoute('app_account_address');
        }

        return $this->render('account/address_form.html.twig',["form"=>$form->createView()]);
    }


    /** 
     * @Route("/compte/modifier-adresse/{id}", name="account_address_edit")
     */
    public function edit(Request $request, $id): Response
    {
        $address = $this->entityManager->getRepository(Address::class)->findOneBy(['id'=>$id]);
  

        if (!$address || $address->getUser() != $this->getUser()) {
            return $this->redirectToRoute('app_account_address');
        }

        $form = $this->createForm(AddressType::class,$address);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->entityManager->flush();
            return $this->redirectToRoute('app_account_address');
        }

        return $this->render('account/address_form.html.twig',["form"=>$form->createView()]);
    }

    /** 
     * @Route("/compte/supprimer-adresse/{id}", name="account_address_delete")
     */
    public function delete($id): Response
    {
        $address = $this->entityManager->getRepository(Address::class)->findOneBy(['id'=>$id]);
  

        if ($address && $address->getUser() == $this->getUser()) {
            $this->entityManager->remove($address);
            $this->entityManager->flush();

        }
        return $this->redirectToRoute('app_account_address');


    }
}
