<?php

namespace App\DataFixtures;


use App\Entity\User;
use App\Entity\Categorie;
use App\Entity\Formation;
use App\Entity\Playlist;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;




class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    // ...


    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername('admin');
        $user->setRoles(
            ['ROLE_ADMIN']
        );
        $password = $this->hasher->hashPassword($user, 'pass_1234');
        $user->setPassword($password);

        $manager->persist($user);


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


        for ($i = 0; $i < 3; $i++) {
            $categorie = new Categorie();
            $categorie->setName($names[$i]);
            $categories[] = $categorie;
            $manager->persist($categorie);

        }

        for ($i = 0; $i < 4; $i++) {
            $playlist = new Playlist();
            $playlist->setName($listNames[$i]);
            $playlists[] = $playlist;
            $manager->persist($playlist);

        }

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

        $manager->flush();

    }
}
