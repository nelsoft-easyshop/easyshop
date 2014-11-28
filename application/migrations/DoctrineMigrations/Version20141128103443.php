<?php

namespace DoctrineMigrations;
use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141128103443 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql('ALTER TABLE `es_member` 
            ADD COLUMN `is_active` BOOL NULL DEFAULT 1;');

    }

    public function down(Schema $schema)
    {
         $this->addSql('ALTER TABLE `es_member` DROP `is_active`');

    }
}
