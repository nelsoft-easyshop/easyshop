<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150225130207 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
                        ALTER TABLE `es_product_shipping_comment`
                        CHANGE COLUMN `expected_date` `expected_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' ;
                    ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
                        ALTER TABLE `es_product_shipping_comment`
                        CHANGE COLUMN `expected_date` `expected_date` DATETIME NOT NULL DEFAULT;
                    ");
    }
}
