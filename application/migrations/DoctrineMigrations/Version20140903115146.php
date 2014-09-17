<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140903115146 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("ALTER TABLE es_admin_member ADD COLUMN `is_active` TINYINT(1) DEFAULT 1");
    }

    public function down(Schema $schema)
    {
        $this->addSql("ALTER TABLE es_admin_member DROP COLUMN `is_active`");
    }
}
