<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\FileRepository;
use App\Document\File;
use App\Form\FileType;
use DateTime;
use DateTimeZone;
use Doctrine\ODM\MongoDB\DocumentManager;
use Knp\Snappy\Pdf;


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
    
                $timezone = new DateTimeZone('Europe/Paris');
                $now = new DateTime('now', $timezone);
                $file->setCreationDate($now); // Affecte la date de création actuelle
                $file->setLastUpdate($now); // Affecte la date de modification actuelle
    
                $fileRepository->saveFile($file);
    
                return $this->redirectToRoute('app_file_list');
            }
        }
    
        return $this->render('file/index.html.twig', [
            'controller_name' => 'FileController',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/list', name: 'app_file_list')]
    public function getListFiles(FileRepository $fileRepository): Response
    {
        $listFiles = $fileRepository->findBy([], ['lastUpdate' => 'DESC']); // Trier les fichiers par date de modification décroissante;
        return $this->render('file/list.html.twig', [
            'controller_name' => 'Liste des fichiers',
            'listFiles' => $listFiles,

        ]);
    }
    #[Route('/update/{id}', name: 'app_file_update', methods: ['POST', 'GET'])]
    public function update(Request $request, string $id, FileRepository $fileRepository): Response
    {
        $file = $fileRepository->find($id);
    
        if (!$file) {
            throw $this->createNotFoundException('Le fichier n\'existe pas');
        }
    
        $form = $this->createForm(FileType::class, $file);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            if ($request->query->get('update') === 'true') {
                $timezone = new DateTimeZone('Europe/Paris');
                $now = new DateTime('now', $timezone);
                $file->setLastUpdate($now);
            }
    
            $fileRepository->saveFile($file);
    
            return $this->redirectToRoute('app_file_list');
        }
    
        return $this->render('file/update.html.twig', [
            'controller_name' => 'Liste des fichiers',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'app_file_delete')]
    public function delete(string $id, FileRepository $fileRepository): Response
    {
        $file = $fileRepository->find($id);

        if (!$file) {
            throw $this->createNotFoundException('Le fichier n\'existe pas');
        }

        $fileRepository->remove($file);

        return $this->redirectToRoute('app_file_list');
    }

    #[Route('/export/pdf/{id}', name: 'app_file_export')]
    public function exportContentToPdf(string $id, Pdf $snappy, FileRepository $fileRepository)
    {
        // Récupérer le document File avec l'ID spécifié
        $file = $fileRepository->find($id);
    
        if (!$file) {
            throw $this->createNotFoundException('Le fichier n\'existe pas');
        }
    
        // Créer le PDF à partir du contenu du fichier
        $html = '<html><body><p>' . $file->getContent() . '</p></body></html>';
        $pdfContent = $snappy->getOutputFromHtml($html);
    
        // Créez la réponse
        $response = new Response(
            $pdfContent,
            Response::HTTP_OK,
            ['content-type' => 'application/pdf']
        );
    
        return $this->render('file/index.html.twig', [
            'controller_name' => 'FileController',
            'form' => $form->createView(),
            'file' => $file,
        ]);
        
    }
    
}
