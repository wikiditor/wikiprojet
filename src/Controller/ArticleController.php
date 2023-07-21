<?php

namespace App\Controller;

use PhpParser\Node\Expr\Isset_;
use ReflectionClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpClient\HttpClient;



class ArticleController extends AbstractController
{

    #[Route('/article', name: 'app_article')]
    /**
     * Récupère un article prédéfini de Wikipédia via l'API
     *
     * @return Response
     * @todo changer le nom de l'article dans l'url de l'api (pour ne pas qu'il soit en dur)
     */
    public function getDefautArticle(): Response
    {
        $article = $this->getArticleHTML('Orange mécanique',  'fr');
        return $this->render('article/index.html.twig', $article);
    }

    #[Route('/article/{searchTerm}/{language}', name: 'app_article_search')]
    /**
     * Récupère un article spécifié de Wikipédia via l'API
     *
     * @param [type] $searchTerm
     * @param [type] $language
     * @return void
     */
    public function getArticle($searchTerm, $language)
    {

        $article = $this->getArticleHTML($searchTerm,  $language);
        return $this->render('article/index.html.twig', $article);
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

            $title = $article->title;
            return [
                'title' => $title,
                'extract' => $extract,
                'images' => $images,
                'links' => $links
            ];
        }

        return [
            'title' => 'Nous n\'avons pas compris votre requête.',
            'extract' => 'Cette page n\'existe pas.',
            'images' => [],
            'links' => []
        ];
    }
}
