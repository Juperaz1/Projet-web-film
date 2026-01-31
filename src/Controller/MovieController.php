<?php

namespace App\Controller;

use App\Entity\Film;
use App\Entity\Favoris;
use App\Repository\FilmRepository;
use App\Repository\FavorisRepository;
use App\Repository\TarifsDynamiquesRepository;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Log\LoggerInterface;

class MovieController extends AbstractController
{
    #[Route('/films', name: 'app_movies')]
    public function index(
        Request $request, 
        FilmRepository $filmRepository, 
        FavorisRepository $favorisRepository, 
        TarifsDynamiquesRepository $tarifsRepo,
        GenreRepository $genreRepository,
        PaginatorInterface $paginator
    ): Response
    {
        try
        {
            $queryBuilder = $filmRepository->createQueryBuilder('f')->leftJoin('f.genres', 'g')->addSelect('g')->orderBy('f.titre', 'ASC');
            $allGenres = $genreRepository->getAllLibelles();
            $searchTerm = $request->query->get('search', '');
            $selectedGenre = $request->query->get('genre', '');
            
            if($searchTerm)
            {
                $queryBuilder->andWhere('f.titre LIKE :searchTerm OR f.synopsis LIKE :searchTerm')->setParameter('searchTerm', '%' . $searchTerm . '%');
            }
            if($selectedGenre)
            {
                $queryBuilder->andWhere('g.libelleGenre = :genre')->setParameter('genre', $selectedGenre);
            }
            
            $pagination = $paginator->paginate($queryBuilder->getQuery(), $request->query->getInt('page', 1), 12);            
            
            $promotionDuJour = $this->getPromotionDuJour($tarifsRepo);
            
            if($pagination->count() === 0)
            {
                return $this->render('movie/index.html.twig', [
                    'films' => [],
                    'title' => 'Notre Bibliothèque de Films',
                    'totalFilms' => 0,
                    'genres' => $allGenres,
                    'searchTerm' => $searchTerm,
                    'selectedGenre' => $selectedGenre,
                    'message' => 'Aucun film trouvé avec ces critères de recherche.',
                    'favoriteCount' => 0,
                    'pagination' => $pagination,
                    'promotionDuJour' => $promotionDuJour
                ]);
            }
            
            $user = $this->getUser();
            $formattedFilms = [];
            
            foreach($pagination as $film)
            {
                $isFavorite = false;
                if($user)
                {
                    try
                    {
                        $isFavorite = $favorisRepository->isFilmInFavoris($user, $film);
                    }
                    catch(\Exception $e)
                    {
                        $isFavorite = false;
                    }
                }
                
                $prixOriginal = (float) $film->getPrixLocationDefault();
                $prixReduit = $prixOriginal;
                $reductionPourcentage = 0;
                
                if ($promotionDuJour && $promotionDuJour['pourcentage'] > 0) {
                    $reductionPourcentage = $promotionDuJour['pourcentage'];
                    $prixReduit = $prixOriginal - ($prixOriginal * $reductionPourcentage / 100);
                }
                
                $formattedFilms[] = [
                    'id' => $film->getIdFilm(),
                    'titre' => $film->getTitre(),
                    'annee' => $film->getAnnee(),
                    'duree' => $film->getFormattedDuration(),
                    'synopsis' => $film->getSynopsis() ?? 'Synopsis non disponible',
                    'genre' => $film->getGenresAsString(),
                    'genres_array' => $film->getGenres()->toArray(),
                    'prix_location_par_default' => $prixOriginal,
                    'prix_location_reduit' => $prixReduit,
                    'reduction_pourcentage' => $reductionPourcentage,
                    'chemin_affiche' => $film->getFullAfficheUrl(),
                    'rating' => $film->getNote() ?? $this->generateRandomRating($film->getIdFilm()),
                    'est_nouveau' => $film->isRecent(),
                    'est_populaire' => $film->isPopular(),
                    'is_favorite' => $isFavorite
                ];
            }
            
            $favoriteCount = 0;
            if($user)
            {
                $favoriteCount = $favorisRepository->countUserFavorites($user);
            }
            
            return $this->render('movie/index.html.twig', [
                'films' => $formattedFilms,
                'title' => 'Notre Bibliothèque de Films',
                'totalFilms' => $pagination->getTotalItemCount(),
                'genres' => $allGenres,
                'searchTerm' => $searchTerm,
                'selectedGenre' => $selectedGenre,
                'message' => null,
                'favoriteCount' => $favoriteCount,
                'pagination' => $pagination,
                'promotionDuJour' => $promotionDuJour
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
                'message' => 'Erreur: ' . $e->getMessage(),
                'favoriteCount' => 0,
                'pagination' => null,
                'promotionDuJour' => null
            ]);
        }
    }

