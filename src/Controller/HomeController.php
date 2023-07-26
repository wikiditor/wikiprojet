<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, RequestStack $requestStack): Response
    {
        $requestStack->getSession()->set('referer_url', $request->attributes->get('_route'));

        return $this->render('home/index.html.twig', [
            'controller_name' => 'Bienvenue sur la page d\'accueil',
        ]);
    }
}
