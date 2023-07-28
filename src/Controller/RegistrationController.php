<?php

namespace App\Controller;

// Importation des différentes classes utilisées dans le contrôleur.
use App\Document\User;
use App\Repository\UserRepository;
use App\Form\RegistrationFormType;
use Doctrine\ODM\MongoDB\DocumentManager as DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
   
    // Route pour l'inscription d'un nouvel utilisateur.
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, DocumentManager $dm, UserRepository $userRepository, RequestStack $requestStack): Response
    {
        // Création d'une nouvelle instance d'User et de son formulaire associé.
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Si le formulaire est soumis et valide, le mot de passe de l'utilisateur est encodé et persisté dans la base de données.
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            // Ajout de l'utilisateur à la base de données.
            $userRepository->addUser($user);  

            // ajoute le message flash après l'inscription de l'utilisateur
            $this->addFlash(
                'success',
                'Votre compte a été créé avec succès, vous pouvez maintenant vous connecter!'
            );
            
            // Redirection vers la page de connexion.
            return $this->redirectToRoute('app_login');
        }

        // Affichage du formulaire d'inscription.
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    
    
    

}  
