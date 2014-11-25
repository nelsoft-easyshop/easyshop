<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141125204835 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("
            ALTER TABLE `es_member` 
            CHANGE COLUMN `oauth_id` `oauth_id` VARCHAR(45) NOT NULL DEFAULT '0' ,
            CHANGE COLUMN `oauth_provider` `oauth_provider` VARCHAR(45) NOT NULL DEFAULT '' ;
        ");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("
            ALTER TABLE `es_member` 
            CHANGE COLUMN `oauth_id` `oauth_id` VARCHAR(45) NULL DEFAULT '0' ,
            CHANGE COLUMN `oauth_provider` `oauth_provider` VARCHAR(45) NULL DEFAULT '' ;
        ");
    }
}
