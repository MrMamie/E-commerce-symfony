<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    private $entityManager;

    public function __construct(ManagerRegistry $entityManager)
    {
        $this->entityManager = $entityManager->getManager();
    }

    /**
     * @Route("/inscription", name="app_register")
     */
    public function index(Request $request, UserPasswordHasherInterface $passHash): Response
    {
        $notification=null;
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $search_email = $this->entityManager->getRepository(User::class)->findOneBy(["email" => $user->getEmail()]);

            if (!$search_email) {
                $password = $passHash->hashPassword($user, $user->getPassword());
                $user->setPassword($password);
                $this->entityManager->persist($user);
                $this->entityManager->flush();
                 $mail = new Mail();
                 $content= 'Bonjour ' . $user->getFirstname(). '<br><br> Bienvenue <br><br>'.  'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Fugit consequuntur esse, neque odio doloribus omnis. Maxime, qui minus error porro voluptatibus architecto totam. Ad eos doloribus corporis! Illo, recusandae excepturi.
                 Itaque velit quae ipsam, fuga minima, id laborum amet molestias reprehenderit eius, est provident delectus culpa similique nostrum obcaecati repellat dolorum explicabo eum dicta iusto dolore nisi! Pariatur, eum porro.';
                 $mail->send($user->getEmail(), $user->getFirstname(), 'Bienvenue sur ART2PIX',$content);
                $notification = "Votre inscription s'est correctement déroulée. Vous pouvez dés à présent vous connecter à votre compte.";
            } else {
                $notification = "L'email que vous avez renseigné existe déja";
            }
        }

        return $this->render('register/index.html.twig', ["form" => $form->createView(),
    "notification"=> $notification
    ]);
    }
}
