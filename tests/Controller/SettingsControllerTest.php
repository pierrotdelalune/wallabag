<?php

namespace App\Tests\Controller;

use App\Tests\WallabagCoreTestCase;

/**
 * The controller `SettingsController` does not exist.
 * This test cover security against the internal settings page managed by CraueConfigBundle.
 */
class SettingsControllerTest extends WallabagCoreTestCase
{
    public function testSettingsWithAdmin()
    {
        $this->logInAs('admin');
        $client = $this->getTestClient();

        $crawler = $client->request('GET', '/settings');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    public function testSettingsWithNormalUser()
    {
        $this->logInAs('bob');
        $client = $this->getTestClient();

        $crawler = $client->request('GET', '/settings');

        $this->assertSame(403, $client->getResponse()->getStatusCode());
    }
}
