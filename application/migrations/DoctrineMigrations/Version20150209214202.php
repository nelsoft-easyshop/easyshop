<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150209214202 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE es_keeplogin DROP INDEX `UNIQUE PAIR`;
        ");
    }

    public function down(Schema $schema)
    {  
        $this->addSql("
            ALTER TABLE `es_keeplogin` ADD UNIQUE `UNIQUE PAIR`(`id_member`, `last_ip`, `useragent`);
        ");
    }
}
