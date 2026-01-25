<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Film;

#[ORM\Entity]
#[ORM\Table(name: 'GENRES')]
class Genre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'IdGenre', type: 'integer')]
    private ?int $idGenre = null;

    #[ORM\Column(name: 'LibelleGenre', type: 'string', length: 50, nullable: false)]
    private string $libelleGenre;

    #[ORM\ManyToMany(targetEntity: Film::class, mappedBy: 'genres')]
    private Collection $films;

    public function __construct()
    {
        $this->films = new ArrayCollection();
    }

    public function getIdGenre(): ?int
    {
        return $this->idGenre;
    }
    public function getLibelleGenre(): string
    {
        return $this->libelleGenre;
    }
    public function getFilms(): Collection
    {
        return $this->films;
    }

    public function __toString(): string
    {
        return $this->libelleGenre;
    }

    public function addFilm(Film $film): self
    {
        if (!$this->films->contains($film)) {
            $this->films[] = $film;
        }
        return $this;
    }

    public function removeFilm(Film $film): self
    {
        $this->films->removeElement($film);
        return $this;
    }
}