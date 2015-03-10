<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150303202112 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql('
            CREATE TABLE `es_api_type` (
                `id_api_type` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `api_type` VARCHAR(45) NOT NULL,
                PRIMARY KEY (`id_api_type`));
        ');

        $this->addSql("
            INSERT INTO `es_api_type` (`id_api_type`, `api_type`) VALUES ('1', 'IOS');
            INSERT INTO `es_api_type` (`id_api_type`, `api_type`) VALUES ('2', 'ANDROID');
        ");

        $this->addSql("
            CREATE TABLE `es_device_token` (
                `id_device_token` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `device_token` VARCHAR(300) NOT NULL,
                `api_type` INT UNSIGNED NOT NULL,
                `is_active` TINYINT(1) NOT NULL,
            PRIMARY KEY (`id_device_token`),
            INDEX `fk_es_device_token_1_idx` (`api_type` ASC),
            CONSTRAINT `fk_es_device_token_1`
                FOREIGN KEY (`api_type`)
                REFERENCES `es_api_type` (`id_api_type`)
                ON DELETE NO ACTION
                ON UPDATE NO ACTION);
        ");

        $this->addSql("
            ALTER TABLE `es_device_token` 
            ADD COLUMN `dateadded` DATETIME NULL DEFAULT CURRENT_TIMESTAMP AFTER `is_active`;
        ");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("
            DROP TABLE `es_device_token`;
        ");

        $this->addSql("
            DROP TABLE `es_api_type`;
        ");
    }
}
