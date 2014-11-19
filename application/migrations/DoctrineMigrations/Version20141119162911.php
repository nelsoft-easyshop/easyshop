<?php


namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141119162911 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE es_optional_attrdetail MODIFY value_price DECIMAL(15,4) NOT NULL DEFAULT '0.0000'
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE es_optional_attrdetail MODIFY value_price VARCHAR(45) NOT NULL DEFAULT '0'
        ");

    }
}
