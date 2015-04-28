<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150405155742 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("ALTER TABLE `es_activity_history` DROP COLUMN `activity_string`");
        $this->addSql("ALTER TABLE `es_activity_history` ADD COLUMN `json_data` TEXT");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("ALTER TABLE `es_activity_history` ADD COLUMN `activity_string` TEXT DEFAULT ''");
        $this->addSql("ALTER TABLE `es_activity_history` DROP COLUMN `json_data`");
    }
}
