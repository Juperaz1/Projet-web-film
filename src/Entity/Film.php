<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity]
#[ORM\Table(name: 'FILMS')]
class Film
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'IdFilm', type: 'integer')]
    private ?int $idFilm = null;

    #[ORM\Column(name: 'Titre', type: 'string', length: 255, nullable: false)]
    private string $titre;

    #[ORM\Column(name: 'Annee', type: 'smallint', nullable: false)]
    private int $annee;

    #[ORM\Column(name: 'Duree', type: 'smallint', nullable: false)]
    private int $duree;

    #[ORM\Column(name: 'Synopsis', type: 'text', nullable: true)]
    private ?string $synopsis = null;

    #[ORM\Column(name: 'PrixLocationDefault', type: 'decimal', precision: 5, scale: 2, nullable: false)]
    private string $prixLocationDefault;

    #[ORM\Column(name: 'CheminAffiche', type: 'string', length: 500, nullable: true)]
    private ?string $cheminAffiche = null;

    #[ORM\Column(name: 'Note', type: 'decimal', precision: 2, scale: 1, nullable: true)]
    private ?string $note = null;

    #[ORM\ManyToMany(targetEntity: Genre::class)]
    #[ORM\JoinTable(name: 'FILM_GENRE')]
    #[ORM\JoinColumn(name: 'IdFilm', referencedColumnName: 'IdFilm')]
    #[ORM\InverseJoinColumn(name: 'IdGenre', referencedColumnName: 'IdGenre')]
    private Collection $genres;

    public function __construct()
    {
        $this->genres = new ArrayCollection();
    }

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
    public function getPrixLocationDefault(): string
    {
        return $this->prixLocationDefault;
    }
    public function getCheminAffiche(): ?string
    {
        return $this->cheminAffiche;
    }
    public function getNote(): ?string
    {
        return $this->note;
    }
    public function getGenres(): Collection
    {
        return $this->genres;
    }


    public function setTitre(string $titre): self
    {
        $this->titre = $titre;
        return $this;
    }
    public function setAnnee(int $annee): self
    {
        $this->annee = $annee;
        return $this;
    }
    public function setDuree(int $duree): self
    {
        $this->duree = $duree;
        return $this;
    }
    public function setSynopsis(?string $synopsis): self
    {
        $this->synopsis = $synopsis;
        return $this;
    }
    public function setPrixLocationDefault(string $prixLocationDefault): self
    {
        $this->prixLocationDefault = $prixLocationDefault;
        return $this;
    }
    public function setCheminAffiche(?string $cheminAffiche): self
    {
        $this->cheminAffiche = $cheminAffiche;
        return $this;
    }
    public function setNote(?string $note): self
    {
        $this->note = $note;
        return $this;
    }

    public function addGenre(Genre $genre): self
    {
        if(!$this->genres->contains($genre))
        {
            $this->genres[] = $genre;
        }
        return $this;
    }
    public function removeGenre(Genre $genre): self
    {
        $this->genres->removeElement($genre);
        return $this;
    }

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

    public function getFullAfficheUrl(): string
    {
        if(!$this->cheminAffiche)
        {
            $defaultColor = substr(md5($this->titre), 0, 6);
            return sprintf('https://via.placeholder.com/300x450/%s/ffffff?text=%s', $defaultColor, urlencode(substr($this->titre, 0, 20)));
        }
        if(str_starts_with($this->cheminAffiche, 'http'))
        {
            return $this->cheminAffiche;
        }
        if(str_starts_with($this->cheminAffiche, '/images/'))
        {
            return $this->cheminAffiche;
        }
        if(!str_starts_with($this->cheminAffiche, '/'))
        {
            return '/images/films/' . $this->cheminAffiche;
        }
        return $this->cheminAffiche;
    }

    public function getGenresAsString(): string
    {
        $genres = [];
        foreach ($this->genres as $genre)
        {
            $genres[] = $genre->getLibelleGenre();
        }
        return implode(', ', $genres) ?: 'Non spécifié';
    }

    public function isRecent(): bool
    {
        return (date('Y') - $this->annee) <= 2;
    }

    public function isPopular(): bool
    {
        return (float) $this->note > 4.00;
    }
}