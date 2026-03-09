<?php

namespace App\Repository;

use App\Entity\Playlist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository pour l'entité Playlist.
 * Donne des méthodes de requête personnalisées pour accéder aux playlists.
 * @extends ServiceEntityRepository<Playlist>
 */
class PlaylistRepository extends ServiceEntityRepository
{
    /**
     * Constructeur du repository.
     *
     * @param ManagerRegistry $registry Le registre des gestionnaires d'entités Doctrine
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Playlist::class);
    }

    /**
     * Ajoute une nouvelle playlist à la base de données
     *
     * @param Playlist $entity L'entité Playlist à enregistrer
     * @return void
     */

    public function add(Playlist $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * Supprime une playlist de la base de données.
     *
     * @param Playlist $entity L'entité Playlist à supprimer
     * @return void
     */

    public function remove(Playlist $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * Retourne toutes les playlists triées sur le nom de la playlist
     * @param string $champ
     * @param string $ordre
     * @return Playlist[]
     */
    public function findAllOrderByName($ordre): array
    {
        return $this->createQueryBuilder('p')
            ->leftjoin('p.formations', 'f')
            ->groupBy('p.id')
            ->orderBy('p.name', $ordre)
            ->getQuery()
            ->getResult();
    }


    /**
     * Retourne toutes les playlists triées par le nombre de formations
     *
     * @param string $ordre
     * @return Playlist[]
     */
    public function findAllOrderByNumberFormations($ordre): array
    {
        return $this->createQueryBuilder('p')
            ->select('p, COUNT(f.id) AS HIDDEN nombreFormations')
            ->leftjoin('p.formations', 'f')
            ->groupBy('p.id')
            ->orderBy('COUNT(f.id)', $ordre)
            ->getQuery()
            ->getResult();
    }

    /**
     * Enregistrements dont un champ contient une valeur
     * ou tous les enregistrements si la valeur est vide
     * @param string $champ
     * @param string $valeur
     * @param string $table si $champ dans une autre table
     * @return Playlist[]
     */
    public function findByContainValue($champ, $valeur, $table = ""): array
    {
        if ($valeur == "") {
            return $this->findAllOrderByName('ASC');
        }
        if ($table == "") {
            return $this->createQueryBuilder('p')
                ->leftjoin('p.formations', 'f')
                ->where('p.' . $champ . ' LIKE :valeur')
                ->setParameter('valeur', '%' . $valeur . '%')
                ->groupBy('p.id')
                ->orderBy('p.name', 'ASC')
                ->getQuery()
                ->getResult();
        } else {
            return $this->createQueryBuilder('p')
                ->leftjoin('p.formations', 'f')
                ->leftjoin('f.categories', 'c')
                ->where('c.' . $champ . ' LIKE :valeur')
                ->setParameter('valeur', '%' . $valeur . '%')
                ->groupBy('p.id')
                ->orderBy('p.name', 'ASC')
                ->getQuery()
                ->getResult();
        }
    }

}
