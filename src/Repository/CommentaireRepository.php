<?php

namespace App\Repository;

use App\Entity\Commentaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Commentaire>
 */
class CommentaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commentaire::class);
    }

    /**
     * Retourne la liste des commentaires validés d'une formation triés par date de publication par ordre croissant.
     * @return Commentaire[] Returns an array of Commentaire objects
     */

    public function findValidatedByFormation($idformation): array
    {
        return $this->createQueryBuilder('cmt')
            ->join('cmt.formation', 'f')
            ->where('f.id = :id AND cmt.estValide = false')
            ->setParameter('id', $idformation)
            ->orderBy('cmt.datePublication', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Retourne la liste de tous les commentaires d'une formation triés par date de publication par ordre croissant.
     * @return Commentaire[] Returns an array of Commentaire objects
     */

    public function findAllByFormation($idformation): array
    {
        return $this->createQueryBuilder('cmt')
            ->join('cmt.formation', 'f')
            ->where('f.id = :id')
            ->setParameter('id', $idformation)
            ->orderBy('cmt.datePublication', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Retourne la liste de tous les commentaires triés par formation et par date de publication par ordre croissant.
     * @return Commentaire[] Returns an array of Commentaire objects
     */

    public function findAllOrderByFormation(): array
    {
        return $this->createQueryBuilder('cmt')
            ->orderBy('cmt.formation', 'ASC')
            ->getQuery()
            ->getResult();
    }



    public function add(Commentaire $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

}
