<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140920170228 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("
            ALTER TABLE `es_product` 
            ADD COLUMN `max_allowable_point` DECIMAL(15,4) NULL DEFAULT '0.0000' AFTER `is_meetup`;
            ");

    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("
            ALTER TABLE `es_product` 
            DROP COLUMN `max_allowable_point`;
            ");
    }
}
