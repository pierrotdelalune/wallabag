<?php

namespace DoctrineMigrations;

use App\Doctrine\WallabagMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Add store_article_headers in craue_config_setting.
 */
class Version20171120163128 extends WallabagMigration
{
    public function up(Schema $schema): void
    {
        $storeArticleHeaders = $this->container
            ->get('doctrine.orm.default_entity_manager')
            ->getConnection()
            ->fetchOne('SELECT * FROM ' . $this->getTable('craue_config_setting') . " WHERE name = 'store_article_headers'");

        $this->skipIf(false !== $storeArticleHeaders, 'It seems that you already played this migration.');

        $this->addSql('INSERT INTO ' . $this->getTable('craue_config_setting') . " (name, value, section) VALUES ('store_article_headers', '0', 'entry')");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM ' . $this->getTable('craue_config_setting') . " WHERE name = 'store_article_headers';");
    }
}
