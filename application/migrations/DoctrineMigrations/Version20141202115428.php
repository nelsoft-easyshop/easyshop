<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141202115428 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE `es_member` 
            CHANGE COLUMN `is_active` `is_active` TINYINT(5) NOT NULL DEFAULT '1';
        ");            

    }

    public function down(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE `es_member` 
            CHANGE COLUMN `is_active` `is_active` TINYINT(5) NOT NULL DEFAULT '0';
        ");             


    }
}
