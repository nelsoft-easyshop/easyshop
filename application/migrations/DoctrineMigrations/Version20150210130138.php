<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150210130138 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE `es_product_external_link`
            CHANGE COLUMN `link` `link` VARCHAR(500) NOT NULL DEFAULT '' ;
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE `es_product_external_link`
            CHANGE COLUMN `link` `link` VARCHAR(45) NOT NULL DEFAULT '' ;
        ");
    }
}
