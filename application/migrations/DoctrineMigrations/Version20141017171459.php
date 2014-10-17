<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141017171459 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `es_member_prodcat` 
                       ADD COLUMN `createddate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
        $this->addSql("ALTER TABLE `es_member_cat` 
                       ADD COLUMN `createddate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `es_member_prodcat` DROP COLUMN `createddate`");
        $this->addSql("ALTER TABLE `es_member_cat` DROP COLUMN `createddate`");
    }
}
