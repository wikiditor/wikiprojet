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
        $response = $httpClient->request('GET', 'https://fr.wikipedia.org/w/api.php?action=query&titles=france&prop=extracts|images|pageimages|info|links&pithumbsize=400&inprop=url&redirects=&format=json&origin=*');

        // Récupère le contenu de la réponse et convertit le JSON en une variable php
        $content = json_decode($response->getContent());
        // dump($content);

        // Accède aux valeurs 'extract' et 'title' dans l'objet PHP
        $pages = (array)$content->query->pages;
        $article = array_pop($pages);
        // dd($article);
        $extract = $article->extract;
        $images = [];
        $links = [];
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


        //récuperation des liens dans l'api 
        $links = [];

        foreach ($article->links as $link) {

            //$response = $httpClient->request('GET', 'https://commons.wikimedia.org/w/api.php?action=query&pageids=69582727&prop=imageinfo&iiprop=extmetadata|url&format=json');
            //dd($image);
            $response = $httpClient->request('GET', 'https://fr.wikipedia.org/w/api.php?action=query&titles=' . $link->title . '&prop=linkinfo&iiprop=url&format=json');
            $content = json_decode($response->getContent());
            $pages = (array)$content->query->pages;
            $page = array_pop($pages);
            $infos = (array) $page->linkinfo;
            $infos = array_pop($infos);
            
            //dd($infos);
            $links[] = [
                'title' => $link->title,
                'url' => $infos->url
            ];
        }
         
    

        // Renvoie une réponse HTTP en affichant le contenu de la réponse de l'API
        return $this->render('article/index.html.twig', [
            'title' => $title,
            'extract' => $extract,
            'images' => $images,
            'links' => $links
        ]);

        
    }


    #[Route('/article/{searchTerm}/{language}', name: 'app_article_search')]
    public function modifyWikipediaApiUrl($searchTerm, $language)
    {
        $searchTerm = ucwords(mb_strtolower($searchTerm));
        $searchTerm = str_replace(' ', '_', $searchTerm);
<<<<<<< Updated upstream
        //dd($searchTerm);
=======


>>>>>>> Stashed changes
        $httpClient = HttpClient::create();

        // Envoie une requête GET à l'API
        $response = $httpClient->request('GET', 'https://'. $language .'.wikipedia.org/w/api.php?action=query&titles=' . $searchTerm . 
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
                if(isset($page['imageinfo'])) {
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
            foreach($article->links as $link) {
                $links[] = [
                    'wikipedia_url' => 'https://'. $language .'.wikipedia.org/wiki/' . str_replace(' ', '_', $link->title),
                    'url' => '/article/' . $link->title . '/' . $language,
                    'label' => $link->title,
                ];
            }

            $title = $article->title;
            return $this->render('article/index.html.twig', [
                'title' => $title,
                'extract' => $extract,
                'images' => $images,
                'links'=> $links
            ]);
        } else {
            return $this->render('article/index.html.twig', [
                'title' => 'Nous n\'avons pas compris votre requête.',
                'extract' => 'Cette page n\'existe pas.',
                'images' => [],
                'links' => []
            ]);
        }
    }
}