    #[Route('/films/genre/{genreName}', name: 'app_movies_by_genre')]
    public function byGenre(
        string $genreName,
        Request $request, 
        FilmRepository $filmRepository, 
        FavorisRepository $favorisRepository, 
        TarifsDynamiquesRepository $tarifsRepo,
        GenreRepository $genreRepository,
        PaginatorInterface $paginator
    ): Response
    {
        try
        {
            $queryBuilder = $filmRepository->createQueryBuilder('f')
                ->leftJoin('f.genres', 'g')
                ->addSelect('g')
                ->where('g.libelleGenre = :genreName')
                ->setParameter('genreName', $genreName)
                ->orderBy('f.titre', 'ASC');
            
            $allGenres = $genreRepository->getAllLibelles();
            $searchTerm = $request->query->get('search', '');
            
            if($searchTerm)
            {
                $queryBuilder->andWhere('f.titre LIKE :searchTerm OR f.synopsis LIKE :searchTerm')
                    ->setParameter('searchTerm', '%' . $searchTerm . '%');
            }
            
            $pagination = $paginator->paginate($queryBuilder->getQuery(), $request->query->getInt('page', 1), 12);            
            
            $promotionDuJour = $this->getPromotionDuJour($tarifsRepo);
            
            if($pagination->count() === 0)
            {
                return $this->render('movie/index.html.twig', [
                    'films' => [],
                    'title' => "Films - $genreName",
                    'totalFilms' => 0,
                    'genres' => $allGenres,
                    'searchTerm' => $searchTerm,
                    'selectedGenre' => $genreName,
                    'message' => "Aucun film trouvé dans la catégorie '$genreName'.",
                    'favoriteCount' => 0,
                    'pagination' => $pagination,
                    'promotionDuJour' => $promotionDuJour
                ]);
            }
            
            $user = $this->getUser();
            $formattedFilms = [];
            
            foreach($pagination as $film)
            {
                $isFavorite = false;
                if($user)
                {
                    try
                    {
                        $isFavorite = $favorisRepository->isFilmInFavoris($user, $film);
                    }
                    catch(\Exception $e)
                    {
                        $isFavorite = false;
                    }
                }
                
                $prixOriginal = (float) $film->getPrixLocationDefault();
                $prixReduit = $prixOriginal;
                $reductionPourcentage = 0;
                
                if ($promotionDuJour && $promotionDuJour['pourcentage'] > 0) {
                    $reductionPourcentage = $promotionDuJour['pourcentage'];
                    $prixReduit = $prixOriginal - ($prixOriginal * $reductionPourcentage / 100);
                }
                
                $formattedFilms[] = [
                    'id' => $film->getIdFilm(),
                    'titre' => $film->getTitre(),
                    'annee' => $film->getAnnee(),
                    'duree' => $film->getFormattedDuration(),
                    'synopsis' => $film->getSynopsis() ?? 'Synopsis non disponible',
                    'genre' => $film->getGenresAsString(),
                    'genres_array' => $film->getGenres()->toArray(),
                    'prix_location_par_default' => $prixOriginal,
                    'prix_location_reduit' => $prixReduit,
                    'reduction_pourcentage' => $reductionPourcentage,
                    'chemin_affiche' => $film->getFullAfficheUrl(),
                    'rating' => $film->getNote() ?? $this->generateRandomRating($film->getIdFilm()),
                    'est_nouveau' => $film->isRecent(),
                    'est_populaire' => $film->isPopular(),
                    'is_favorite' => $isFavorite
                ];
            }
            
            $favoriteCount = 0;
            if($user)
            {
                $favoriteCount = $favorisRepository->countUserFavorites($user);
            }
            
            return $this->render('movie/index.html.twig', [
                'films' => $formattedFilms,
                'title' => "Films - $genreName",
                'totalFilms' => $pagination->getTotalItemCount(),
                'genres' => $allGenres,
                'searchTerm' => $searchTerm,
                'selectedGenre' => $genreName,
                'message' => null,
                'favoriteCount' => $favoriteCount,
                'pagination' => $pagination,
                'promotionDuJour' => $promotionDuJour
            ]);
        }
        catch(\Exception $e)
        {
            return $this->render('movie/index.html.twig', [
                'films' => [],
                'title' => "Films - $genreName",
                'totalFilms' => 0,
                'genres' => [],
                'searchTerm' => '',
                'selectedGenre' => $genreName,
                'message' => 'Erreur: ' . $e->getMessage(),
                'favoriteCount' => 0,
                'pagination' => null,
                'promotionDuJour' => null
            ]);
        }
    }

