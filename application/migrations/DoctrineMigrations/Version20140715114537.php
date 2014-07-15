<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;


/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140715114537 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(" ALTER TABLE `easyshop`.`es_member`
                        ADD COLUMN TABLE `is_promo_valid` TINYINT(3) DEFAULT 0 NOT NULL AFTER `store_desc`;");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `easyshop`.`es_member`
                        DROP COLUMN `is_promo_valid`;");
    }
}
