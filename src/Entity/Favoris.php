<?php

namespace App\Entity;

use App\Repository\FavorisRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FavorisRepository::class)]
#[ORM\Table(name: 'FAVORIS')]
class Favoris
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'IdFavori')]
    private ?int $idFavori = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'IdUtilisateur', referencedColumnName: 'IdUtilisateur', nullable: false)]
    private ?User $utilisateur = null; 

    #[ORM\ManyToOne(targetEntity: Film::class)]
    #[ORM\JoinColumn(name: 'IdFilm', referencedColumnName: 'IdFilm', nullable: false)]
    private ?Film $film = null;

    #[ORM\Column(name: 'DateAjout', type: 'datetime')]
    private ?\DateTimeInterface $dateAjout = null;

    public function __construct()
    {
        $this->dateAjout = new \DateTime();
    }

    public function getIdFavori(): ?int
    {
        return $this->idFavori;
    }

    public function getUtilisateur(): ?User
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?User $utilisateur): static
    {
        $this->utilisateur = $utilisateur;
        return $this;
    }

    public function getFilm(): ?Film
    {
        return $this->film;
    }

    public function setFilm(?Film $film): static
    {
        $this->film = $film;
        return $this;
    }

    public function getDateAjout(): ?\DateTimeInterface
    {
        return $this->dateAjout;
    }

    public function setDateAjout(\DateTimeInterface $dateAjout): static
    {
        $this->dateAjout = $dateAjout;
        return $this;
    }
    
    public function getUser(): ?User
    {
        return $this->utilisateur;
    }
    
    public function setUser(?User $user): static
    {
        $this->utilisateur = $user;
        return $this;
    }
}