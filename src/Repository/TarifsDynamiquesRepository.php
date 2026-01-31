<?php
// src/Repository/TarifsDynamiquesRepository.php

namespace App\Repository;

use App\Entity\TarifsDynamiques;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class TarifsDynamiquesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TarifsDynamiques::class);
    }


    public function findByJourSemaine(int $jourSemaine): ?TarifsDynamiques
    {
        return $this->findOneBy(['jourSemaine' => $jourSemaine]);
    }
    
 
    public function findAllOrdered(): array
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.jourSemaine', 'ASC')
            ->getQuery()
            ->getResult();
    }
}