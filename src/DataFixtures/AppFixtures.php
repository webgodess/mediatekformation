<?php

namespace App\DataFixtures;


use App\Entity\User;
use App\Entity\Categorie;
use App\Entity\Formation;
use App\Entity\Playlist;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


/**
 * Classe de fixtures pour l'application.
 * Permet de pré-remplir la base de données avec des données de test pour les entités User, Categorie, Formation et Playlist.
 * @author s.n
 */

class AppFixtures extends Fixture
{

    /**
     * Service de hachage des mots de passe, utilisé pour sécuriser le mot de passe de l'admin.
     *
     * @var UserPasswordHasherInterface
     */

    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    /**
     * Constructeur de la classe AppFixtures.
     *
     * @param UserPasswordHasherInterface $hasher Le service de hachage des mots de passe
     */


    public function load(ObjectManager $manager): void
    {
        // Création de l'utilisateur admin

        $user = new User();
        $user->setUsername('admin');
        $user->setRoles(
            ['ROLE_ADMIN']
        );
        $password = $this->hasher->hashPassword($user, 'pass_1234');
        $user->setPassword($password);

        $manager->persist($user);

        // Données pour les catégories, playlists et formations

        $names = ["Intelligence artificielle", "Développement web", "DevOps"];
        $categories = [];
        $listNames = ["Playlist IA", "Playlist Dev Web", "Playlist DevOps", "Playlist PHP"];
        $playlists = [];
        $formations = [];
        $formationsNames = ["IA", "Dev Web", "DevOps", "PHP", "Symfony", "React", "Docker", "Kubernetes", "AWS", "Azure"];
        $dates = [
            "2022-01-15",
            "2022-06-20",
            "2023-03-10",
            "2023-09-05",
            "2024-01-20",
            "2024-06-15",
            "2024-08-30",
            "2024-10-10",
            "2025-01-05",
            "2025-02-20"
        ];

        // Création des 3 catégories

        for ($i = 0; $i < 3; $i++) {
            $categorie = new Categorie();
            $categorie->setName($names[$i]);
            $categories[] = $categorie;
            $manager->persist($categorie);

        }

        // Création des 4 playlists

        for ($i = 0; $i < 4; $i++) {
            $playlist = new Playlist();
            $playlist->setName($listNames[$i]);
            $playlists[] = $playlist;
            $manager->persist($playlist);

        }

        // Création des 10 formations et association aux playlists et catégories
        // Chaque formation est associée à une playlist ($i % 4) et une catégorie ($i % 3)

        for ($i = 0; $i < 10; $i++) {
            $formation = new Formation();
            $formation->setTitle($formationsNames[$i]);
            $formation->setDescription("Description de la formation " . $i);
            $formation->setPublishedAt(new \DateTime($dates[$i]));
            $formation->setVideoId("AZWYX- " . $i);
            $formation->setPlaylist($playlists[$i % 4]);
            $formation->addCategory($categories[$i % 3]);
            $formations[] = $formation;
            $manager->persist($formation);
        }

        // Exécution de toutes les insertions en base de données

        $manager->flush();

    }
}
