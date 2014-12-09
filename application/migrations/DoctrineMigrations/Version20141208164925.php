<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141208164925 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql('
            ALTER TABLE `es_order_product_history`
            DROP FOREIGN KEY `fk_es_order_product_history_es_order_product`,
            DROP FOREIGN KEY `fk_es_order_product_status_es_order_product_history`;
            ALTER TABLE `es_order_product_history`
            CHANGE COLUMN `order_product_id` `order_product_id` INT(10) NOT NULL ,
            DROP INDEX `fk_es_order_product_status_es_order_product_history_idx` ,
            DROP INDEX `UNIQUE`;
        ');
    }

    public function down(Schema $schema)
    {
        $this->addSql('
            ALTER TABLE `es_order_product_history`
            CHANGE COLUMN `order_product_id` `order_product_id` INT(10) UNSIGNED NOT NULL ,
            ADD UNIQUE INDEX `UNIQUE` (`order_product_id` ASC, `order_product_status` ASC),
            ADD INDEX `fk_es_order_product_status_es_order_product_history_idx` (`order_product_status` ASC);
            ALTER TABLE `es_order_product_history`
            ADD CONSTRAINT `fk_es_order_product_status_es_order_product_history`
            FOREIGN KEY (`order_product_status`)
            REFERENCES `es_order_product_status` (`id_order_product_status`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION,
            ADD CONSTRAINT `fk_es_order_product_history_es_order_product`
            FOREIGN KEY (`order_product_id`)
            REFERENCES `es_order_product` (`id_order_product`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION;
        ');
    }
}