    #[Route('/films/favoris/toggle/{id}', name: 'app_movies_toggle_favorite', methods: ['POST'])]
    public function toggleFavorite(
        int $id,
        FilmRepository $filmRepository,
        FavorisRepository $favorisRepository,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger
    ): JsonResponse
    {
        try {
            $user = $this->getUser();
            if (!$user) {
                $logger->error('User not authenticated when trying to toggle favorite');
                return new JsonResponse([
                    'success' => false,
                    'error' => 'Vous devez être connecté pour ajouter des favoris'
                ], 401);
            }

            $film = $filmRepository->find($id);
            if (!$film) {
                $logger->error('Film not found with id: ' . $id);
                return new JsonResponse([
                    'success' => false,
                    'error' => 'Film non trouvé'
                ], 404);
            }

            $favoris = $favorisRepository->findOneBy([
                'utilisateur' => $user, 
                'film' => $film
            ]);

            $action = '';
            
            if ($favoris) {
                $entityManager->remove($favoris);
                $entityManager->flush();
                $action = 'removed';
            } else {
                $favoris = new Favoris();
                $favoris->setUtilisateur($user); 
                $favoris->setFilm($film);
                $favoris->setDateAjout(new \DateTime());
                
                $entityManager->persist($favoris);
                $entityManager->flush();
                $action = 'added';
            }

            $isFavorite = $favorisRepository->isFilmInFavoris($user, $film);
            
            return new JsonResponse([
                'success' => true,
                'action' => $action,
                'isFavorite' => $isFavorite,
                'message' => $action === 'added' ? 'Film ajouté aux favoris' : 'Film retiré des favoris'
            ]);

        } catch (\Exception $e) {
            $logger->error('Error in toggleFavorite: ' . $e->getMessage());
            return new JsonResponse([
                'success' => false,
                'error' => 'Une erreur est survenue : ' . $e->getMessage()
            ], 500);
        }
    }

