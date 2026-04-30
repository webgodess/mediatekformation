<?php

namespace App\Repository;

use App\Entity\Categorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository pour l'entité Categorie.
 * Donne des méthodes de requête personnalisées pour accéder aux catégories.
 *
 * @extends ServiceEntityRepository<Categorie>
 */

class CategorieRepository extends ServiceEntityRepository
{

    /**
     * Constructeur du repository.
     *
     * @param ManagerRegistry $registry Le registre des gestionnaires d'entités Doctrine
     */


    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Categorie::class);
    }

    /**
     * Ajoute une nouvelle catégorie à la base de données.
     *
     * @param Categorie $entity L'entité Categorie à enregistrer
     * @return void
     */

    public function add(Categorie $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * Supprime une catégorie de la base de données.
     *
     * @param Categorie $entity L'entité Categorie à supprimer
     * @return void
     */

    public function remove(Categorie $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * Retourne les catégories avec une limite de résultats.
     *
     * @param int $limit Le nombre maximum de catégories à retourner
     * @return array Un tableau d'entités Categorie
     */
    public function findAllWithLimit($limit = 50): array
    {
        return $this->createQueryBuilder('c')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Retourne la liste des catégories des formations d'une playlist
     * triées par nom de catégorie par ordre croissant.
     * @param int $idPlaylist L'identifiant de la playlist dont on veut les catégories
     * @return array tableau d'entités Categorie liées à la playlist
     */
    public function findAllForOnePlaylist($idPlaylist): array
    {
        return $this->createQueryBuilder('c')
            ->join('c.formations', 'f')
            ->join('f.playlist', 'p')
            ->where('p.id=:id')
            ->setParameter('id', $idPlaylist)
            ->orderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

}
