<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Repository\FavorisRepository;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    #[IsGranted('ROLE_USER')]
    public function index(): Response
    {
        $user = $this->getUser();

        return $this->render('profile/index.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/favorites/index', name: 'app_favoris')]
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
                    'affiche' => $film->getFullAfficheUrl()
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
}
