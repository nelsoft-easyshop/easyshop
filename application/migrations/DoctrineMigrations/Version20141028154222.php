<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141028154222 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("CREATE TABLE `es_social_media_provider` (
                      `id_social_media_provider` INT NOT NULL AUTO_INCREMENT,
                      `name` VARCHAR(45) NOT NULL,
                      PRIMARY KEY (`id_social_media_provider`))
                      ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("DROP TABLE `es_socialmedia_provider`");
    }
}
