<?php

namespace App\Controller;

// Import des classes et interfaces nécessaires.
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

// Définition de la classe SecurityController qui hérite de AbstractController.
class SecurityController extends AbstractController
{
    // Définition de la route pour la page de connexion.
    #[Route(path: '/login', name: 'app_login')]
    /**
     * connecte l'utilisateur
     *
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // redirige l'utilisateur déjà connecter vers la page d'accueil 
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        // Récupère l'erreur de connexion si elle existe.
        $error = $authenticationUtils->getLastAuthenticationError();
        // Récupère le dernier nom d'utilisateur saisi par l'utilisateur.
        $lastUsername = $authenticationUtils->getLastUsername();

        // Renvoie la page de connexion avec les données du dernier essai de connexion.
        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    // Définition de la route pour la déconnexion.
    #[Route(path: '/logout', name: 'app_logout')]
    /**
     * déconnecte l'utilisateur
     *
     * @return void
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    // Définition de la route pour la déconnexion.
    #[Route(path: '/redirect', name: 'app_redirect')]
    /**
     * déconnecte l'utilisateur
     *
     * @return redirect
     */
    public function redirectRouter(RequestStack $requestStack):Response
    {
        if(!empty($requestStack->getSession()->get('referer_url'))){
            return $this->redirectToRoute($requestStack->getSession()->get('referer_url'), [
                'searchTerm' => $requestStack->getSession()->get('searchTerm'),
                'language' => $requestStack->getSession()->get('language')
            ]);
        }else{
            return $this->redirectToRoute('app_article');
        }
    }
}
