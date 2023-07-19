<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\User1Type;
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

}