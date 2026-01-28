<?php

namespace App\Controller;
use App\Entity\Film;
use App\Entity\Favoris;
use App\Repository\FilmRepository;
use App\Repository\FavorisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    #[Route('/films', name: 'app_movies')]
    public function index(Request $request, FilmRepository $filmRepository, FavorisRepository $favorisRepository): Response
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
                    'message' => 'La base de données est vide. Veuillez ajouter des films.',
                    'favoriteCount' => 0
                ]);
            }
            $user = $this->getUser();
            $formattedFilms = [];
            foreach ($films as $film) {
                $isFavorite = false;
                if ($user) {
                    try {
                        $isFavorite = $favorisRepository->isFilmInFavoris($user, $film);
                    } catch (\Exception $e) {
                        $isFavorite = false;
                    }
                }
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
                    'est_populaire' => $film->isPopular(),
                    'is_favorite' => $isFavorite
                ];
            }
            $allGenres = $filmRepository->findAllGenres();
            $searchTerm = $request->query->get('search', '');
            $selectedGenre = $request->query->get('genre', '');
            if ($searchTerm || $selectedGenre) {
                $formattedFilms = array_filter($formattedFilms, function($film) use ($searchTerm, $selectedGenre) {
                    $match = true;

                    if ($searchTerm) {
                        $match = $match && (stripos($film['titre'], $searchTerm) !== false ||
                                           stripos($film['synopsis'], $searchTerm) !== false);
                    }

                    if ($selectedGenre) {
                        $hasGenre = false;
                        foreach ($film['genres_array'] as $genre) {
                            if ($genre->getLibelleGenre() === $selectedGenre) {
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
            $favoriteCount = 0;
            if ($user) {
                $favoriteCount = $favorisRepository->countUserFavorites($user);
            }

            return $this->render('movie/index.html.twig', [
                'films' => $formattedFilms,
                'title' => 'Notre Bibliothèque de Films',
                'totalFilms' => count($formattedFilms),
                'genres' => $allGenres,
                'searchTerm' => $searchTerm,
                'selectedGenre' => $selectedGenre,
                'message' => null,
                'favoriteCount' => $favoriteCount
            ]);

        } catch (\Exception $e) {
            return $this->render('movie/index.html.twig', [
                'films' => [],
                'title' => 'Notre Bibliothèque de Films',
                'totalFilms' => 0,
                'genres' => [],
                'searchTerm' => '',
                'selectedGenre' => '',
                'message' => 'Erreur: ' . $e->getMessage(),
                'favoriteCount' => 0
            ]);
        }
    }

    #[Route('/films/favoris/toggle/{id}', name: 'app_movies_toggle_favorite', methods: ['POST'])]
    public function toggleFavorite(Film $film, FavorisRepository $favorisRepository, EntityManagerInterface $em): JsonResponse
    {
        error_log("=== TOGGLE FAVORITE CALLED ===");

        if (!$this->getUser()) {
            error_log("ERROR: No user authenticated");
            return $this->json([
                'success' => false,
                'error' => 'Utilisateur non authentifié',
                'action' => 'error'
            ], 401);
        }

        $user = $this->getUser();
        error_log("User: " . $user->getEmail());
        error_log("Film: " . $film->getTitre() . " (ID: " . $film->getIdFilm() . ")");

        try {
            $existingFavori = $em->getRepository(Favoris::class)->findOneBy([
                'utilisateur' => $user,
                'film' => $film
            ]);

            if ($existingFavori) {
                error_log("Removing from favorites");
                $em->remove($existingFavori);
                $em->flush();

                return $this->json([
                    'success' => true,
                    'action' => 'removed',
                    'isFavorite' => false,
                    'message' => 'Film retiré des favoris'
                ]);
            } else {
                error_log("Adding to favorites");
                $favori = new Favoris();
                $favori->setUtilisateur($user);
                $favori->setFilm($film);
                $favori->setDateAjout(new \DateTime());

                $em->persist($favori);
                $em->flush();

                return $this->json([
                    'success' => true,
                    'action' => 'added',
                    'isFavorite' => true,
                    'message' => 'Film ajouté aux favoris'
                ]);
            }

        } catch (\Exception $e) {
            error_log("ERROR in toggleFavorite: " . $e->getMessage());
            error_log($e->getTraceAsString());

            return $this->json([
                'success' => false,
                'error' => 'Erreur serveur: ' . $e->getMessage(),
                'action' => 'error'
            ], 500);
        }
    }

    #[Route('/films/favoris/session/toggle/{id}', name: 'app_movies_toggle_favorite_session', methods: ['POST'])]
    public function toggleFavoriteSession(Film $film, EntityManagerInterface $em): JsonResponse
    {
        error_log("=== SESSION TOGGLE FAVORITE CALLED ===");

        $user = $this->getUser();
        if (!$user) {
            return $this->json([
                'success' => false,
                'error' => 'Non authentifié',
                'action' => 'error'
            ], 401);
        }

        try {
            $existingFavori = $em->getRepository(Favoris::class)->findOneBy([
                'utilisateur' => $user,
                'film' => $film
            ]);

            if ($existingFavori) {
                $em->remove($existingFavori);
                $em->flush();

                return $this->json([
                    'success' => true,
                    'action' => 'removed',
                    'isFavorite' => false
                ]);
            } else {
                $favori = new Favoris();
                $favori->setUtilisateur($user);
                $favori->setFilm($film);
                $favori->setDateAjout(new \DateTime());

                $em->persist($favori);
                $em->flush();

                return $this->json([
                    'success' => true,
                    'action' => 'added',
                    'isFavorite' => true
                ]);
            }

        } catch (\Exception $e) {
            error_log("ERROR in session toggle: " . $e->getMessage());
            return $this->json([
                'success' => false,
                'error' => $e->getMessage(),
                'action' => 'error'
            ], 500);
        }
    }

    #[Route('/api/test-auth', name: 'app_test_auth', methods: ['GET'])]
    public function testAuth(): JsonResponse
    {
        $user = $this->getUser();

        return $this->json([
            'isAuthenticated' => $user !== null,
            'user' => $user ? [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'roles' => $user->getRoles()
            ] : null,
            'session_id' => session_id(),
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }

    #[Route('/api/films/{id}/favorite', name: 'api_film_favorite', methods: ['POST'])]
    public function apiToggleFavorite(Film $film, EntityManagerInterface $em): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->json([
                'success' => false,
                'error' => 'Non authentifié',
                'action' => 'error'
            ], 401);
        }

        try {
            $existingFavori = $em->getRepository(Favoris::class)->findOneBy([
                'utilisateur' => $user,
                'film' => $film
            ]);

            if ($existingFavori) {
                $em->remove($existingFavori);
                $em->flush();

                return $this->json([
                    'success' => true,
                    'action' => 'removed',
                    'isFavorite' => false
                ]);
            } else {
                $favori = new Favoris();
                $favori->setUtilisateur($user);
                $favori->setFilm($film);
                $favori->setDateAjout(new \DateTime());

                $em->persist($favori);
                $em->flush();

                return $this->json([
                    'success' => true,
                    'action' => 'added',
                    'isFavorite' => true
                ]);
            }

        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'error' => $e->getMessage(),
                'action' => 'error'
            ], 500);
        }
    }

    #[Route('/debug/favoris/{id}', name: 'app_debug_favoris')]
    public function debugFavoris(Film $film, FavorisRepository $favorisRepository): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['error' => 'Non authentifié'], 401);
        }

        $isFavorite = $favorisRepository->isFilmInFavoris($user, $film);

        return $this->json([
            'user_id' => $user->getId(),
            'user_email' => $user->getEmail(),
            'film_id' => $film->getIdFilm(),
            'film_title' => $film->getTitre(),
            'is_favorite' => $isFavorite,
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }

    private function generateRandomRating(int $id): float
    {
        mt_srand($id);
        return round(rand(30, 50) / 10, 1);
    }
}
