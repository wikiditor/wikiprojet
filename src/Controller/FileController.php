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

    #[Route('/export/pdf', name: 'export_pdf')]
    public function exportPdf(Pdf $snappy, Request $request, FileRepository $fileRepository): Response
    {
        $file = new File();
        $form = $this->createForm(FileType::class, $file);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fileRepository->saveFile($file);

            // Récupérez les données du formulaire
            $data = $form->getData();

            // Convertissez les données en HTML
            $html = $this->renderView('pdf_template.html.twig', [
                'data' => $data,
            ]);

            // Générez le PDF à partir du HTML
            $pdf = $snappy->getOutputFromHtml($html);

            // Créez une réponse avec le PDF
            return new Response(
                $pdf,
                200,
                [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="document.pdf"'
                ]
            );
        }

        // Retournez le formulaire s'il n'est pas valide
        // return $this->render('file/pdf_file.html.twig', [
        //     'form' => $form->createView(),
        // ]);
    }

}
