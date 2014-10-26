<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141024202911 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("
            CREATE TABLE `es_vendor_subscribe_history` (
                `id_vendor_subscribe_history` INT NOT NULL,
                `member_id` INT(11) UNSIGNED NULL,
                `vendor_id` INT(11) UNSIGNED NULL,
                `action` VARCHAR(45) NOT NULL,
                `timestamp` DATETIME NULL,
            PRIMARY KEY (`id_vendor_subscribe_history`),
            INDEX `fk_es_vendor_subscribe_member_id_idx` (`member_id` ASC),
            INDEX `fk_es_vendor_subscribe_vendor_id_idx` (`vendor_id` ASC),
            CONSTRAINT `fk_es_vendor_subscribe_member_id`
                FOREIGN KEY (`member_id`)
                REFERENCES `es_member` (`id_member`)
                ON DELETE NO ACTION
                ON UPDATE NO ACTION,
            CONSTRAINT `fk_es_vendor_subscribe_vendor_id`
                FOREIGN KEY (`vendor_id`)
                REFERENCES `es_member` (`id_member`)
                ON DELETE NO ACTION
                ON UPDATE NO ACTION);
        ");

        $this->addSql("
            ALTER TABLE `es_vendor_subscribe_history` 
            CHANGE COLUMN `id_vendor_subscribe_history` `id_vendor_subscribe_history` INT(11) NOT NULL AUTO_INCREMENT ;
        ");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("
            DROP TABLE `es_vendor_subscribe_history`;
        ");
    }
}
