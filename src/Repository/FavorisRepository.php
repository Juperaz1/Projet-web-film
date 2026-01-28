<?php

namespace App\Repository;

use App\Entity\Favoris;
use App\Entity\User;
use App\Entity\Film;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Favoris>
 */
class FavorisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Favoris::class);
    }

    public function isFilmInFavoris(User $user, Film $film): bool
    {
        try {
            $result = $this->createQueryBuilder('f')
                ->select('COUNT(f.idFavori)')
                ->where('f.utilisateur = :user')
                ->andWhere('f.film = :film')
                ->setParameter('user', $user)
                ->setParameter('film', $film)
                ->getQuery()
                ->getSingleScalarResult();

            return $result > 0;
        } catch (\Exception $e) {
            error_log("Error in isFilmInFavoris: " . $e->getMessage());
            return false;
        }
    }

    public function findUserFavorites(User $user): array
    {
        try {
            return $this->createQueryBuilder('f')
                ->join('f.film', 'film')
                ->addSelect('film')
                ->where('f.utilisateur = :user')
                ->setParameter('user', $user)
                ->orderBy('f.dateAjout', 'DESC')
                ->getQuery()
                ->getResult();
        } catch (\Exception $e) {
            error_log("Error in findUserFavorites: " . $e->getMessage());
            return [];
        }
    }

    public function addToFavorites(User $user, Film $film): bool
    {
        try {
            if (!$this->isFilmInFavoris($user, $film)) {
                $favoris = new Favoris();
                $favoris->setUtilisateur($user);
                $favoris->setFilm($film);
                $favoris->setDateAjout(new \DateTime());
                
                $this->getEntityManager()->persist($favoris);
                $this->getEntityManager()->flush();
                return true;
            }
            return false;
        } catch (\Exception $e) {
            error_log("Error in addToFavorites: " . $e->getMessage());
            return false;
        }
    }

    public function removeFromFavorites(User $user, Film $film): bool
    {
        try {
            $favoris = $this->createQueryBuilder('f')
                ->where('f.utilisateur = :user')
                ->andWhere('f.film = :film')
                ->setParameter('user', $user)
                ->setParameter('film', $film)
                ->getQuery()
                ->getOneOrNullResult();

            if ($favoris) {
                $this->getEntityManager()->remove($favoris);
                $this->getEntityManager()->flush();
                return true;
            }
            return false;
        } catch (\Exception $e) {
            error_log("Error in removeFromFavorites: " . $e->getMessage());
            return false;
        }
    }

    public function countUserFavorites(User $user): int
    {
        try {
            return (int) $this->createQueryBuilder('f')
                ->select('COUNT(f.idFavori)')
                ->where('f.utilisateur = :user')
                ->setParameter('user', $user)
                ->getQuery()
                ->getSingleScalarResult();
        } catch (\Exception $e) {
            error_log("Error in countUserFavorites: " . $e->getMessage());
            return 0;
        }
    }
}