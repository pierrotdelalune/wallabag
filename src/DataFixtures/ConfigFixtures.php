<?php

namespace App\DataFixtures;

use App\Entity\Config;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ConfigFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager): void
    {
        $adminConfig = new Config($this->getReference('admin-user'));

        $adminConfig->setItemsPerPage(30);
        $adminConfig->setReadingSpeed(200);
        $adminConfig->setLanguage('en');
        $adminConfig->setPocketConsumerKey('xxxxx');
        $adminConfig->setActionMarkAsRead(0);
        $adminConfig->setListMode(0);
        $adminConfig->setDisplayThumbnails(0);

        $manager->persist($adminConfig);

        $this->addReference('admin-config', $adminConfig);

        $bobConfig = new Config($this->getReference('bob-user'));
        $bobConfig->setItemsPerPage(10);
        $bobConfig->setReadingSpeed(200);
        $bobConfig->setLanguage('fr');
        $bobConfig->setPocketConsumerKey(null);
        $bobConfig->setActionMarkAsRead(1);
        $bobConfig->setListMode(1);
        $bobConfig->setDisplayThumbnails(1);

        $manager->persist($bobConfig);

        $this->addReference('bob-config', $bobConfig);

        $emptyConfig = new Config($this->getReference('empty-user'));
        $emptyConfig->setItemsPerPage(10);
        $emptyConfig->setReadingSpeed(100);
        $emptyConfig->setLanguage('en');
        $emptyConfig->setPocketConsumerKey(null);
        $emptyConfig->setActionMarkAsRead(0);
        $emptyConfig->setListMode(0);
        $emptyConfig->setDisplayThumbnails(0);

        $manager->persist($emptyConfig);

        $this->addReference('empty-config', $emptyConfig);

        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
