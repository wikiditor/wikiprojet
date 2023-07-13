<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/file')]
class FileController extends AbstractController
{
    #[Route('/', name: 'app_file')]
    public function index(): Response
    {
        return $this->render('file/index.html.twig', [
            'controller_name' => 'FileController',
        ]);
    }

    // #[Route('/file/list', name: 'app_file_list')]
    // public function getListFiles(): Response
    // {
    //     return $this->render('file/index.html.twig', [
    //         'controller_name' => 'FileController',
    //     ]);
    // }

}
