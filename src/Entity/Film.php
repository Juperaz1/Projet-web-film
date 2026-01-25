<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'FILM')]
class Film
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_film', type: 'integer')]
    private ?int $idFilm = null;

    #[ORM\Column(name: 'titre', type: 'string', length: 255, nullable: false)]
    private string $titre;

    #[ORM\Column(name: 'annee', type: 'smallint', nullable: false)]
    private int $annee;

    #[ORM\Column(name: 'duree', type: 'smallint', nullable: false, options: ['comment' => 'Durée en minutes'])]
    private int $duree;

    #[ORM\Column(name: 'synopsis', type: 'text', nullable: true)]
    private ?string $synopsis = null;

    #[ORM\Column(name: 'genre', type: 'string', length: 100, nullable: true)]
    private ?string $genre = null;

    #[ORM\Column(name: 'prix_location_par_default', type: 'decimal', precision: 5, scale: 2, nullable: false)]
    private string $prixLocationParDefault;

    #[ORM\Column(name: 'chemin_affiche', type: 'string', length: 500, nullable: true)]
    private ?string $cheminAffiche = null;

    public function getIdFilm(): ?int
    {
        return $this->idFilm;
    }
    public function getTitre(): string
    {
        return $this->titre;
    }
    public function getAnnee(): int
    {
        return $this->annee;
    }
    public function getDuree(): int
    {
        return $this->duree;
    }
    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }
    public function getGenre(): ?string
    {
        return $this->genre;
    }
    public function getPrixLocationParDefault(): string
    {
        return $this->prixLocationParDefault;
    }
    public function getCheminAffiche(): ?string
    {
        return $this->cheminAffiche;
    }


    public function setTitre(string $titre): self
    {
        $this->titre = $titre;
        return $this;
    }
    public function setDuree(int $duree): self
    {
        $this->duree = $duree;
        return $this;
    }
    public function setAnnee(int $annee): self
    {
        $this->annee = $annee;
        return $this;
    }
    public function setSynopsis(?string $synopsis): self
    {
        $this->synopsis = $synopsis;
        return $this;
    }
    public function setGenre(?string $genre): self
    {
        $this->genre = $genre;
        return $this;
    }
    public function setPrixLocationParDefault(string $prixLocationParDefault): self
    {
        $this->prixLocationParDefault = $prixLocationParDefault;
        return $this;
    }
    public function setCheminAffiche(?string $cheminAffiche): self
    {
        $this->cheminAffiche = $cheminAffiche;
        return $this;
    }

    /**
     * Formate la durée en format lisible (ex: "2h28" ou "148 min")
     */
    public function getFormattedDuration(): string
    {
        if($this->duree >= 60)
        {
            $hours = floor($this->duree / 60);
            $minutes = $this->duree % 60;
            return sprintf('%dh%02d', $hours, $minutes);
        }
        return sprintf('%d min', $this->duree);
    }

    /**
     * Retourne l'URL complète de l'affiche ou une image par défaut
     */
    public function getFullAfficheUrl(): string
    {
        if($this->cheminAffiche && str_starts_with($this->cheminAffiche, 'http'))
        {
            return $this->cheminAffiche;
        }
        $defaultColor = substr(md5($this->titre), 0, 6);
        return sprintf('https://via.placeholder.com/300x450/%s/ffffff?text=%s', $defaultColor, urlencode(substr($this->titre, 0, 20)));
    }
}