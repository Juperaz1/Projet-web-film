<?php
// src/Controller/MovieController.php

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    #[Route('/films', name: 'app_movies')]
    public function index(Request $request, Connection $connection): Response
    {
        try
        {
            $filmsData = $connection->fetchAllAssociative('SELECT * FROM FILM ORDER BY titre');
            if(empty($filmsData))
            {
                return $this->render('movie/index.html.twig', [
                    'films' => [],
                    'title' => 'Notre Bibliothèque de Films',
                    'totalFilms' => 0,
                    'genres' => [],
                    'searchTerm' => '',
                    'selectedGenre' => '',
                    'message' => 'La base de données est vide. Veuillez ajouter des films.'
                ]);
            }
            $films = [];
            foreach($filmsData as $data)
            {
                $films[] = [
                    'id' => $data['id_film'] ?? 0,
                    'titre' => $data['titre'] ?? 'Titre inconnu',
                    'annee' => $data['annee'] ?? 2000,
                    'duree' => $this->formatDuration($data['duree'] ?? 90),
                    'synopsis' => $data['synopsis'] ?? 'Synopsis non disponible',
                    'genre' => $data['genre'] ?? 'Non spécifié',
                    'prix_location_par_default' => (float) ($data['prix_location_par_default'] ?? 0),
                    'chemin_affiche' => $this->getImageUrl($data['chemin_affiche'] ?? null, $data['titre'] ?? ''),
                    'rating' => $this->generateRandomRating($data['id_film'] ?? 0),
                    'est_nouveau' => $this->isRecent($data['annee'] ?? 2000),
                    'est_populaire' => (float) ($data['prix_location_par_default'] ?? 0) > 3.50
                ];
            }
            $genres = $this->getUniqueGenres($films);            
            $searchTerm = $request->query->get('search', '');
            $selectedGenre = $request->query->get('genre', '');
            if($searchTerm || $selectedGenre)
            {
                $films = array_filter($films, function($film) use ($searchTerm, $selectedGenre)
                {
                    $match = true;
                    if($searchTerm)
                    {
                        $match = $match && (stripos($film['titre'], $searchTerm) !== false || stripos($film['synopsis'], $searchTerm) !== false);
                    }
                    if($selectedGenre)
                    {
                        $match = $match && ($film['genre'] === $selectedGenre);
                    }
                    return $match;
                });
                $films = array_values($films);
            }
            
            return $this->render('movie/index.html.twig', [
                'films' => $films,
                'title' => 'Notre Bibliothèque de Films',
                'totalFilms' => count($films),
                'genres' => $genres,
                'searchTerm' => $searchTerm,
                'selectedGenre' => $selectedGenre,
                'message' => null
            ]);    
        }
        catch(\Exception $e)
        {
            return $this->render('movie/index.html.twig', [
                'films' => [],
                'title' => 'Notre Bibliothèque de Films',
                'totalFilms' => 0,
                'genres' => [],
                'searchTerm' => '',
                'selectedGenre' => '',
                'message' => 'Erreur de connexion à la base de données: ' . $e->getMessage()
            ]);
        }
    }
    
    private function formatDuration(int $minutes): string
    {
        if($minutes >= 60)
        {
            $hours = floor($minutes / 60);
            $mins = $minutes % 60;
            return sprintf('%dh%02d', $hours, $mins);
        }
        return sprintf('%d min', $minutes);
    }
    
    private function getImageUrl(?string $url, string $title): string
    {
        if(!empty($url) && str_starts_with($url, 'http'))
        {
            return $url;
        }
        $defaultColor = substr(md5($title), 0, 6);
        return sprintf('https://via.placeholder.com/300x450/%s/ffffff?text=%s', $defaultColor, urlencode(substr($title, 0, 20))
        );
    }
    
    private function generateRandomRating(int $id): float
    {
        mt_srand($id);
        return round(rand(30, 50) / 10, 1);
    }
    
    private function isRecent(int $year): bool
    {
        return (date('Y') - $year) <= 3;
    }
    
    private function getUniqueGenres(array $films): array
    {
        $genres = [];
        foreach ($films as $film)
        {
            if(!in_array($film['genre'], $genres))
            {
                $genres[] = $film['genre'];
            }
        }
        sort($genres);
        return $genres;
    }
}