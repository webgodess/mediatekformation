<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Entité représentant un utilisateur de l'application.
 * Un utilisateur possède un nom d'utilisateur unique, un mot de passe haché et des rôles pour la gestion des permissions.
 */

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]

    // Identifiant unique de l'utilisateur
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $username = null;

    /**
     * @var list<string>
     * Rôles de l'utilisateur pour la gestion des permissions (ex: ROLE_USER, ROLE_ADMIN)
     */

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string
     * Mot de passe haché de l'utilisateur. Ne doit jamais être stocké en clair pour des raisons de sécurité
     */

    #[ORM\Column]
    private ?string $password = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * l'identifiant visuel représentant l'utilisateur.
     *
     * @see UserInterface
     * @return string L'identifiant de l'utilisateur (généralement le nom d'utilisateur)
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // garantit que chaque utilisateur a au moins le rôle ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     * Efface les données sensibles de l'utilisateur. Cette méthode est appelée après l'authentification pour nettoyer les données temporaires.
     *
     * @return void
     */
    public function eraseCredentials(): void
    {
        // Si vous stockez des données sensibles temporairement sur l'utilisateur, effacez-les ici
        // $this->plainPassword = null;
    }
}
