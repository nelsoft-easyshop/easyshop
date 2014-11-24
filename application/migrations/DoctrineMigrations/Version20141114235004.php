<?php


namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141114235004 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("ALTER TABLE es_product CHANGE `shipped_within_count` `ships_within_days` TINYINT(5)");

    }

    public function down(Schema $schema)
    {
        $this->addSql("ALTER TABLE es_product CHANGE  `ships_within_days` `shipped_within_count` TINYINT(5)");

    }
}
