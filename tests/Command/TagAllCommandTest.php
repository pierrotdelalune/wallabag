<?php

namespace App\Tests\Command;

use App\Tests\WallabagCoreTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Tester\CommandTester;

class TagAllCommandTest extends WallabagCoreTestCase
{
    public function testRunTagAllCommandWithoutUsername()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Not enough arguments (missing: "username")');

        $application = new Application($this->getTestClient()->getKernel());

        $command = $application->find('wallabag:tag:all');

        $tester = new CommandTester($command);
        $tester->execute([]);
    }

    public function testRunTagAllCommandWithBadUsername()
    {
        $application = new Application($this->getTestClient()->getKernel());

        $command = $application->find('wallabag:tag:all');

        $tester = new CommandTester($command);
        $tester->execute([
            'username' => 'unknown',
        ]);

        $this->assertStringContainsString('User "unknown" not found', $tester->getDisplay());
    }

    public function testRunTagAllCommand()
    {
        $application = new Application($this->getTestClient()->getKernel());

        $command = $application->find('wallabag:tag:all');

        $tester = new CommandTester($command);
        $tester->execute([
            'username' => 'admin',
        ]);

        $this->assertStringContainsString('Tagging entries for user admin...', $tester->getDisplay());
        $this->assertStringContainsString('Done', $tester->getDisplay());
    }
}
