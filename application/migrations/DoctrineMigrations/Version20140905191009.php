<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140905191009 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("ALTER TABLE es_member ADD COLUMN `store_name` VARCHAR(1024) NULL");
    }

    public function down(Schema $schema)
    {
        $this->addSql("ALTER TABLE es_member DROP COLUMN `store_name`");
    }
}
