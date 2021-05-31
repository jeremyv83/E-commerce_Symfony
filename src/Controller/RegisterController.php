<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function index(Request $request, UserPasswordEncoderInterface $encode, EntityManagerInterface $em): Response
    {
        /**
         * 1. Créer une instance de User
         * 2. Créer un form
         * 3. Récupérer les résultats reçu depuis le form (avec Request)
         * 4. Si valid et soumis alors fait coucou dd('')
         */

        // 1
        $user = new User();

        // 2 
        $form = $this->createForm(RegisterType::class, $user);

        // 3 
        $form->handleRequest($request);

        // 4 

        if($form->isSubmitted() && $form->isValid()){
            // Je récupère bien mes data 
            // dd($form->getData());

            // Ici, en injection de dépendance, j'utilise UserPasswordEncoderInterface $encode
            // pour encoder le mot de passe reçu
            $pwd = $encode->encodePassword($user, $user->getPassword());
            $user->setPassword($pwd);

            // J'ai besoin de mon EntityManager
            // J'utilise l'injection de dépendance (EntityManagerInterface $em)
            // Sinon
            // $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('app_login');
        }
        return $this->render('register/index.html.twig', [
            'formulaire' => $form->createView()
        ]);
    }
}
