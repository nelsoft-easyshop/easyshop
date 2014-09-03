<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140902103345 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("ALTER TABLE es_admin_member ADD COLUMN `created_at` TIMESTAMP DEFAULT '0000-00-00 00:00:00'");
        $this->addSql("ALTER TABLE es_admin_member ADD COLUMN `updated_at` TIMESTAMP DEFAULT '0000-00-00 00:00:00'");
        $this->addSql("ALTER TABLE es_admin_member ADD COLUMN `remember_token` VARCHAR(100)");
        $this->addSql("ALTER TABLE es_admin_member ADD COLUMN `role_id` INTEGER(10) DEFAULT 1");
        $this->addSql("ALTER TABLE es_admin_member ADD COLUMN `is_promo_valid` TINYINT(3) DEFAULT 0");
    }

    public function down(Schema $schema)
    {
        $this->addSql("ALTER TABLE es_admin_member DROP COLUMN");
        $this->addSql("ALTER TABLE es_admin_member DROP COLUMN");
        $this->addSql("ALTER TABLE es_admin_member DROP COLUMN");
        $this->addSql("ALTER TABLE es_admin_member DROP COLUMN");
        $this->addSql("ALTER TABLE es_admin_member DROP COLUMN");
    }
}
