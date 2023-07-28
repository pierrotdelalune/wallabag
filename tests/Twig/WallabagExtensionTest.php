<?php

namespace App\Tests\Twig;

use App\Repository\EntryRepository;
use App\Repository\TagRepository;
use App\Twig\WallabagExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class WallabagExtensionTest extends TestCase
{
    public function testRemoveWww()
    {
        $entryRepository = $this->getMockBuilder(EntryRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $tagRepository = $this->getMockBuilder(TagRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $tokenStorage = $this->getMockBuilder(TokenStorageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $translator = $this->getMockBuilder(TranslatorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $extension = new WallabagExtension($entryRepository, $tagRepository, $tokenStorage, 0, $translator, '');

        $this->assertSame('lemonde.fr', $extension->removeWww('www.lemonde.fr'));
        $this->assertSame('lemonde.fr', $extension->removeWww('lemonde.fr'));
        $this->assertSame('gist.github.com', $extension->removeWww('gist.github.com'));
    }

    public function testRemoveScheme()
    {
        $entryRepository = $this->getMockBuilder(EntryRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $tagRepository = $this->getMockBuilder(TagRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $tokenStorage = $this->getMockBuilder(TokenStorageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $translator = $this->getMockBuilder(TranslatorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $extension = new WallabagExtension($entryRepository, $tagRepository, $tokenStorage, 0, $translator, '');

        $this->assertSame('lemonde.fr', $extension->removeScheme('lemonde.fr'));
        $this->assertSame('gist.github.com', $extension->removeScheme('gist.github.com'));
        $this->assertSame('gist.github.com', $extension->removeScheme('https://gist.github.com'));
    }

    public function testRemoveSchemeAndWww()
    {
        $entryRepository = $this->getMockBuilder(EntryRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $tagRepository = $this->getMockBuilder(TagRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $tokenStorage = $this->getMockBuilder(TokenStorageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $translator = $this->getMockBuilder(TranslatorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $extension = new WallabagExtension($entryRepository, $tagRepository, $tokenStorage, 0, $translator, '');

        $this->assertSame('lemonde.fr', $extension->removeSchemeAndWww('www.lemonde.fr'));
        $this->assertSame('lemonde.fr', $extension->removeSchemeAndWww('http://lemonde.fr'));
        $this->assertSame('lemonde.fr', $extension->removeSchemeAndWww('https://www.lemonde.fr'));
        $this->assertSame('gist.github.com', $extension->removeSchemeAndWww('https://gist.github.com'));
        $this->assertSame('ftp://gist.github.com', $extension->removeSchemeAndWww('ftp://gist.github.com'));
    }
}
