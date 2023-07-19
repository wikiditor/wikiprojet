<?php

namespace App\Controller;

// Importation des différentes classes utilisées dans le contrôleur.
use App\Document\User;
use App\Repository\UserRepository;
use App\Form\RegistrationFormType;
use Doctrine\ODM\MongoDB\DocumentManager as DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
   
    // Route pour l'inscription d'un nouvel utilisateur.
    #[Route('/inscription', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, DocumentManager $dm, UserRepository $userRepository): Response
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
           


            // Redirection vers la page de connexion.
            return $this->redirectToRoute('app_login');
        }

        // Affichage du formulaire d'inscription.
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    
    
    #[Route('admin/user/{id}', name: 'app_admin_user_update', methods: ['POST', 'GET'])]
    public function update(Request $request, string $id, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException('L\'utilisateur n\'existe pas');
        }

        $form = $this->createForm(FileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->saveUser($user);

            return $this->redirectToRoute('app_admin_user');
        }

        return $this->render('registration/update.html.twig', [
            'controller_name' => 'Liste des fichiers',
            'form' => $form->createView(),
        ]);
    }

}  
