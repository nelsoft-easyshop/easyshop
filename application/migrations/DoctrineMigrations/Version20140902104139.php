<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140902104139 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("ALTER TABLE es_order_product_history ADD COLUMN `created_at` TIMESTAMP DEFAULT '0000-00-00 00:00:00'");
        $this->addSql("ALTER TABLE es_order_product_history ADD COLUMN `updated_at` TIMESTAMP DEFAULT '0000-00-00 00:00:00'");

    }

    public function down(Schema $schema)
    {
        $this->addSql("ALTER TABLE es_order_product_history ADD COLUMN `created_at` TIMESTAMP DEFAULT '0000-00-00 00:00:00'");
        $this->addSql("ALTER TABLE es_order_product_history ADD COLUMN `updated_at` TIMESTAMP DEFAULT '0000-00-00 00:00:00'");

    }
}
