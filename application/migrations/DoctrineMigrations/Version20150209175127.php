<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150209175127 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            CREATE TABLE `es_product_external_link` (
            `id_product_external_link` INT NOT NULL AUTO_INCREMENT,
            `link` VARCHAR(45) NOT NULL DEFAULT '',
            `product_id` INT NOT NULL DEFAULT 0,
            `social_media_provider_id` INT NOT NULL DEFAULT 0,
            PRIMARY KEY (`id_product_external_link`));
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("DROP TABLE es_product_external_link");
    }
}
