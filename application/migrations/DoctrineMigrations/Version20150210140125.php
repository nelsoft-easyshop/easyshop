<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150210140125 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE `es_product_external_link`
            ADD COLUMN `date_of_announcement` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `social_media_provider_id`;
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE `es_product_external_link`
            DROP COLUMN `date_of_announcement`;
        ");
    }
}
