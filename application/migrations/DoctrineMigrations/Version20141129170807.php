<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141129170807 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("
            CREATE TABLE `es_activity_type`(  
              `id_activity_type` INT(10) NOT NULL AUTO_INCREMENT,
              `activity_description` VARCHAR(100),
              PRIMARY KEY (`id_activity_type`)
            );

        ");

        $this->addSql("
            CREATE TABLE `es_activity_history`(  
              `id_activity_history` INT(10) NOT NULL AUTO_INCREMENT,
              `activity_type_id` INT NOT NULL,
              `activity_string` TEXT,
              `activity_datetime` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
              PRIMARY KEY (`id_activity_history`),
              INDEX `activity_id` (`activity_type_id`),
              CONSTRAINT `activity_id` FOREIGN KEY (`activity_type_id`) REFERENCES `es_activity_type`(`id_activity_type`)
            );
        ");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("
            DROP `es_activity_type`;
        ");

        $this->addSql("
            DROP `es_activity_history`;
        ");
    }
}
