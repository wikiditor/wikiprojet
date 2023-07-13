<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/file')]
class FileController extends AbstractController
{
    #[Route('/', name: 'app_file')]
    /**
     * affiche le wikiditor
     *
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('file/index.html.twig', [
            'controller_name' => 'FileController',
        ]);
    }    
 

    #[Route('/list', name: 'app_file_list')]
    public function getListFiles(): Response
    {
        return $this->render('file/list.html.twig', [
            'controller_name' => 'Liste des fichiers',
        ]);
    }

    
   
}