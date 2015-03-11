<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150311144556 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql('
            ALTER TABLE `es_member_prodcat` ADD COLUMN `sort_order` INT(11) NOT NULL DEFAULT 0;
        ');
    }

    public function down(Schema $schema)
    {
        $this->addSql('
            ALTER TABLE `es_member_prodcat` DROP COLUMN `sort_order`;
        ');
    }
}
