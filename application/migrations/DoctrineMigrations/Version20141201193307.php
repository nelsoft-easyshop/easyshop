<?php


namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141201193307 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("
            ALTER TABLE `es_activity_type` 
                ADD COLUMN `activity_phrase` TEXT NULL AFTER `activity_description`;
        ");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("
            ALTER TABLE `es_activity_type` 
                DROP COLUMN `activity_phrase`;
        ");
    }
}
