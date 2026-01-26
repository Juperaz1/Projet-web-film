<?php
// src/Controller/ProfileController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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
    
    #[Route('/profile/favoris', name: 'app_favoris')]
    #[IsGranted('ROLE_USER')]
    public function favoris(): Response
    {
        $user = $this->getUser();
        
        return $this->render('profile/favoris.html.twig', [
            'user' => $user,
            'favoris' => [],
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