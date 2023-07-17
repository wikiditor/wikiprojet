<?php

namespace App\Controller;

// Importe les classes nécessaires à notre contrôleur
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Document\TextDocument;
use App\Form\TextDocumentType;
use Symfony\Component\HttpFoundation\Request;

// Définit la route de base pour ce contrôleur
#[Route('/file')]
class FileController extends AbstractController
{
    // Route pour accéder à l'index du contrôleur de fichier
    #[Route('/', name: 'app_file')]
    /**
     * Affiche le wikiditor
     *
     * @return Response
     */
    public function index(): Response
    {
        // Renvoie le rendu du template avec le nom du contrôleur
        return $this->render('file/index.html.twig', [
            'controller_name' => 'FileController',
        ]);
    }
    
    // Méthode pour créer un nouveau document TextDocument
    public function new(Request $request, DocumentManager $dm)
    {
        // Crée une nouvelle instance de TextDocument
        $textDocument = new TextDocument();
        // Crée le formulaire associé à TextDocument
        $form = $this->createForm(TextDocumentType::class, $textDocument);

        // Traite la requête HTTP avec le formulaire
        $form->handleRequest($request);
        // Vérifie si le formulaire a été soumis et est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistre l'objet TextDocument en base de données
            $dm->persist($textDocument);
            // Enregistre tout ce qui est en attente (ici, le TextDocument)
            $dm->flush();

            // Redirige vers la page d'accueil
            return $this->redirectToRoute('home');
        }

        // Renvoie le rendu du template avec le nom du contrôleur et le formulaire créé
        return $this->render('file/index.html.twig', [
            'controller_name' => 'FileController',
            'form' => $form->createView(),  // Passer la variable 'form' à votre template
        ]);
    }
 

    // Route pour afficher la liste des fichiers
    #[Route('/list', name: 'app_file_list')]
    public function getListFiles(): Response
    {
        // Renvoie le rendu du template avec le nom du contrôleur
        return $this->render('file/list.html.twig', [
            'controller_name' => 'Liste des fichiers',
        ]);
    }

    // Le reste du code de la classe FileController irait ici
}
