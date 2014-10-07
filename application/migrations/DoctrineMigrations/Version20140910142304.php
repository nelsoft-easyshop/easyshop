<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140910142304 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("
            CREATE TABLE `es_failed_login_history` (
            `id_failed_login` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            `login_username` VARCHAR(255) NOT NULL DEFAULT '',
            `login_ip` VARCHAR(45) NOT NULL DEFAULT '',
            `login_datetime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id_failed_login`));
        ");

        $this->addSql("
            ALTER TABLE `es_member` 
            ADD COLUMN `failed_login_count` INT(10) UNSIGNED NULL DEFAULT '0' AFTER `slug`,
            ADD COLUMN `last_failed_login_datetime` DATETIME NULL DEFAULT CURRENT_TIMESTAMP AFTER `failed_login_count`;
        ");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TABLE `es_failed_login_history`;");
        
        $this->addSql("
            ALTER TABLE `es_member` 
            DROP COLUMN `last_failed_login_datetime`,
            DROP COLUMN `failed_login_count`;
            ");
    }
}
