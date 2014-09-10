<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140826175517 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("
            ALTER TABLE `es_point_history` 
            ADD COLUMN `data` VARCHAR(1024) NOT NULL DEFAULT '' AFTER `type`;");

        $this->addSql("
            CREATE TABLE `es_payment_gateway` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `payment_method_id` INT(11) NOT NULL DEFAULT '0',
                `amount` DECIMAL(15,4) NULL DEFAULT '0.0000',
                `order_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
                PRIMARY KEY (`id`),
                INDEX `fk_es_point_gateway_order_id_idx` (`order_id` ASC),
                INDEX `fk_es_point_gateway_payment_method_idx` (`payment_method_id` ASC),
                CONSTRAINT `fk_es_point_gateway_payment_method`
                    FOREIGN KEY (`payment_method_id`)
                    REFERENCES `es_payment_method` (`id_payment_method`)
                    ON DELETE RESTRICT
                    ON UPDATE CASCADE,
                CONSTRAINT `fk_es_point_gateway_order_id`
                    FOREIGN KEY (`order_id`)
                    REFERENCES `es_order` (`id_order`)
                    ON DELETE RESTRICT
                    ON UPDATE CASCADE);");

            $this->addSql("
                ALTER TABLE `es_point_type` 
                CHANGE COLUMN `point` `point` INT(10) NOT NULL DEFAULT '0' ;");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("
            ALTER TABLE `es_point_history` 
            DROP COLUMN `data`;");

        $this->addSql("
            DROP TABLE `es_payment_gateway`;");

        $this->addSql("
            ALTER TABLE `es_point_type` 
            CHANGE COLUMN `point` `point` INT(10) UNSIGNED NOT NULL DEFAULT '0' ;");
    }
}
