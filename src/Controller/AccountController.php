<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Entity\PasswordUpdate;
use App\Form\RegistrationType;
use App\Form\PasswordUpdateType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * Permet de gerer le formulaire de connexion
     *
     * @Route("/login", name="account_login")
     * @param AuthenticationUtils $utils
     * @return Response
     */
    public function login(AuthenticationUtils $utils): Response
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render('account/login.html.twig', [
            'hasError' => $error !== null,
            'username' => $username
        ]);
    }

    /**
     * Permet de gerer le formualire d'inscription
     *
     * @Route("/register", name="account_register")
     * @return Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On récupère l'avatar transmise
            $avatar_image = $form->get('avatar')->getData();
            // On génère un nouveau nom de fichier
            $fichier = md5(uniqid()) . '.' . $avatar_image->guessExtension();
            // On copie le fichier dans le dossier uploads
            $avatar_image->move(
                $this->getParameter('avatars_directory'),
                $fichier
            );
            // On stocke le nom de l'image dans la base de données
            $user->setAvatar($fichier);

            // encodage de mot de passe
            $hash = $encoder->encodePassword($user, $user->getHash());
            $user->setHash($hash);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
        }
        return $this->render('account/registration.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de gerer la modfication des profile
     *
     * @Route("/account/profile",name="account_profile")
     * @IsGranted("ROLE_USER")
     * 
     * @param Request $request
     * @return Response
     */
    public function profile(Request $request): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(AccountType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('account_index');
        }

        return $this->render('account/profile.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * modification de mot de passe
     *
     * @Route("/account/update-password", name="account_password")
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @return Response
     */
    public function updatePassword(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $password = new PasswordUpdate();

        $user = $this->getUser();

        $form = $this->createForm(PasswordUpdateType::class, $password);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            if (!password_verify($password->getOldPassword(), $user->getHash())) {
                $form->get('oldPassword')->addError(new FormError("Le mot de passe que vous avez entre n'est pas votre mot de passe actuel"));
            } else {
                $newPassword = $password->getNewPassword();
                $hash = $encoder->encodePassword($user, $newPassword);

                $user->setHash($hash);

                $entityManager->persist($user);
                $entityManager->flush();

                return $this->redirectToRoute('account_index');
            }
            
        }
        return $this->render('account/password.html.twig',[
            'form' => $form->createView()
        ]);
    }
    /**
     * Permet de deconnecter
     * 
     * @Route("/logout", name="account_logout")
     * @return void
     */
    public function logout()
    {
        # code...
    }

    /**
     * Permet d'afficher le profil de l'utilisateur connecté
     *
     * @Route("/account", name="account_index")
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function myAccount() {
        return $this->render('user/index.html.twig', [
            'user' => $this->getUser()
        ]);
    }
}
