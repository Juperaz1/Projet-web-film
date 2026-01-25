<?php

namespace App\Repository;
use App\Entity\Film;
use App\Entity\Genre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

class FilmRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Film::class);
    }

    public function findAllWithGenres(): array
    {
        return $this->createQueryBuilder('f')->leftJoin('f.genres', 'g')->addSelect('g')->orderBy('f.titre', 'ASC')->getQuery()->getResult();
    }

    public function findByGenre(string $genreLibelle): array
    {
        return $this->createQueryBuilder('f')->leftJoin('f.genres', 'g')->andWhere('g.libelleGenre = :genre')->setParameter('genre', $genreLibelle)->orderBy('f.annee', 'DESC')->getQuery()->getResult();
    }

    public function findRecent(int $limit = 10): array
    {
        $currentYear = (int) date('Y');
        return $this->createQueryBuilder('f')->andWhere('f.annee >= :year')->setParameter('year', $currentYear - 3)->orderBy('f.annee', 'DESC')->setMaxResults($limit)->getQuery()->getResult();
    }

    public function findPopular(int $limit = 10): array
    {
        return $this->createQueryBuilder('f')->andWhere('f.note > :minNote')->setParameter('minNote', 4.0)->orderBy('f.note', 'DESC')->setMaxResults($limit)->getQuery()->getResult();
    }

    public function findByYear(int $year): array
    {
        return $this->createQueryBuilder('f')->andWhere('f.annee = :year')->setParameter('year', $year)->orderBy('f.titre', 'ASC')->getQuery()->getResult();
    }

    public function findAllGenres(): array
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery('SELECT DISTINCT g.libelleGenre FROM App\Entity\Genre g ORDER BY g.libelleGenre ASC');
        $results = $query->getScalarResult();
        return array_column($results, 'libelleGenre');
    }

    public function search(string $term): array
    {
        return $this->createQueryBuilder('f')->andWhere('f.titre LIKE :term')->setParameter('term', '%' . $term . '%')->orderBy('f.titre', 'ASC')->getQuery()->getResult();
    }

    public function findByHighestRating(int $limit = 5): array
    {
        return $this->createQueryBuilder('f')->andWhere('f.note IS NOT NULL')->orderBy('f.note', 'DESC')->setMaxResults($limit)->getQuery()->getResult();
    }
}