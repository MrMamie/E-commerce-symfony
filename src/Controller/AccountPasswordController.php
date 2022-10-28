<?php

namespace App\Controller;

use App\Form\ChangePWDType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AccountPasswordController extends AbstractController
{
    private $entityManager;
    public function __construct(ManagerRegistry $entityManager)
    {
        $this->entityManager = $entityManager->getManager();
    }
    /**
     * @Route("/compte/modification-pwd", name="app_account_password")
     */
    public function index(Request $request, UserPasswordHasherInterface $encoder): Response
    {
        $notification = null;

        $user = $this->getUser();
        $form = $this->createForm(ChangePWDtype::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $old_pwd = $form->get('old_password')->getData();

            if ($encoder->isPasswordValid($user,$old_pwd)){
                $new_pwd = $form->get('new_password')->getData();
                $password = $encoder->hashPassword($user,$new_pwd);
                $user->setPassword($password); // sa fonctionne malgré l'erreur
                $this->entityManager->persist($user); // pour une mise à jour cette ligne n'est pas obligatoire 
                $this->entityManager->flush();
                $notification = "Votre mot de passe à bien été mis à jour";
            }else{
                $notification = "Votre mot de passe actuel n'est pas le bon";
            }
    
        }
        return $this->render('account/password.html.twig',['form' =>$form->createView(),
    'notification' => $notification]);
    }
}
