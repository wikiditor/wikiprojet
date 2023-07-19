<?php

namespace App\Controller;

use PhpParser\Node\Expr\Isset_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpClient\HttpClient;



class ArticleController extends AbstractController
{

    #[Route('/article', name: 'app_article')]
    /**
     * Récupère un article Wikipédia via l'API
     *
     * @return Response
     * @todo changer le nom de l'article dans l'url de l'api (pour ne pas qu'il soit en dur)
     */
    public function getArticle(): Response
    {
        // Crée une instance de HttpClient
        $httpClient = HttpClient::create();

        // Envoie une requête GET à l'API
        $response = $httpClient->request('GET', 'https://fr.wikipedia.org/w/api.php?action=query&titles=france&prop=extracts|images|pageimages|info&pithumbsize=400&inprop=url&redirects=&format=json&origin=*');

        // Récupère le contenu de la réponse et convertit le JSON en une variable php
        $content = json_decode($response->getContent());
        // dump($content);

        // Accède aux valeurs 'extract' et 'title' dans l'objet PHP
        $pages = (array)$content->query->pages;
        $article = array_pop($pages);

        $extract = $article->extract;
        $images = [];
        foreach ($article->images as $image) {

            //$response = $httpClient->request('GET', 'https://commons.wikimedia.org/w/api.php?action=query&pageids=69582727&prop=imageinfo&iiprop=extmetadata|url&format=json');
            //dd($image);
            $response = $httpClient->request('GET', 'https://fr.wikipedia.org/w/api.php?action=query&titles=' . $image->title . '&prop=imageinfo&iiprop=url&format=json');
            $content = json_decode($response->getContent());
            $pages = (array)$content->query->pages;
            $page = array_pop($pages);
            $infos = (array) $page->imageinfo;
            $infos = array_pop($infos);
            
            //dd($infos);
            $images[] = [
                'title' => $image->title,
                'url' => $infos->url
            ];
        }
        
        $title = $article->title;


        // Renvoie une réponse HTTP en affichant le contenu de la réponse de l'API
        return $this->render('article/index.html.twig', [
            'title' => $title,
            'extract' => $extract,
            'images' => $images
        ]);

        
    }


    #[Route('/article/{searchTerm}/{language}', name: 'app_article_search')]
    public function modifyWikipediaApiUrl($searchTerm, $language)
    {
        $searchTerm = ucwords(mb_strtolower($searchTerm));
        $searchTerm = str_replace(' ', '_', $searchTerm);

        $httpClient = HttpClient::create();

        // Envoie une requête GET à l'API
        $response = $httpClient->request('GET', 'https://'. $language .'.wikipedia.org/w/api.php?action=query&titles=' . $searchTerm . 
        '&prop=extracts|images|pageimages|info&pithumbsize=400&inprop=url&redirects=&format=json&origin=*');

        // Récupère le contenu de la réponse et convertit le JSON en une variable php
        $content = json_decode($response->getContent());
        //dd($content);
        // dump($content);

        // Accède aux valeurs 'extract' et 'title' dans l'objet PHP
        $pages = (array)$content->query->pages;
        $article = array_pop($pages);

        if (isset($article->extract)) {
            $extract = $article->extract; // PLANTE SI ARTICLE NON TROUVE
            $images = [];
            foreach ($article->images as $image) {
    
                //$response = $httpClient->request('GET', 'https://commons.wikimedia.org/w/api.php?action=query&pageids=69582727&prop=imageinfo&iiprop=extmetadata|url&format=json');
                //dd($image);
                $response = $httpClient->request('GET', 'https://fr.wikipedia.org/w/api.php?action=query&titles=' . $image->title . '&prop=imageinfo&iiprop=url&format=json');
                $content = json_decode($response->getContent());
                $pages = (array)$content->query->pages;
                $page = array_pop($pages);
                $infos = (array) $page->imageinfo;
                $infos = array_pop($infos);
                
                //dd($infos);
                $images[] = [
                    'title' => $image->title,
                    'url' => $infos->url
                ];
            }
            $title = $article->title;
            return $this->render('article/index.html.twig', [
                'title' => $title,
                'extract' => $extract,
                'images' => $images
            ]);
        } else {
            return $this->render('article/index.html.twig', [
                'title' => 'Nous n\'avons pas compris votre requête.',
                'extract' => 'Cette page n\'existe pas.',
                'images' => []
            ]);
        }
    }
}
