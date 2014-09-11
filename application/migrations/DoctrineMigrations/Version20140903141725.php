<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140903141725 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("
            ALTER TABLE `es_payment_gateway` 
            ADD COLUMN `date_added` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `order_id`;");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("
            ALTER TABLE `es_payment_gateway` 
            DROP COLUMN `date_added`;");
    }
}
