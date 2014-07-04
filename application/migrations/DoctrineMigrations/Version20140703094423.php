<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140703094423 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql('ALTER TABLE `easyshop`.`es_member` 
ADD COLUMN `store_desc` TEXT NULL DEFAULT NULL AFTER `is_admin`;');

    }

    public function down(Schema $schema)
    {
         $this->addSql('ALTER TABLE `easyshop`.`es_member` DROP `store_desc`');

    }
}
