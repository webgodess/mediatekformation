<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * Repository pour l'entité User.
 * Fournit des méthodes de requête personnalisées pour accéder aux utilisateurs
 * et implémente la mise à jour automatique des mots de passe hachés.
 *
 * @extends ServiceEntityRepository<User>
 */


class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{

    /**
     * Constructeur du repository.
     *
     * @param ManagerRegistry $registry Le registre des gestionnaires d'entités Doctrine
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Met à jour (re-hache) automatiquement le mot de passe de l'utilisateur au fil du temps.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {

        // Vérifie que l'utilisateur est bien une instance de l'entité User
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }
        // Met à jour le mot de passe et persiste les changements en base de données
        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }
}
