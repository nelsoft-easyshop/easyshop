<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140902112623 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("ALTER TABLE `es_admin_member` CHANGE COLUMN `id_admin` `id_admin_member` INTEGER(10) NOT NULL AUTO_INCREMENT ; ");

    }

    public function down(Schema $schema)
    {
        $this->addSql("ALTER TABLE `es_admin_member` CHANGE COLUMN `id_admin_member` `id_admin` INTEGER(10) NOT NULL AUTO_INCREMENT ; ");

    }
}
