<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141024232846 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("
            CREATE TABLE `es_tag_type` (
                `id_tag_type` INT NOT NULL AUTO_INCREMENT,
                `tag_description` VARCHAR(45) NOT NULL,
                PRIMARY KEY (`id_tag_type`));
            ");

        $this->addSql("
            INSERT INTO `es_tag_type` (`tag_description`) VALUES ('CONTACTED');
            INSERT INTO `es_tag_type` (`tag_description`) VALUES ('REFUND');
            INSERT INTO `es_tag_type` (`tag_description`) VALUES ('ON-HOLD');
            INSERT INTO `es_tag_type` (`tag_description`) VALUES ('PAYOUT');
        ");

        $this->addSql("
            CREATE TABLE `es_order_product_tag` (
            `id_order_product_tag` INT NOT NULL AUTO_INCREMENT,
            `order_product_id` INT(11) UNSIGNED NULL,
            `tag_type_id` INT(11) NULL,
            `date_updated` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            `admin_member_id` INT(11) NULL,
            PRIMARY KEY (`id_order_product_tag`),
            INDEX `fk_es_order_product_tag_1_idx` (`tag_type_id` ASC),
            INDEX `fk_es_order_product_tag_order_product_idx` (`order_product_id` ASC),
            INDEX `fk_es_order_product_tag_admin_member_id_idx` (`admin_member_id` ASC),
            CONSTRAINT `fk_es_order_product_tag_type`
                FOREIGN KEY (`tag_type_id`)
                REFERENCES `es_tag_type` (`id_tag_type`)
                ON DELETE NO ACTION
                ON UPDATE NO ACTION,
            CONSTRAINT `fk_es_order_product_tag_order_product`
                FOREIGN KEY (`order_product_id`)
                REFERENCES `es_order_product` (`id_order_product`)
                ON DELETE NO ACTION
                ON UPDATE NO ACTION,
            CONSTRAINT `fk_es_order_product_tag_admin_member_id`
                FOREIGN KEY (`admin_member_id`)
                REFERENCES `es_admin_member` (`id_admin_member`)
                ON DELETE NO ACTION
                ON UPDATE NO ACTION); 
        ");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("
            DROP TABLE `es_tag_type`;
        ");

        $this->addSql("
            DROP TABLE `es_order_product_tag`;
        ");
    }
}
