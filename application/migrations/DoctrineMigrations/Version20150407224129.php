<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150407224129 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("
            INSERT INTO `es_activity_type` (`id_activity_type`, `activity_description`, `activity_phrase`) VALUES ('5', 'vendor subscription', 'vendor_subscription');
        ");

        $this->addSql("
            ALTER TABLE `es_member` 
            ADD COLUMN `last_banner_changed` DATETIME NULL DEFAULT CURRENT_TIMESTAMP AFTER `temp_id`,
            ADD COLUMN `last_avatar_changed` DATETIME NULL DEFAULT CURRENT_TIMESTAMP AFTER `last_banner_changed`;
        ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("
            DELETE FROM `es_activity_type` WHERE `id_activity_type`='5';
        ");

        $this->addSql("
            ALTER TABLE `es_member` 
            DROP COLUMN `last_avatar_changed`,
            DROP COLUMN `last_banner_changed`;
        ");
    }
}
