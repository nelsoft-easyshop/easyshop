<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150316141840 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql('
            INSERT INTO es_feature_restrict (id_feature_restrict, name, max_user) VALUES (1, "Real Time Chat", 20);
        ');
    }

    public function down(Schema $schema)
    {
        $this->addSql('
            DELETE FROM es_feature_restrict WHERE `id_feature-restrict` = "1";
        ');
    }
}
