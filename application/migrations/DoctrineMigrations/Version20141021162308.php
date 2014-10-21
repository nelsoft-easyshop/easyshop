<?php


namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;


/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141021162308 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("ALTER TABLE `oauth_scopes` 
                ADD COLUMN `id_oauth_scope` int NOT NULL PRIMARY KEY");

    }

    public function down(Schema $schema)
    {
        
        $this->addSql("ALTER TABLE `oauth_scopes` 
                DROP COLUMN `id_oauth_scope`");

    }
}
