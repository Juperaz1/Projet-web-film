<?php

namespace App\Controller;

use App\Repository\FilmRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(FilmRepository $filmRepository): Response
    {
        $popularFilms = $filmRepository->findByHighestRating(5);
        $formattedMovies = [];
        foreach ($popularFilms as $film) {
            $formattedMovies[] = [
                'id' => $film->getIdFilm(),
                'title' => $film->getTitre(),
                'genre' => $film->getGenresAsString(),
                'price' => (float) $film->getPrixLocationDefault(),
                'rating' => (float) $film->getNote() ?? 3.5,
                'image' => $film->getFullAfficheUrl(),
            ];
        }
        return $this->render('home/index.html.twig', ['title' => 'SiteFilm - Votre cinéma en ligne','popularMovies' => $formattedMovies,]);
    }
}