<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141028153101 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("
            ALTER TABLE `es_order_product_tag` 
            ADD COLUMN `seller_id` INT(11) UNSIGNED NULL AFTER `order_product_id`,
            ADD INDEX `fk_es_order_product_tag_seller_id_idx` (`seller_id` ASC);
            ALTER TABLE `es_order_product_tag` 
            ADD CONSTRAINT `fk_es_order_product_tag_seller_id`
                FOREIGN KEY (`seller_id`)
                REFERENCES `es_member` (`id_member`)
                ON DELETE NO ACTION
                ON UPDATE NO ACTION;
        ");

        $this->addSql("
            INSERT INTO `es_tag_type` (`id_tag_type`, `tag_description`) VALUES ('5', 'CONFIRMED');
        ");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("
            ALTER TABLE `es_order_product_tag` 
            DROP FOREIGN KEY `fk_es_order_product_tag_seller_id`;
            ALTER TABLE `es_order_product_tag` 
            DROP COLUMN `seller_id`,
            DROP INDEX `fk_es_order_product_tag_seller_id_idx` ;
        ");

        $this->addSql("
            DELETE FROM `es_tag_type` WHERE `id_tag_type`='5';
        ");
    }
}
