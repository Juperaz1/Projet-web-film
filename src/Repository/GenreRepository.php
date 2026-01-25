<?php

namespace App\Repository;
use App\Entity\Genre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class GenreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Genre::class);
    }

    public function findByLibelle(string $libelle): ?Genre
    {
        return $this->createQueryBuilder('g')->andWhere('g.libelleGenre = :libelle')->setParameter('libelle', $libelle)->getQuery()->getOneOrNullResult();
    }

    public function findOrCreate(string $libelle): Genre
    {
        $genre = $this->findByLibelle($libelle);
        if(!$genre)
        {
            $genre = new Genre();
            $reflection = new \ReflectionClass($genre);
            $property = $reflection->getProperty('libelleGenre');
            $property->setAccessible(true);
            $property->setValue($genre, $libelle);            
            $entityManager = $this->getEntityManager();
            $entityManager->persist($genre);
            $entityManager->flush();
        }
        return $genre;
    }

    public function getAllLibelles(): array
    {
        $genres = $this->createQueryBuilder('g')->select('g.libelleGenre')->orderBy('g.libelleGenre', 'ASC')->getQuery()->getScalarResult();
        return array_column($genres, 'libelleGenre');
    }
}