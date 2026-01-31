<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Repository\FavorisRepository;
use App\Repository\LocationRepository;
use Doctrine\ORM\EntityManagerInterface;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    #[IsGranted('ROLE_USER')]
    public function index(FavorisRepository $favorisRepository, LocationRepository $locationRepository): Response
    {
        $user = $this->getUser();

        try {
            $locations = $locationRepository->findBy(['utilisateur' => $user], ['dateLocation' => 'DESC']);
        } catch (\Exception $e) {
            $locations = [];
        }

        try {
            $favorisEntities = $favorisRepository->findBy(['utilisateur' => $user], ['dateAjout' => 'DESC']);
        } catch (\Exception $e) {
            $favorisEntities = [];
        }

        $favoris = [];
        foreach ($favorisEntities as $favori) {
            $film = $favori->getFilm();
            $favoris[] = [
                'entity' => $favori,
                'titre' => $film->getTitre(),
                'annee' => $film->getAnnee(),
                'duree' => $film->getFormattedDuration(),
                'categorie' => $film->getGenresAsString(),
                'date_ajout' => $favori->getDateAjout(),
                'film_id' => $film->getIdFilm(),
                'affiche' => $film->getFullAfficheUrl(),
                'film' => $film
            ];
        }

        $totalDepense = 0;
        foreach ($locations as $location) {
            $totalDepense += (float) $location->getPrixFinal();
        }

        $favorisFilms = array_map(function($favori) {
            return $favori['film'];
        }, $favoris);

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'locations' => $locations,
            'favoris' => $favoris,
            'favorisFilms' => $favorisFilms,
            'totalDepense' => $totalDepense,
        ]);
    }

    #[Route('/profile/favoris', name: 'app_favoris')]
    #[IsGranted('ROLE_USER')]
    public function favoris(FavorisRepository $favorisRepository): Response
    {
        $user = $this->getUser();

        try {
            $favorisEntities = $favorisRepository->findUserFavorites($user);

            $favoris = [];
            foreach ($favorisEntities as $favori) {
                $film = $favori->getFilm();
                $favoris[] = [
                    'titre' => $film->getTitre(),
                    'annee' => $film->getAnnee(),
                    'duree' => $film->getFormattedDuration(),
                    'categorie' => $film->getGenresAsString(),
                    'date_ajout' => $favori->getDateAjout(),
                    'film_id' => $film->getIdFilm(),
                    'affiche' => $film->getFullAfficheUrl(),
                    'film' => $film
                ];
            }

        } catch (\Exception $e) {
            $favoris = [];
        }

        return $this->render('favorites/index.html.twig', [
            'user' => $user,
            'favoris' => $favoris,
        ]);
    }

    #[Route('/profile/parametres', name: 'app_parametres')]
    #[IsGranted('ROLE_USER')]
    public function parametres(): Response
    {
        $user = $this->getUser();

        return $this->render('profile/parametres.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/profile/locations', name: 'app_mes_locations')]
    #[IsGranted('ROLE_USER')]
    public function mesLocations(LocationRepository $locationRepository): Response
    {
        $user = $this->getUser();

        try {
            $locations = $locationRepository->findBy(['utilisateur' => $user], ['dateLocation' => 'DESC']);
        } catch (\Exception $e) {
            $locations = [];
        }

        $totalDepense = 0;
        foreach ($locations as $location) {
            $totalDepense += (float) $location->getPrixFinal();
        }

        return $this->render('profile/locations.html.twig', [
            'user' => $user,
            'locations' => $locations,
            'totalDepense' => $totalDepense,
        ]);
    }

    #[Route('/profile/favoris/supprimer/{id}', name: 'app_favoris_supprimer', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function supprimerFavori(int $id, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        
        if (!$user) {
            $this->addFlash('error', 'Vous devez être connecté pour supprimer un favori.');
            return $this->redirectToRoute('app_login');
        }
        
        try {
            $film = $em->getRepository(\App\Entity\Film::class)->find($id);
            
            if (!$film) {
                $this->addFlash('error', 'Film non trouvé.');
                return $this->redirectToRoute('app_profile');
            }
            
            $favori = $em->getRepository(\App\Entity\Favoris::class)->findOneBy([
                'utilisateur' => $user,
                'film' => $film
            ]);
            
            if ($favori) {
                $em->remove($favori);
                $em->flush();
                $this->addFlash('success', 'Film retiré des favoris.');
            } else {
                $this->addFlash('warning', 'Ce film n\'était pas dans vos favoris.');
            }
        } catch (\Exception $e) {
            $this->addFlash('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
        
        return $this->redirectToRoute('app_profile');
    }

    #[Route('/profile/favoris/ajouter/{id}', name: 'app_favoris_ajouter', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function ajouterFavori(int $id, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        
        if (!$user) {
            $this->addFlash('error', 'Vous devez être connecté pour ajouter un favori.');
            return $this->redirectToRoute('app_login');
        }
        
        try {
            $film = $em->getRepository(\App\Entity\Film::class)->find($id);
            
            if (!$film) {
                $this->addFlash('error', 'Film non trouvé.');
                return $this->redirectToRoute('app_profile');
            }
            
            $existingFavori = $em->getRepository(\App\Entity\Favoris::class)->findOneBy([
                'utilisateur' => $user,
                'film' => $film
            ]);
            
            if (!$existingFavori) {
                $favori = new \App\Entity\Favoris();
                $favori->setUtilisateur($user);
                $favori->setFilm($film);
                $favori->setDateAjout(new \DateTime());
                
                $em->persist($favori);
                $em->flush();
                $this->addFlash('success', 'Film ajouté aux favoris.');
            } else {
                $this->addFlash('warning', 'Ce film est déjà dans vos favoris.');
            }
        } catch (\Exception $e) {
            $this->addFlash('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
        
        return $this->redirectToRoute('app_profile');
    }
}