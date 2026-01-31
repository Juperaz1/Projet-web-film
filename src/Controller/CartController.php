<?php

namespace App\Controller;

use App\Repository\FilmRepository;
use App\Repository\TarifsDynamiquesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/panier', name: 'app_cart', methods: ['GET'])]
    public function index(
        SessionInterface $session, 
        FilmRepository $filmRepository,
        TarifsDynamiquesRepository $tarifsRepo
    ): Response
    {
        $cart = $session->get('cart', []);
        $cartItems = [];
        $total = 0;
        $totalSansReduction = 0;
        $reduction = 0;
        
        $promotionDuJour = $this->getPromotionDuJour($tarifsRepo);
        
        foreach ($cart as $idFilm => $quantity) {
            $film = $filmRepository->find($idFilm);

            if ($film) {
                $price = (float) $film->getPrixLocationDefault();
                $subtotal = $price * $quantity;
                $totalSansReduction += $subtotal;

                $cartItems[] = [
                    'film' => $film,
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $subtotal,
                ];
            }
        }
        
        if ($promotionDuJour && $promotionDuJour['pourcentage'] > 0) {
            $reduction = ($totalSansReduction * $promotionDuJour['pourcentage']) / 100;
            $total = $totalSansReduction - $reduction;
        } else {
            $total = $totalSansReduction;
        }

        return $this->render('cart/index.html.twig', [
            'cartItems' => $cartItems,
            'total' => $total,
            'totalSansReduction' => $totalSansReduction,
            'reduction' => $reduction,
            'promotionDuJour' => $promotionDuJour,
        ]);
    }

    #[Route('/panier/ajouter/{idFilm}', name: 'app_cart_add', methods: ['GET'])]
    public function add(int $idFilm, SessionInterface $session, FilmRepository $filmRepository): Response
    {
        $film = $filmRepository->find($idFilm);
        if (!$film) {
            throw $this->createNotFoundException('Film introuvable');
        }

        $cart = $session->get('cart', []);
        $cart[$idFilm] = ($cart[$idFilm] ?? 0) + 1;
        $session->set('cart', $cart);

        $this->addFlash('success', sprintf('"%s" ajouté au panier', $film->getTitre()));

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/panier/supprimer/{idFilm}', name: 'app_cart_remove', methods: ['GET'])]
    public function remove(int $idFilm, SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);
        
        if (isset($cart[$idFilm])) {
            unset($cart[$idFilm]);
            $session->set('cart', $cart);
            $this->addFlash('success', 'Film supprimé du panier');
        } else {
            $this->addFlash('warning', 'Ce film n\'était pas dans votre panier');
        }

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/panier/vider', name: 'app_cart_clear', methods: ['GET'])]
    public function clear(SessionInterface $session): Response
    {
        $session->remove('cart');
        $this->addFlash('success', 'Panier vidé');

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/panier/mettre-a-jour/{idFilm}/{quantite}', name: 'app_cart_update', methods: ['GET'])]
    public function update(int $idFilm, int $quantite, SessionInterface $session, FilmRepository $filmRepository): Response
    {
        $film = $filmRepository->find($idFilm);
        if (!$film) {
            throw $this->createNotFoundException('Film introuvable');
        }

        $cart = $session->get('cart', []);
        
        if ($quantite <= 0) {
            unset($cart[$idFilm]);
            $this->addFlash('success', sprintf('"%s" supprimé du panier', $film->getTitre()));
        } else {
            $cart[$idFilm] = $quantite;
            $this->addFlash('success', sprintf('Quantité de "%s" mise à jour', $film->getTitre()));
        }
        
        $session->set('cart', $cart);

        return $this->redirectToRoute('app_cart');
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
}