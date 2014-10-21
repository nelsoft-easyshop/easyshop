<?php


namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;


/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141021160716 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("ALTER TABLE `es_order_billing_info` 
                ADD COLUMN `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
        $this->addSql("ALTER TABLE `es_order_billing_info` 
                ADD COLUMN `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");

    }

    public function down(Schema $schema)
    {
        $this->addSql("ALTER TABLE `es_order_billing_info` 
                DROP COLUMN `created_at`");
        $this->addSql("ALTER TABLE `es_order_billing_info` 
                DROP COLUMN `updated_at`");

    }
}
