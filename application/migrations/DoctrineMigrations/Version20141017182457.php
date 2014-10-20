<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141017182457 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("
            CREATE TABLE `oauth_token_lookup` (
              `access_token` VARCHAR(40) NOT NULL,
              `refresh_token` VARCHAR(40) NOT NULL,
              `client_id` VARCHAR(80) NOT NULL,
              `client_secret` VARCHAR(80) NOT NULL,
              PRIMARY KEY (`access_token`));
        ");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("
            DROP TABLE `oauth_token_lookup`;
        ");
    }
}

