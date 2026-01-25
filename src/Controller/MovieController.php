<?php

namespace App\Controller;
use App\Entity\Film;
use App\Repository\FilmRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    #[Route('/films', name: 'app_movies')]
    public function index(Request $request, FilmRepository $filmRepository): Response
    {
        try
        {
            $films = $filmRepository->findAllWithGenres();
            if(empty($films))
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
            $formattedFilms = [];
            foreach($films as $film)
            {
                $formattedFilms[] = [
                    'id' => $film->getIdFilm(),
                    'titre' => $film->getTitre(),
                    'annee' => $film->getAnnee(),
                    'duree' => $film->getFormattedDuration(),
                    'synopsis' => $film->getSynopsis() ?? 'Synopsis non disponible',
                    'genre' => $film->getGenresAsString(),
                    'genres_array' => $film->getGenres()->toArray(),
                    'prix_location_par_default' => (float) $film->getPrixLocationDefault(),
                    'chemin_affiche' => $film->getFullAfficheUrl(),
                    'rating' => $film->getNote() ?? $this->generateRandomRating($film->getIdFilm()),
                    'est_nouveau' => $film->isRecent(),
                    'est_populaire' => $film->isPopular()
                ];
            }
            $allGenres = $filmRepository->findAllGenres();
            $searchTerm = $request->query->get('search', '');
            $selectedGenre = $request->query->get('genre', '');
            if($searchTerm || $selectedGenre)
            {
                $formattedFilms = array_filter($formattedFilms, function($film) use ($searchTerm, $selectedGenre) {
                    $match = true;
                    if($searchTerm)
                    {
                        $match = $match && (stripos($film['titre'], $searchTerm) !== false);
                    }
                    if($selectedGenre)
                    {
                        $hasGenre = false;
                        foreach($film['genres_array'] as $genre)
                        {
                            if($genre->getLibelleGenre() === $selectedGenre)
                            {
                                $hasGenre = true;
                                break;
                            }
                        }
                        $match = $match && $hasGenre;
                    }
                    return $match;
                });
                $formattedFilms = array_values($formattedFilms);
            }
            
            return $this->render('movie/index.html.twig', [
                'films' => $formattedFilms,
                'title' => 'Notre Bibliothèque de Films',
                'totalFilms' => count($formattedFilms),
                'genres' => $allGenres,
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
                'message' => 'Erreur: ' . $e->getMessage()
            ]);
        }
    }
    
    private function generateRandomRating(int $id): float
    {
        mt_srand($id);
        return round(rand(30, 50) / 10, 1);
    }
}