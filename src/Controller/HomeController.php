<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'title' => 'SiteFilm - Votre cinéma en ligne',
            'popularMovies' => [
                [
                    'title' => 'Inception',
                    'genre' => 'Science-Fiction',
                    'price' => 3.99,
                    'rating' => 4,
                    'image' => 'https://fr.web.img6.acsta.net/c_310_420/medias/nmedia/18/72/34/14/19476654.jpg'
                ],
                [
                    'title' => 'Le Parrain',
                    'genre' => 'Drame',
                    'price' => 2.99,
                    'rating' => 5,
                    'image' => 'https://fr.web.img6.acsta.net/c_310_420/pictures/22/01/14/08/39/1848157.jpg'
                ],
                [
                    'title' => 'Interstellar',
                    'genre' => 'Science-Fiction',
                    'price' => 4.50,
                    'rating' => 4,
                    'image' => 'https://fr.web.img5.acsta.net/c_310_420/pictures/14/09/24/12/08/158828.jpg'
                ]
            ]
        ]);
    }
}