    #[Route('/films/favoris/session/toggle/{id}', name: 'app_movies_toggle_favorite_session', methods: ['POST'])]
    public function toggleFavoriteSession(
        int $id,
        FilmRepository $filmRepository,
        Request $request
    ): JsonResponse
    {
        try {
            $film = $filmRepository->find($id);
            if (!$film) {
                return new JsonResponse([
                    'success' => false,
                    'error' => 'Film non trouvé'
                ], 404);
            }

            $session = $request->getSession();
            $favorites = $session->get('favorites', []);

            $action = '';
            
            if (in_array($id, $favorites)) {
                $favorites = array_diff($favorites, [$id]);
                $action = 'removed';
            } else {
                $favorites[] = $id;
                $action = 'added';
            }

            $session->set('favorites', array_values($favorites));

            return new JsonResponse([
                'success' => true,
                'action' => $action,
                'message' => $action === 'added' ? 'Film ajouté aux favoris (session)' : 'Film retiré des favoris (session)',
                'count' => count($favorites)
            ]);

        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'error' => 'Une erreur est survenue : ' . $e->getMessage()
            ], 500);
        }
    }

    #[Route('/api/films/{id}/favorite', name: 'api_film_favorite', methods: ['POST'])]
    public function apiToggleFavorite(
        int $id,
        FilmRepository $filmRepository,
        FavorisRepository $favorisRepository,
        EntityManagerInterface $entityManager,
        Request $request
    ): JsonResponse
    {
        try {
            $user = $this->getUser();
            if (!$user) {
                return new JsonResponse([
                    'success' => false,
                    'error' => 'Non authentifié'
                ], 401);
            }

            $film = $filmRepository->find($id);
            if (!$film) {
                return new JsonResponse([
                    'success' => false,
                    'error' => 'Film non trouvé'
                ], 404);
            }

            $favoris = $favorisRepository->findOneBy([
                'utilisateur' => $user, 
                'film' => $film
            ]);

            $action = '';
            
            if ($favoris) {
                $entityManager->remove($favoris);
                $entityManager->flush();
                $action = 'removed';
                $isFavorite = false;
            } else {
                $favoris = new Favoris();
                $favoris->setUtilisateur($user); 
                $favoris->setFilm($film);
                $favoris->setDateAjout(new \DateTime());
                
                $entityManager->persist($favoris);
                $entityManager->flush();
                $action = 'added';
                $isFavorite = true;
            }

            return new JsonResponse([
                'success' => true,
                'action' => $action,
                'isFavorite' => $isFavorite,
                'message' => $action === 'added' ? 'Film ajouté aux favoris' : 'Film retiré des favoris'
            ]);

        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'error' => 'Une erreur est survenue : ' . $e->getMessage()
            ], 500);
        }
    }

    #[Route('/api/test-auth', name: 'app_test_auth', methods: ['GET'])]
    public function testAuth(): JsonResponse
    {
        $user = $this->getUser();
        
        return new JsonResponse([
            'authenticated' => $user !== null,
            'user' => $user ? [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'roles' => $user->getRoles()
            ] : null
        ]);
    }

    #[Route('/debug/favoris/{id}', name: 'debug_favoris', methods: ['GET'])]
    public function debugFavoris(
        int $id,
        FilmRepository $filmRepository,
        FavorisRepository $favorisRepository,
        EntityManagerInterface $em
    ): JsonResponse
    {
        $user = $this->getUser();
        $film = $filmRepository->find($id);
        
        if (!$user || !$film) {
            return new JsonResponse(['error' => 'User or film not found'], 404);
        }
        
        $dql = "SELECT f FROM App\Entity\Favoris f WHERE f.utilisateur = :user AND f.film = :film";
        $query = $em->createQuery($dql)
            ->setParameter('user', $user)
            ->setParameter('film', $film);
        
        $existing = $query->getResult();
        
        $viaRepository = $favorisRepository->findOneBy(['utilisateur' => $user, 'film' => $film]);
        
        return new JsonResponse([
            'user_id' => $user->getId(),
            'film_id' => $film->getId(),
            'existing_favorites_dql' => count($existing),
            'existing_favorites_repository' => $viaRepository ? 'Yes' : 'No',
            'is_in_favoris' => $favorisRepository->isFilmInFavoris($user, $film)
        ]);
    }

    private function getPromotionDuJour(TarifsDynamiquesRepository $tarifsRepo): ?array
    {
        $jourSemaine = date('N'); 
        
        $tarif = $tarifsRepo->findByJourSemaine($jourSemaine);
        
        if ($tarif && (float)$tarif->getPourcentageReduction() > 0) {
            return [
                'jour' => $this->getNomJour($jourSemaine),
                'pourcentage' => (float) $tarif->getPourcentageReduction(),
                'tarif' => $tarif,
            ];
        }
        
        return null;
    }
    

    private function getNomJour(int $jourSemaine): string
    {
        $jours = [
            1 => 'lundi',
            2 => 'mardi', 
            3 => 'mercredi',
            4 => 'jeudi',
            5 => 'vendredi',
            6 => 'samedi',
            7 => 'dimanche'
        ];
        
        return $jours[$jourSemaine] ?? 'aujourd\'hui';
    }
    
    private function generateRandomRating(int $id): float
    {
        mt_srand($id);
        return round(rand(30, 50) / 10, 1);
    }
}