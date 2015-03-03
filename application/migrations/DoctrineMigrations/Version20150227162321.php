<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150227162321 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            CREATE TABLE `es_feature_restrict` (
                `id_feature_restrict` int(11) NOT NULL,
                `name` varchar(45) NOT NULL,
                `max_user` int(11) NOT NULL,
                PRIMARY KEY (`id_feature_restrict`)
            )
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("DROP TABLE `es_feature_restrict`");
    }
}
