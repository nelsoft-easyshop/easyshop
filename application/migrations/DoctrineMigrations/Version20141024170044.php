<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141024170044 extends AbstractMigration
{
    public function up(Schema $schema)
    { 
        $this->addSql("ALTER TABLE `es_member` 
                ADD FULLTEXT INDEX `ft_store_name_idx` (`store_name` ASC)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `es_member` 
                DROP INDEX `ft_store_name_idx`");
    }
}
