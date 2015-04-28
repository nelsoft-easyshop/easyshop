<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150409123151 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("
            CREATE TABLE `es_order_points` (
                `id_order_points` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `order_product_id` INT(10) UNSIGNED NULL,
                `points` DECIMAL(15,4) UNSIGNED NULL,
            PRIMARY KEY (`id_order_points`),
            INDEX `fk_es_order_points_1_idx` (`order_product_id` ASC),
            CONSTRAINT `fk_es_order_points_1`
                FOREIGN KEY (`order_product_id`)
                REFERENCES `es_order_product` (`id_order_product`)
                ON DELETE NO ACTION
                ON UPDATE NO ACTION);
        ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("
            DROP TABLE `es_order_points`;
        ");
    }
}
