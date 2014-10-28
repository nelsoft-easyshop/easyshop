<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141028154829 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("CREATE TABLE `es_socialmedia_provider` (
                      `id_socialmedia_provider` INT NOT NULL AUTO_INCREMENT,
                      `name` VARCHAR(45) NOT NULL,
                      PRIMARY KEY (`id_socialmedia_provider`))
                      ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("DROP TABLE `es_socialmedia_provider`");
    }
}
