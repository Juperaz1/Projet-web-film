<?php

namespace App\Entity;

use App\Repository\TarifsDynamiquesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TarifsDynamiquesRepository::class)]
#[ORM\Table(name: 'TARIFSDYNAMIQUES')]
class TarifsDynamiques
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'IdTarif', type: 'integer')]
    private ?int $idTarif = null;

    #[ORM\Column(name: 'JourSemaine', type: 'smallint')]
    private ?int $jourSemaine = null;

    #[ORM\Column(name: 'PourcentageReduction', type: 'decimal', precision: 5, scale: 2)]
    private ?string $pourcentageReduction = null;

    public function getIdTarif(): ?int
    {
        return $this->idTarif;
    }

    public function getJourSemaine(): ?int
    {
        return $this->jourSemaine;
    }

    public function setJourSemaine(int $jourSemaine): static
    {
        $this->jourSemaine = $jourSemaine;

        return $this;
    }

    public function getPourcentageReduction(): ?string
    {
        return $this->pourcentageReduction;
    }

    public function setPourcentageReduction(string $pourcentageReduction): static
    {
        $this->pourcentageReduction = $pourcentageReduction;

        return $this;
    }
    

    public function getNomJour(): string
    {
        $jours = [
            1 => 'Lundi',
            2 => 'Mardi', 
            3 => 'Mercredi',
            4 => 'Jeudi',
            5 => 'Vendredi',
            6 => 'Samedi',
            7 => 'Dimanche'
        ];
        
        return $jours[$this->jourSemaine] ?? 'Inconnu';
    }

    public function getPourcentageFormate(): string
    {
        return number_format((float)$this->pourcentageReduction, 0);
    }
}