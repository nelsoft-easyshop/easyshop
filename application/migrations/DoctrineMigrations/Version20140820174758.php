<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140820174758 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("
                    DROP TABLE IF EXISTS `es_webservice_user`;
                    CREATE TABLE `es_webservice_user` (
                      `id_user` INT NOT NULL AUTO_INCREMENT,
                      `username` VARCHAR(45) NOT NULL,
                      `password` VARCHAR(45) NOT NULL,
                      PRIMARY KEY (`id_user`));
                    ");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TABLE `es_webservice_user`;");
    }
}
