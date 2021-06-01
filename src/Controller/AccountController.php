<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * @Route("/account", name="account")
     */
    public function index(): Response
    {
        return $this->render('account/index.html.twig');
    }

    /**
     * @Route("/account/pwd", name="account_pwd")
     */
    public function pwd(Request $request, UserPasswordEncoderInterface $encode, EntityManagerInterface $em): Response
    {
        $notif = null;
        $error = null;
        /**
         * 1. Recuperer mon user (qui est connecté)
         * 2. Créer le form
         * 3. Récuperer data du form
         * 4. S le formulaire est soumis et valide, alors changer le mot de passe
         */

        // 1 
        $user = $this->getUser();

        // 2
        $form = $this->createForm(ChangePasswordType::class, $user);
        
        //3
        $form->handleRequest($request);

        // 4
        if($form->isSubmitted() && $form->isValid()){
            // Ici, je récupere via get('old_password')->getData() la valeur de l'input dans le formulaire
            $old_pwd = $form->get('old_password')->getData();

            // Ici, je demnde si mon mot de passe est identique avec celui qui y est actuellement
            if($encode->isPasswordValid($user, $old_pwd)){
                // Je récupere le nouveau mot de passe
                $new_pwd = $form->get('new_password')->getData();

                // Je l'encode
                $pwd = $encode->encodePassword($user, $new_pwd);

                $user->setPassword($pwd);

                $em->persist($user);
                $em->flush();
                $notif = "Votre nouveau mot de passe à bien été enregistré";
                $error = false;
            }else{
                $notif = "Le mot de passe actuel est incorrect";
                $error = true;
            }

            
        }

        return $this->render('account/change_pwd.html.twig', [
            'form_mdp' => $form->createView(),
            'notif' => $notif,
            'error' => $error,
        ]);
    }
}
