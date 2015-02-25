<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150224063550 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE `easyshop`.`es_member_cat` 
            ADD COLUMN `is_delete` TINYINT(4) NOT NULL DEFAULT '0' AFTER `sort_order`,
            ADD COLUMN `last_date_modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `is_delete`;
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE `easyshop`.`es_member_cat`
            DROP COLUMN `is_delete`,
            DROP COLUMN `last_date_modified`;
        ");
    }
}
