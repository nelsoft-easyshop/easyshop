<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141218110338 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("
            CREATE TABLE `es_product_history_view` (
                `id_product_history_view` INT NOT NULL AUTO_INCREMENT,
                `product_id` INT UNSIGNED NULL,
                `member_id` INT UNSIGNED NULL,
                `ip_address` VARCHAR(25) NULL,
                `date_viewed` TIMESTAMP NULL,
                PRIMARY KEY (`id_product_history_view`),
                INDEX `fk_es_product_history_view_1_idx` (`product_id` ASC),
                INDEX `fk_es_product_history_view_1_idx1` (`member_id` ASC),
            CONSTRAINT `product_key`
                FOREIGN KEY (`product_id`)
                REFERENCES `es_product` (`id_product`)
                ON DELETE NO ACTION
                ON UPDATE NO ACTION,
            CONSTRAINT `member_key`
                FOREIGN KEY (`member_id`)
                REFERENCES `es_member` (`id_member`)
                ON DELETE NO ACTION
                ON UPDATE NO ACTION);
        ");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("
            DROP TABLE `es_product_history_view`;
        ");
    }
}
