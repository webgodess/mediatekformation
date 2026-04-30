<?php

namespace App\Repository;

use App\Entity\Formation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository pour l'entité Formation.
 * Donne des méthodes de requête personnalisées pour accéder aux formations.
 *
 * @extends ServiceEntityRepository<Formation>
 */
class FormationRepository extends ServiceEntityRepository
{

    /**
     * Constructeur du repository.
     *
     * @param ManagerRegistry $registry Le registre des gestionnaires d'entités Doctrine
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Formation::class);
    }

    /**
     * Ajoute une nouvelle formation à la base de données.
     *
     * @param Formation $entity L'entité Formation à enregistrer
     * @return void
     */

    public function add(Formation $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * Supprime une formation de la base de données.
     *
     * @param Formation $entity L'entité Formation à supprimer
     * @return void
     */

    public function remove(Formation $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * Retourne toutes les formations triées sur un champ
     * @param string $champ
     * @param string $ordre
     * @param string $table si $champ dans une autre table
     * @return Formation[]
     */

    /**
     * Retourne toutes les formations triées sur un champ donné.
     * Permet de trier sur un champ d'une table associée si précisée.
     *
     * @param string $champ  Le nom du champ sur lequel effectuer le tri
     * @param string $ordre  L'ordre de tri : 'ASC' pour croissant, 'DESC' pour décroissant
     * @param string $table  La relation à joindre si le champ appartient à une autre entité
     *                       (ex: 'playlist', 'categories'). Laisser vide pour trier dans Formation.
     * @return Formation[] Un tableau d'entités Formation triées selon les critères
     */

    public function findAllOrderBy($champ, $ordre, $table = ""): array
    {
        if ($table == "") {
            return $this->createQueryBuilder('f')
                ->orderBy('f.' . $champ, $ordre)
                ->getQuery()
                ->getResult();
        } else {
            return $this->createQueryBuilder('f')
                ->join('f.' . $table, 't')
                ->orderBy('t.' . $champ, $ordre)
                ->getQuery()
                ->getResult();
        }
    }

    /**
     * Enregistrements dont un champ contient une valeur
     * ou tous les enregistrements si la valeur est vide
     * @param string $champ
     * @param string $valeur
     * @param string $table si $champ dans une autre table
     * @return Formation[]
     */
    public function findByContainValue($champ, $valeur, $table = ""): array
    {
        if ($valeur == "") {
            return $this->findAll();
        }
        if ($table == "") {
            return $this->createQueryBuilder('f')
                ->where('f.' . $champ . ' LIKE :valeur')
                ->orderBy('f.publishedAt', 'DESC')
                ->setParameter('valeur', '%' . $valeur . '%')
                ->getQuery()
                ->getResult();
        } else {
            return $this->createQueryBuilder('f')
                ->join('f.' . $table, 't')
                ->where('t.' . $champ . ' LIKE :valeur')
                ->orderBy('f.publishedAt', 'DESC')
                ->setParameter('valeur', '%' . $valeur . '%')
                ->getQuery()
                ->getResult();
        }
    }

    /**
     * Retourne les n formations les plus récentes, triées par date de publication décroissante.
     * @param int $nb
     * @return Formation[]
     */
    public function findAllLasted($nb): array
    {
        return $this->createQueryBuilder('f')
            ->orderBy('f.publishedAt', 'DESC')
            ->setMaxResults($nb)
            ->getQuery()
            ->getResult();
    }

    /**
     * Retourne les formations les plus récentes avec une limite de résultats.
     *
     * @param int $limit Le nombre maximum de formations à retourner
     * @return Formation[] Un tableau d'entités Formation
     */
    public function findAllWithLimit($limit = 50): array
    {
        return $this->createQueryBuilder('f')
            ->orderBy('f.publishedAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Retourne la liste des formations d'une playlist
     * triées par date de publication croissante.
     * 
     * @param int $idPlaylist identifiant de la playlist dont on veut les formations.
     * @return array tableau d'entités Formation liées à la playlist.
     */
    public function findAllForOnePlaylist($idPlaylist): array
    {
        return $this->createQueryBuilder('f')
            ->join('f.playlist', 'p')
            ->where('p.id=:id')
            ->setParameter('id', $idPlaylist)
            ->orderBy('f.publishedAt', 'ASC')
            ->getQuery()
            ->getResult();
    }

}
