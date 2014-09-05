<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;
/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140820195536 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            CREATE TABLE `easyshop`.`es_promo` (
                `id_promo` INT NOT NULL AUTO_INCREMENT,
                `cat_id` INT NOT NULL,
                `code` VARCHAR(45) NOT NULL,
            PRIMARY KEY (`id_promo`))
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("DROP TABLE `easyshop`.`es_promo`");

    }
}
