<?php

namespace App\Tests\Controller;
use App\Repository\FormationRepository;
use App\Repository\CategorieRepository;
use App\Repository\PlaylistRepository;
use App\Repository\UserRepository;
use symfony\Component\DomCrawler\Field\FormField;
use symfony\Component\DomCrawler\Link;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class AdminFormationsTest extends WebTestCase
{
    private const FORMATION_HOMEPAGE = '/admin/formations';

    public function getAdmin()
    {
        $client = static::createClient();
        $adminUser = static::getContainer()->get(UserRepository::class)->findOneBy(['username' => 'admin']);
        $client->loginUser($adminUser);
        return $client;
    }

    public function testAdminFormations(): void
    {
        $client = $this->getAdmin();

        $client->request('GET', self::FORMATION_HOMEPAGE);

        $this->assertResponseIsSuccessful();


    }

    public function testAdminFormationSort()
    {
        $client = $this->getAdmin();
        $client->request('GET', '/admin/formations/tri/title/ASC');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h5', 'AWS');

    }

    public function testAdminFormationSortDateDesc()
    {
        $client = $this->getAdmin();
        $client->request('GET', '/admin/formations/tri/publishedAt/DESC');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h5', 'Azure');

    }

    public function testAdminFormationFiltreTitle()
    {
        $client = $this->getAdmin();
        $client->request('POST', self::FORMATION_HOMEPAGE);
        $crawler = $client->request(
            'POST',
            '/admin/formations/recherche/title',
            ['recherche' => 'Docker']
        );
        $this->assertCount(1, $crawler->filter('h5'));
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h5', 'Docker');

    }

    public function testAdminFormationFiltrePlaylist()
    {
        $client = $this->getAdmin();
        $client->request('POST', '/admin/formations/recherche/name/playlist', [
            'recherche' => 'Playlist PHP',
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertCount(2, $client->getCrawler()->filter('h5'));
        $this->assertSelectorTextContains('h5', 'Kubernetes');
    }

    public function testAdminFormationCategorie()
    {
        $client = $this->getAdmin();
        $categorieRepository = static::getContainer()->get(CategorieRepository::class);
        $categorie = $categorieRepository->findOneBy(['name' => 'DevOps']);
        $categorieId = $categorie->getId();
        $client->request('POST', '/admin/formations/recherche/id/categories', ['recherche' => $categorieId]);
        $this->assertResponseIsSuccessful();
        $this->assertCount(3, $client->getCrawler()->filter('h5'));
        $this->assertSelectorTextContains('h5', 'AWS');
    }

    public function testAdminFormationShowOne()
    {
        $client = $this->getAdmin();
        $formationRepository = static::getContainer()->get(FormationRepository::class);
        $formation = $formationRepository->findOneBy(['title' => 'Symfony']);
        $formationId = $formation->getId();
        $client->request('GET', "/admin/formations/formation/$formationId");
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h4', 'Symfony');
    }

    public function testAdminFormationAdd(): void
    {
        $client = $this->getAdmin();
        $client->request('GET', self::FORMATION_HOMEPAGE);
        $form = $client->getCrawler()->filter('.btn-success')->form();
        $client->submit($form);
        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('admin.formations.add');
        $this->assertSelectorTextContains('.btn-danger', 'Cancel');
    }

    public function testAdminFormationEdit(): void
    {
        $client = $this->getAdmin();
        $client->request('GET', self::FORMATION_HOMEPAGE);
        $this->assertResponseIsSuccessful();
        $form = $client->getCrawler()->filter('.btn-warning')->form();
        $client->submit($form);
        $this->assertRouteSame('admin.formations.edit');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.btn-danger', 'Cancel');
    }

    public function testAdminFormationRemove(): void
    {
        $client = $this->getAdmin();
        $crawler = $client->request('GET', self::FORMATION_HOMEPAGE);


        $form = $crawler->filter('.btn-danger')->form();
        $client->submit($form);

        $this->assertResponseRedirects(self::FORMATION_HOMEPAGE);
    }


}
