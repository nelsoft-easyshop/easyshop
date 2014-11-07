<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141105191005 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("
            ALTER TABLE `es_keywords_temp` 
            ADD COLUMN `ip_address` VARCHAR(20) NOT NULL DEFAULT '' AFTER `keywords`,
            ADD COLUMN `timestamp` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP AFTER `ip_address`;
        ");

    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("
            ALTER TABLE `es_keywords_temp` 
            DROP COLUMN `timestamp`,
            DROP COLUMN `ip_address`;
        ");

    }
}
