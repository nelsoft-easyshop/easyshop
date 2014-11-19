<?php


namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141119075334 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            INSERT INTO `es_social_media_provider` (`id_social_media_provider`, `name`) VALUES ('1', 'Facebook');
        ");
        $this->addSql("
            INSERT INTO `es_social_media_provider` (`id_social_media_provider`, `name`) VALUES ('2', 'Google');
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            DELETE FROM `es_social_media_provider` WHERE `id_social_media_provider`='1';
        ");
        $this->addSql("
            DELETE FROM `es_social_media_provider` WHERE `id_social_media_provider`='2';
        ");
    }
}
