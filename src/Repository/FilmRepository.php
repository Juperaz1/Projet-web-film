<?php

namespace App\Repository;
use App\Entity\Film;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class FilmRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Film::class);
    }

    public function findByGenre(string $genre): array
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.genre = :genre')
            ->setParameter('genre', $genre)
            ->orderBy('f.annee', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findRecent(int $limit = 10): array
    {
        $currentYear = (int) date('Y');
        
        return $this->createQueryBuilder('f')
            ->andWhere('f.annee >= :year')
            ->setParameter('year', $currentYear - 3)
            ->orderBy('f.annee', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findByYear(int $year): array
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.annee = :year')
            ->setParameter('year', $year)
            ->orderBy('f.titre', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findAllGenres(): array
    {
        $query = $this->createQueryBuilder('f')
            ->select('DISTINCT f.genre')
            ->andWhere('f.genre IS NOT NULL')
            ->orderBy('f.genre', 'ASC')
            ->getQuery();

        $results = $query->getScalarResult();
        
        return array_column($results, 'genre');
    }

    public function search(string $term): array
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.titre LIKE :term OR f.synopsis LIKE :term')
            ->setParameter('term', '%' . $term . '%')
            ->orderBy('f.titre', 'ASC')
            ->getQuery()
            ->getResult();
    }
}