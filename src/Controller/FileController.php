<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\FileRepository;
use App\Document\File;
use App\Form\FileType;

#[Route('/file')]
class FileController extends AbstractController
{

    #[Route('/', name: 'app_file')]
    public function index(Request $request, FileRepository $fileRepository)
    {
        $file = new File();
        $form = $this->createForm(FileType::class, $file);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                // Récupérer le contenu du formulaire
                $content = $file->getContent();
    
                // Enregistrer le contenu dans l'objet File
                $file->setContent($content);
    
                $fileRepository->saveFile($file);
    
                //return $this->redirectToRoute('/');
            }
        }
    
        return $this->render('file/index.html.twig', [
            'controller_name' => 'FileController',
            'form' => $form->createView(),
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
