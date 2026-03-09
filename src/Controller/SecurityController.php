<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


/**
 * Contrôleur gérant l'authentification des utilisateurs.
 * Prend en charge la connexion et la déconnexion.
 * @author s.n
 */


class SecurityController extends AbstractController
{

    /**
         * Affiche le formulaire de connexion et gère les erreurs d'authentification.

         *
         * @param AuthenticationUtils $authenticationUtils Service Symfony fournissant les
         *                                                  informations sur la dernière
         *                                                  tentative d'authentification
         * @return Response La réponse HTTP contenant la vue du formulaire de connexion
         */

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {


        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }


    /**

     *
     * @throws \LogicException Cette exception est levée si la méthode
     *                         est appelée directement sans interception
     *                         du pare-feu Symfony
     */

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}

