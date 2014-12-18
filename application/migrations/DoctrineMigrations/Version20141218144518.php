<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141218144518 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("CREATE TABLE `es_promo_type` (
                      `id_promo_type` INT NOT NULL,
                      `promo_name` VARCHAR(45) NULL DEFAULT '',
                      PRIMARY KEY (`id_promo_type`));");
    }

    public function down(Schema $schema)
    {
        $this->addSql("DROP TABLE `es_promo_type`");
    }
}
