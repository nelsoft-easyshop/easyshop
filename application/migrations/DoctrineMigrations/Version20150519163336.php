<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150519163336 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('INSERT INTO es_ban_type (id_ban_type, title, message) VALUES (4, "Exessive Period of Inactivity", "This account has temporarily been suspended due to extended periods of inactivity")'); 
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('DELETE FROM es_ban_type WHERE id_ban_type = 4'); 
    }
}
