<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150311115727 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("
            ALTER TABLE `es_point` 
            DROP COLUMN `credit_point`;
        ");

        $this->addSql("
            CREATE TABLE `es_order_points` (
                `id_order_points` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `order_id` INT(10) UNSIGNED NOT NULL,
                `credit_points` INT(10) NOT NULL,
            PRIMARY KEY (`id_order_points`),
            INDEX `fk_es_order_points_1_idx` (`order_id` ASC),
            CONSTRAINT `fk_es_order_points_1`
                FOREIGN KEY (`order_id`)
                REFERENCES `es_order` (`id_order`)
                ON DELETE NO ACTION
                ON UPDATE NO ACTION);
        ");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("
            ALTER TABLE `es_point` 
            ADD COLUMN `credit_point` INT(10) UNSIGNED NOT NULL AFTER `point`;
        ");

        $this->addSql("
            DROP TABLE `es_order_points`;
        ");
    }
}
