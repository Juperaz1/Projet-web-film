<?php

namespace App\Controller;
use App\Entity\Film;
use App\Form\FilmType;
use App\Repository\FilmRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AdminController extends AbstractController
{
    #[Route('/admin/films', name: 'admin_films')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(FilmRepository $filmRepository): Response
    {
        return $this->render('admin/films.html.twig', ['films' => $filmRepository->findAll(),]);
    }

    #[Route('/admin/films/new', name: 'admin_film_new')]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $film = new Film();
        $form = $this->createForm(FilmType::class, $film);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager->persist($film);
            $entityManager->flush();
            $this->addFlash('success', 'Film créé avec succès !');
            return $this->redirectToRoute('admin_films');
        }
        return $this->render('admin/new_film.html.twig', ['form' => $form->createView(),]);
    }

    #[Route('/admin/films/{id}/edit', name: 'admin_film_edit')]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Film $film, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FilmType::class, $film);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager->flush();
            $this->addFlash('success', 'Film modifié avec succès !');
            return $this->redirectToRoute('admin_films');
        }

        return $this->render('admin/edit_film.html.twig', ['form' => $form->createView(),'film' => $film,]);
    }

    #[Route('/admin/films/{id}/delete', name: 'admin_film_delete')]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Film $film, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($film);
        $entityManager->flush();
        $this->addFlash('success', 'Film supprimé avec succès !');
        return $this->redirectToRoute('admin_films');
    }
}