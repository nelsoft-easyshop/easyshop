<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140915095249 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("
                ALTER TABLE `es_member`
                ADD COLUMN `oauth_id`  VARCHAR(45) NULL DEFAULT '0',
                ADD COLUMN `oauth_provider` VARCHAR(45) NULL DEFAULT '';
            ");

    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("
                ALTER TABLE `es_member`
                DROP COLUMN `oauth_provider`,
                DROP COLUMN `oauth_id`;
            ");

    }
}
