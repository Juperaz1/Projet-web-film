<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\LocationRepository; 

#[ORM\Entity(repositoryClass: LocationRepository::class)] 
#[ORM\Table(name: 'LOCATIONS')]
class Location
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'IdLocation', type: 'integer')]
    private ?int $idLocation = null;

    #[ORM\ManyToOne(targetEntity: User::class)] 
    #[ORM\JoinColumn(name: 'IdUtilisateur', referencedColumnName: 'IdUtilisateur')]
    private ?User $utilisateur = null;

    #[ORM\ManyToOne(targetEntity: Film::class)]
    #[ORM\JoinColumn(name: 'IdFilm', referencedColumnName: 'IdFilm')]
    private ?Film $film = null;

    #[ORM\Column(name: 'DateLocation', type: 'datetime')]
    private ?\DateTimeInterface $dateLocation = null;

    #[ORM\Column(name: 'PrixFinal', type: 'decimal', precision: 5, scale: 2)]
    private string $prixFinal;

    public function getIdLocation(): ?int
    {
        return $this->idLocation;
    }

    public function getUtilisateur(): ?User 
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?User $utilisateur): self 
    {
        $this->utilisateur = $utilisateur;
        return $this;
    }

    public function getFilm(): ?Film
    {
        return $this->film;
    }

    public function setFilm(?Film $film): self
    {
        $this->film = $film;
        return $this;
    }

    public function getDateLocation(): ?\DateTimeInterface
    {
        return $this->dateLocation;
    }

    public function setDateLocation(\DateTimeInterface $dateLocation): self
    {
        $this->dateLocation = $dateLocation;
        return $this;
    }

    public function getPrixFinal(): string
    {
        return $this->prixFinal;
    }

    public function setPrixFinal(string $prixFinal): self
    {
        $this->prixFinal = $prixFinal;
        return $this;
    }

    public function setIdUtilisateur($user): self
    {
        if ($user instanceof User) {
            $this->utilisateur = $user;
        } elseif (is_numeric($user)) {
        }
        return $this;
    }

    public function setIdFilm($film): self
    {
        if ($film instanceof Film) {
            $this->film = $film;
        }
        return $this;
    }
}