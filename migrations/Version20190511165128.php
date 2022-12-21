<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Doctrine\WallabagMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Convert tab label to utf8mb4_bin (MySQL only).
 */
final class Version20190511165128 extends WallabagMigration
{
    public function up(Schema $schema): void
    {
        $this->skipIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'This migration only apply to MySQL');

        $this->addSql('ALTER TABLE ' . $this->getTable('tag') . ' CHANGE `label` `label` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin;');
        $this->addSql('ALTER TABLE ' . $this->getTable('tag') . ' CHANGE `slug` `slug` VARCHAR(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin;');
    }

    public function down(Schema $schema): void
    {
        $this->skipIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'This migration only apply to MySQL');

        $this->addSql('ALTER TABLE ' . $this->getTable('tag') . ' CHANGE `slug` `slug` VARCHAR(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
        $this->addSql('ALTER TABLE ' . $this->getTable('tag') . ' CHANGE `label` `label` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
    }
}