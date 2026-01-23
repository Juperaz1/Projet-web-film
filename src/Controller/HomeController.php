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
            'message' => 'Des milliers de films et séries à portée de clic',
            'features' => [
                'Films illimités',
                'Notes et avis',
                'Liste de favoris',
                'Location & Achat'
            ],
            'popularMovies' => [
                [
                    'title' => 'Inception',
                    'genre' => 'Sci-Fi/Thriller',
                    'price' => 4.99,
                    'rating' => 4,
                    'image' => 'https://via.placeholder.com/300x450/1a202c/667eea?text=Inception'
                ],
                [
                    'title' => 'The Dark Knight',
                    'genre' => 'Action',
                    'price' => 3.99,
                    'rating' => 5,
                    'image' => 'https://via.placeholder.com/300x450/1a202c/764ba2?text=Dark+Knight'
                ],
                [
                    'title' => 'Interstellar',
                    'genre' => 'Sci-Fi/Drame',
                    'price' => 5.99,
                    'rating' => 4,
                    'image' => 'https://via.placeholder.com/300x450/1a202c/4299e1?text=Interstellar'
                ],
                [
                    'title' => 'Pulp Fiction',
                    'genre' => 'Crime/Drame',
                    'price' => 2.99,
                    'rating' => 4,
                    'image' => 'https://via.placeholder.com/300x450/1a202c/ed64a6?text=Pulp+Fiction'
                ]
            ]
        ]);
    }
}