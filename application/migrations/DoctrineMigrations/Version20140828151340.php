<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140828151340 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `es_promo` (
                  `id_promo` INT NOT NULL AUTO_INCREMENT,
                  `member_id` INT NULL DEFAULT 0,
                  `product_id` INT NULL DEFAULT 0,
                  `code` VARCHAR(45) NULL DEFAULT 0,
                  PRIMARY KEY (`id_promo`));
		');

    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE `es_promo`');
    }
}
