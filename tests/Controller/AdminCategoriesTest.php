<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminCategoriesTest extends WebTestCase
{
    private const CATEGORIE_HOMEPAGE = "/admin/categories";
    private function getAdmin()
    {
        $client = static::createClient();
        $adminUser = static::getContainer()
            ->get(UserRepository::class)
            ->findOneBy(['username' => 'admin']);
        $client->loginUser($adminUser);
        return $client;
    }

    public function testAdminCategories(): void
    {
        $client = $this->getAdmin();
        $client->request('GET', self::CATEGORIE_HOMEPAGE);
        $this->assertResponseIsSuccessful();
        $this->assertCount(3, $client->getCrawler()->filter('h5'));
    }

    public function testAdminCategorieAdd(): void
    {
        $client = $this->getAdmin();
        $crawler = $client->request('GET', self::CATEGORIE_HOMEPAGE);
        $form = $crawler->filter('.btn-success')->form([
            'name' => 'New Categorie',
        ]);
        $client->submit($form);
        $this->assertResponseRedirects(self::CATEGORIE_HOMEPAGE);
        $client->followRedirect();
        $this->assertAnySelectorTextContains('h5', 'New Categorie');

    }

    public function testAdminCategorieRemove(): void
    {
        $client = $this->getAdmin();
        $crawler = $client->request('GET', self::CATEGORIE_HOMEPAGE);
        $form = $crawler->filter('.btn-danger')->form();
        $client->submit($form);
        $this->assertResponseRedirects(self::CATEGORIE_HOMEPAGE);
    }
}
