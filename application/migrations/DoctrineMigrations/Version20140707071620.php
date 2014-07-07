<?php


namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140707071620 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
		ALTER TABLE easyshop.es_member MODIFY store_desc VARCHAR(1024) DEFAULT '';
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
		ALTER TABLE easyshop.es_member MODIFY store_desc TEXT;
    }
}
