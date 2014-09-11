<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140902105101 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `es_order_billing_info` CHANGE COLUMN `id_es_order_billing_info` `id_order_billing_info` INTEGER(10) NOT NULL AUTO_INCREMENT ;");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `es_order_billing_info` CHANGE COLUMN `id_es_order_billing_info` `id_order_billing_info` INTEGER(10) NOT NULL AUTO_INCREMENT ;");

    }
}
