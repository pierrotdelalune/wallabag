<?php

namespace App\Tests\Controller\Import;

use App\Tests\WallabagCoreTestCase;

class ImportControllerTest extends WallabagCoreTestCase
{
    public function testLogin()
    {
        $client = $this->getTestClient();

        $client->request('GET', '/import');

        $this->assertSame(302, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('login', $client->getResponse()->headers->get('location'));
    }

    public function testImportList()
    {
        $this->logInAs('admin');
        $client = $this->getTestClient();

        $crawler = $client->request('GET', '/import');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(10, $crawler->filter('blockquote')->count());
    }
}