<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140923160025 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("
            ALTER TABLE `es_member` 
            ADD COLUMN `support_email` VARCHAR(255) NULL DEFAULT '' AFTER `store_name`,
            ADD COLUMN `website` VARCHAR(255) NULL DEFAULT '' AFTER `support_email`;
            ");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("
            ALTER TABLE `es_member` 
            DROP COLUMN `website`,
            DROP COLUMN `support_email`;
            ");
    }
}
