<?php
// src/Controller/DashboardController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]  // Changed name to app_homepage
    #[Route('/dashboard', name: 'app_dashboard')] // Keep this for backward compatibility
    public function index(): Response
    {
        // Sample movie data - in a real app, this would come from a database
        $movies = [
            [
                'id' => 1,
                'title' => 'Dune: Part Two',
                'year' => 2024,
                'genre' => ['Sci-Fi', 'Adventure'],
                'rating' => 8.7,
                'duration' => '2h 46m',
                'description' => 'Paul Atreides unites with Chani and the Fremen while seeking revenge against the conspirators who destroyed his family.',
                'image' => 'https://images.unsplash.com/photo-1534447677768-be436bb09401?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
                'featured' => true
            ],
            [
                'id' => 2,
                'title' => 'The Batman',
                'year' => 2022,
                'genre' => ['Action', 'Crime', 'Drama'],
                'rating' => 7.8,
                'duration' => '2h 56m',
                'description' => 'When a sadistic serial killer begins murdering key political figures in Gotham, Batman is forced to investigate.',
                'image' => 'https://images.unsplash.com/photo-1635805737707-575885ab0820?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
                'featured' => false
            ],
            [
                'id' => 3,
                'title' => 'Oppenheimer',
                'year' => 2023,
                'genre' => ['Biography', 'Drama', 'History'],
                'rating' => 8.3,
                'duration' => '3h',
                'description' => 'The story of American scientist J. Robert Oppenheimer and his role in the development of the atomic bomb.',
                'image' => 'https://images.unsplash.com/photo-1534447677768-be436bb09401?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
                'featured' => false
            ],
            [
                'id' => 4,
                'title' => 'Spider-Man: No Way Home',
                'year' => 2021,
                'genre' => ['Action', 'Adventure', 'Fantasy'],
                'rating' => 8.2,
                'duration' => '2h 28m',
                'description' => 'With Spider-Man\'s identity now revealed, Peter asks Doctor Strange for help, leading to a dangerous threat.',
                'image' => 'https://images.unsplash.com/photo-1635805737707-575885ab0820?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
                'featured' => false
            ],
            [
                'id' => 5,
                'title' => 'Interstellar',
                'year' => 2014,
                'genre' => ['Adventure', 'Drama', 'Sci-Fi'],
                'rating' => 8.6,
                'duration' => '2h 49m',
                'description' => 'A team of explorers travel through a wormhole in space in an attempt to ensure humanity\'s survival.',
                'image' => 'https://images.unsplash.com/photo-1534447677768-be436bb09401?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
                'featured' => false
            ],
            [
                'id' => 6,
                'title' => 'The Dark Knight',
                'year' => 2008,
                'genre' => ['Action', 'Crime', 'Drama'],
                'rating' => 9.0,
                'duration' => '2h 32m',
                'description' => 'When the menace known as the Joker wreaks havoc and chaos on the people of Gotham.',
                'image' => 'https://images.unsplash.com/photo-1635805737707-575885ab0820?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
                'featured' => false
            ],
        ];

        // Featured content
        $featuredContent = [
            'trending' => array_slice($movies, 0, 4),
            'continueWatching' => array_slice($movies, 2, 3),
            'newReleases' => array_slice($movies, 1, 4),
        ];

        return $this->render('dashboard/index.html.twig', [
            'movies' => $movies,
            'featuredContent' => $featuredContent,
            'genres' => ['Action', 'Adventure', 'Comedy', 'Drama', 'Sci-Fi', 'Horror', 'Romance', 'Thriller'],
        ]);
    }
}