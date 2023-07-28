<?php

namespace DoctrineMigrations;

use App\Doctrine\WallabagMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Added the internal setting to enable/disable downloading pictures.
 */
class Version20161031132655 extends WallabagMigration
{
    public function up(Schema $schema): void
    {
        $images = $this->container
            ->get('doctrine.orm.default_entity_manager')
            ->getConnection()
            ->fetchOne('SELECT * FROM ' . $this->getTable('craue_config_setting') . " WHERE name = 'download_images_enabled'");

        $this->skipIf(false !== $images, 'It seems that you already played this migration.');

        $this->addSql('INSERT INTO ' . $this->getTable('craue_config_setting') . " (name, value, section) VALUES ('download_images_enabled', 0, 'misc')");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM ' . $this->getTable('craue_config_setting') . " WHERE name = 'download_images_enabled';");
    }
}
