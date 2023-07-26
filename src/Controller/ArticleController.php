<?php

namespace App\Controller;

use App\Document\File;
use App\Repository\FileRepository;
use PhpParser\Node\Expr\Isset_;
use ReflectionClass;
use Symfony\Component\Security\Core\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use App\Form\FileType;
use DateTime;
use DateTimeZone;
use Symfony\Component\HttpFoundation\JsonResponse;

class ArticleController extends AbstractController
{

    #[Route('/article', name: 'app_article')]
    /**
     * Récupère un article prédéfini de Wikipédia via l'API
     *
     * @return Response
     * @todo changer le nom de l'article dans l'url de l'api (pour ne pas qu'il soit en dur)
     */
    public function getDefaultArticle(Request $request, FileRepository $fileRepository, Security $security): Response
    {
        $twigVars = $this->getArticleHTML('Stanley Kubrick',  'fr');
        $twigVars['form'] = $this->buildAndProcessFileForm($request, $fileRepository, $security);
        return $this->render('article/index.html.twig', $twigVars);
    }

    #[Route('/article/{searchTerm}/{language}', name: 'app_article_search')]
    /**
     * Récupère un article spécifié de Wikipédia via l'API
     *
     * @param [type] $searchTerm
     * @param [type] $language
     * @return void
     */
    public function getArticle($searchTerm, $language, Request $request, FileRepository $fileRepository, Security $security)
    {
        $twigVars = $this->getArticleHTML($searchTerm,  $language);
        $twigVars['form'] = $this->buildAndProcessFileForm($request, $fileRepository, $security);
        return $this->render('article/index.html.twig', $twigVars);
    }

    #[Route('/article/fake', name: 'app_article_fake')]
    /**
     * Récupère un article prédéfini de Wikipédia via l'API
     *
     * @return Response
     * @todo changer le nom de l'article dans l'url de l'api (pour ne pas qu'il soit en dur)
     */
    public function getFakeArticle(Request $request, FileRepository $fileRepository, Security $security): Response
    {
        return $this->render('article/fake.html.twig');
    }

    /**
     * Fonction qui génère et gère le formulaire
     *
     * @return void
     */
    private function buildAndProcessFileForm(Request $request, FileRepository $fileRepository, Security $security)
    {
        $request->get('wikiditor');
        // ICI remettre le code de création du form
        $file = new File();
        $user = $security->getUser();

        // if (!$user) {
        //     // Gérer le cas où l'utilisateur n'est pas authentifié
        //     return $this->redirectToRoute('app_login');
        // }

        // Crée le formulaire de création de fichier
        $form = $this->createForm(FileType::class, $file);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //dump('form submitted');
            // Définir la date de création et de dernière modification actuelle
            $timezone = new DateTimeZone('Europe/Paris');
            $now = new DateTime('now', $timezone);
            $file->setCreationDate($now);
            $file->setLastUpdate($now);

            // On ajoute l'utilisateur connecté qui a créé le document
            $file->setUser($user);

            // Sauvegarder le fichier dans la base de données
            $fileRepository->saveFile($file);
        }

        // retourne le formulaire 
        return $form->createView();
    }

    /**
     * Récupère toutes les datas de l'article spécifié
     *
     * @param string $searchTerm
     * @param [type] $language
     * @return array
     */
    private function getArticleHTML(string $searchTerm, $language): array
    {
        $searchTerm = ucwords(mb_strtolower($searchTerm));
        $searchTerm = str_replace(' ', '_', $searchTerm);
        //dd($searchTerm);
        $httpClient = HttpClient::create();

        // Envoie une requête GET à l'API
        $response = $httpClient->request('GET', 'https://' . $language . '.wikipedia.org/w/api.php?action=query&titles=' . $searchTerm .
            '&prop=extracts|images|pageimages|info|links&pithumbsize=400&inprop=url&redirects=&format=json&origin=*');

        // Récupère le contenu de la réponse et convertit le JSON en une variable php
        $content = json_decode($response->getContent());

        // dump($content);

        // Accède aux valeurs 'extract' et 'title' dans l'objet PHP
        $pages = (array)$content->query->pages;
        $article = array_pop($pages);

        if (isset($article->extract)) {
            $extract = $article->extract; // PLANTE SI ARTICLE NON TROUVE
            $images = [];
            //dd($article->images);
            foreach ($article->images as $image) {

                $response = $httpClient->request('GET', 'https://fr.wikipedia.org/w/api.php?action=query&titles=' . $image->title . '&prop=imageinfo&iiprop=url&format=json');
                $content = json_decode($response->getContent());
                $pages = (array)$content->query->pages;
                $page = array_pop($pages);

                $page = (array) $page;
                if (isset($page['imageinfo'])) {
                    $infos = $page['imageinfo'];
                    $infos = array_pop($infos);

                    //dd($infos);
                    $images[] = [
                        'title' => $image->title,

                        'url' => $infos->url
                    ];
                }
            }

            $links = [];
            foreach ($article->links as $link) {
                $links[] = [
                    'wikipedia_url' => 'https://' . $language . '.wikipedia.org/wiki/' . str_replace(' ', '_', $link->title),
                    'url' => '/article/' . $link->title . '/' . $language,
                    'label' => $link->title,
                ];
            }
            //retourne la page de l'article si elle existe
            $title = $article->title;
            return [
                'title' => $title,
                'extract' => $extract,
                'error' => 'no',
                'images' => $images,
                'links' => $links
            ];
        }
            //retourne a la page 404 error si l'article n'existe pas
        return [
            'title' => 'Nous n\'avons pas compris votre requête.',
            'extract' => 'Cette page n\'existe pas.',
            'error' => '404',
            'images' => [],
            'links' => []
        ];
    }


    /**
     * Route d'api de recherche d'article pour l'autocompletion
     *
     * @param string $searchTerm
     * @return JsonResponse
     */
    #[Route('/API/article/{searchTerm}', name: 'app_api_article')]
    public function getArticleJson(string $searchTerm, ): JsonResponse
    {
        $datas = [
            'searchTerm' => $searchTerm,
            'datas' => []
        ];

        $httpClient = HttpClient::create();
        $response = $httpClient->request('GET', 'https://fr.wikipedia.org/w/api.php?action=query&generator=search&gsrsearch=' . $searchTerm . '&exintro=&format=json');

        // Récupère le contenu de la réponse et convertit le JSON en une variable php
        $content = json_decode($response->getContent());

        if (isset($content->query)) {
            foreach ($content->query->pages as $page) {
                $datas['suggestions'][] = $page->title;
            }
        }

        return new JsonResponse($datas);
    }
}
