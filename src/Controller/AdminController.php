<?php

namespace App\Controller;

// Importation des différentes classes et interfaces utilisées dans le contrôleur.
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/admin')]
class AdminController extends AbstractController
{  

    //récupère la liste des utilisateurs
    #[Route('/', name: 'app_admin_user')]
    public function getUsers(UserRepository $userRepository): Response
    {
        $listUsers = $userRepository->findAll();
        return $this->render('admin/index.html.twig', [            
            'listUsers' => $listUsers,
        ]);
    }

    //modifie certaines informations de l'utilisateur
    #[Route('/{id}/edit', name: 'app_admin_user_update', methods: ['POST', 'GET'])]
    public function update(Request $request, string $id, UserRepository $userRepository): Response
    {
        // Utilise le UserRepository pour trouver un utilisateur basé sur son 'id'.
        $user = $userRepository->find($id);
    
        // Si l'utilisateur n'a pas été trouvé, une exception est lancée indiquant que l'utilisateur n'existe pas.
        if (!$user) {
            throw $this->createNotFoundException('L\'utilisateur n\'existe pas');
        }
    
        // Crée un formulaire basé sur la classe UserType et le lie à l'entité utilisateur trouvée précédemment.
        $form = $this->createForm(UserType::class, $user);
        // HandleRequest capture les données du formulaire soumises via la requête (si applicable) et les applique à l'entité liée.
        $form->handleRequest($request);
    
        // Vérifie si le formulaire a été soumis et est valide.
        if ($form->isSubmitted() && $form->isValid()) {
            // Si c'est le cas, utilise UserRepository pour mettre à jour l'utilisateur dans la base de données.
            $userRepository->updateUser($user);
    
            // Redirige ensuite l'utilisateur vers la route 'app_admin_user'.
            return $this->redirectToRoute('app_admin_user');
        }
    
        // Si le formulaire n'a pas été soumis ou n'est pas valide, alors la page de mise à jour est rendue avec le formulaire et certaines autres informations.
        return $this->render('admin/update.html.twig', [
            'controller_name' => 'Fiche utilisateur',
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
    #[Route('/delete/{id}', name: 'app_admin_user_delete')]
    public function delete(string $id, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Le fichier n\'existe pas');
        }

        $userRepository->remove($user);

        return $this->redirectToRoute('app_admin_user');
    }

}