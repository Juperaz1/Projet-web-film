<?php

namespace App\Controller;

use App\Entity\Film;
use App\Entity\Location;
use App\Repository\TarifsDynamiquesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\FilmRepository;

class PaymentController extends AbstractController
{
    #[Route('/paiement', name: 'app_payment')]
    public function paiement(
        Request $request, 
        FilmRepository $filmRepository,
        TarifsDynamiquesRepository $tarifsRepo
    ): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $session = $request->getSession();
        $cart = $session->get('cart', []);
        
        if (empty($cart)) {
            $this->addFlash('warning', 'Votre panier est vide');
            return $this->redirectToRoute('app_cart');
        }

        $cartItems = [];
        $totalSansReduction = 0;
        
        foreach ($cart as $idFilm => $quantity) {
            $film = $filmRepository->find($idFilm);
            
            if ($film) {
                $price = (float) $film->getPrixLocationDefault();
                $subtotal = $price * $quantity;
                $totalSansReduction += $subtotal;
                
                $cartItems[] = [
                    'film' => $film,
                    'titre' => $film->getTitre(),
                    'prix' => $price,
                    'quantite' => $quantity,
                    'sous_total' => $subtotal,
                    'film_id' => $idFilm
                ];
            }
        }
        
        $promotionDuJour = $this->getPromotionDuJour($tarifsRepo);
        
        if ($promotionDuJour && $promotionDuJour['pourcentage'] > 0) {
            $reduction = ($totalSansReduction * $promotionDuJour['pourcentage']) / 100;
            $total = $totalSansReduction - $reduction;
        } else {
            $total = $totalSansReduction;
            $reduction = 0;
        }

        return $this->render('payment/index.html.twig', [
            'cartItems' => $cartItems,
            'total' => $total,
            'totalSansReduction' => $totalSansReduction,
            'reduction' => $reduction,
            'promotionDuJour' => $promotionDuJour
        ]);
    }

    #[Route('/paiement/valider', name: 'app_payment_validate')]
    public function valider(
        Request $request, 
        EntityManagerInterface $em, 
        FilmRepository $filmRepository,
        TarifsDynamiquesRepository $tarifsRepo
    ): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $session = $request->getSession();
        $cart = $session->get('cart', []);

        if (empty($cart)) {
            $this->addFlash('error', 'Votre panier est vide');
            return $this->redirectToRoute('app_cart');
        }

        $user = $this->getUser();
        
        $promotionDuJour = $this->getPromotionDuJour($tarifsRepo);
        $tauxReduction = $promotionDuJour ? $promotionDuJour['pourcentage'] : 0;

        foreach ($cart as $idFilm => $quantity) {
            $film = $filmRepository->find($idFilm);
            
            if ($film) {
                $price = (float) $film->getPrixLocationDefault();
                
                if ($tauxReduction > 0) {
                    $priceReduit = $price - ($price * $tauxReduction / 100);
                } else {
                    $priceReduit = $price;
                }
                
                for ($i = 0; $i < $quantity; $i++) {
                    $location = new Location();
                    
                    $location->setIdUtilisateur($user);
                    $location->setIdFilm($film);
                    $location->setDateLocation(new \DateTime());
                    $location->setPrixFinal($priceReduit);
                    
                    $em->persist($location);
                }
            }
        }

        try {
            $em->flush();
            $session->remove('cart');
            
            $message = 'Paiement effectué avec succès ! Vos films sont maintenant disponibles.';
            if ($tauxReduction > 0) {
                $message .= sprintf(' (Réduction de %s%% appliquée)', $tauxReduction);
            }
            
            $this->addFlash('success', $message);
            
        } catch (\Exception $e) {
            $this->addFlash('error', 'Une erreur est survenue lors du paiement : ' . $e->getMessage());
            return $this->redirectToRoute('app_cart');
        }

        return $this->redirectToRoute('app_payment_success');
    }

    #[Route('/paiement/succes', name: 'app_payment_success')]
    public function success(): Response
    {
        return $this->render('payment/success.html.twig');
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