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
use Symfony\Component\Security\Core\Security;

#[Route('/file')]
class FileController extends AbstractController
{
    #[Route('/', name: 'app_file')]
    public function index(Request $request, FileRepository $fileRepository, Security $security): Response
    {
        $file = new File();
        $user = $security->getUser();

        if (!$user) {
            // Gérer le cas où l'utilisateur n'est pas authentifié
            return $this->redirectToRoute('app_login');
        }

        // Crée le formulaire de création de fichier
        $form = $this->createForm(FileType::class, $file);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Définir la date de création et de dernière modification actuelle
            $timezone = new DateTimeZone('Europe/Paris');
            $now = new DateTime('now', $timezone);
            $file->setCreationDate($now);
            $file->setLastUpdate($now);

            // On ajoute l'utilisateur connecté qui a créé le document
            $file->setUser($user);

            // Sauvegarder le fichier dans la base de données
            $fileRepository->saveFile($file);

            return $this->redirectToRoute('app_file_list');
        }

        return $this->render('file/index.html.twig', [
            'controller_name' => 'FileController',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/list', name: 'app_file_list')]
    public function getListFiles(FileRepository $fileRepository, Security $security): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $security->getUser();

        if (!$user) {
            // Gérer le cas où l'utilisateur n'est pas authentifié
            // Par exemple, redirigez-le vers la page de connexion
            return $this->redirectToRoute('app_login');
        }

        // Récupérer la liste des fichiers créés par l'utilisateur connecté
        $listFiles = $fileRepository->findByUserSortedByLastUpdate($user);
                
        return $this->render('file/list.html.twig', [
            'controller_name' => 'Liste des fichiers',
            'listFiles' => $listFiles,
        ]);
    }
    
    #[Route('/update/{id}', name: 'app_file_update', methods: ['POST', 'GET'])]
    public function update(Request $request, string $id, FileRepository $fileRepository, Security $security): Response
    {
        $file = $fileRepository->find($id);

        if (!$file) {
            throw $this->createNotFoundException('Le fichier n\'existe pas');
        }

        $user = $security->getUser();

        if (!$user) {
            // Gérer le cas où l'utilisateur n'est pas authentifié
        }

        if ($user->getId() !== $file->getUser()->getId()) {
            // L'utilisateur actuel n'est pas autorisé à mettre à jour ce fichier
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à modifier ce fichier');
        }

        $form = $this->createForm(FileType::class, $file);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Mettre à jour la date de dernière modification
            $timezone = new DateTimeZone('Europe/Paris');
            $now = new DateTime('now', $timezone);
            $file->setLastUpdate($now);

            // Sauvegarder les modifications dans la base de données
            $fileRepository->saveFile($file);

            return $this->redirectToRoute('app_file_list');
        }

        return $this->render('file/update.html.twig', [
            'controller_name' => 'Liste des fichiers',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'app_file_delete')]
    public function delete(string $id, FileRepository $fileRepository, Security $security): Response
    {
        $file = $fileRepository->find($id);

        if (!$file) {
            throw $this->createNotFoundException('Le fichier n\'existe pas');
        }

        $user = $security->getUser();

        if ($user->getId() !== $file->getUser()->getId()) {
            // L'utilisateur actuel n'est pas autorisé à supprimer ce fichier
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à supprimer ce fichier');
        }

        $fileRepository->remove($file);

        return $this->redirectToRoute('app_file_list');
    }
    
}
