<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;
/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140904115831 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE es_promo ADD COLUMN `created_at` TIMESTAMP DEFAULT '0000-00-00 00:00:00'");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE es_promo DROP COLUMN `created_at`");
    }
}
