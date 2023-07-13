<?php

namespace App\Controller;

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
    public function getArticle() : Response            
    {
        // Crée une instance de HttpClient
        $httpClient = HttpClient::create();

        // Envoie une requête GET à l'API
        $response = $httpClient->request('GET', 'https://fr.wikipedia.org/w/api.php?action=query&titles=france&prop=extracts|pageimages|info&pithumbsize=400&inprop=url&redirects=&format=json&origin=*');

        // Récupère le contenu de la réponse et convertit le JSON en une variable php
        $content = json_decode($response->getContent());
    
        // dump($content);

         // Accède aux valeurs 'extract' et 'title' dans l'objet PHP
        $article = $content->query->pages->{'1095'};
        $extract = $article->extract;
        $title = $article->title;
       

        // Renvoie une réponse HTTP en affichant le contenu de la réponse de l'API
        return $this->render('article/index.html.twig', [
            'title' => $title,            
            'extract' => $extract,
        ]);
    }
}

