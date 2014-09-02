<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;


/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140902110218 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            CREATE TABLE `es_admin_member_role` (
                `id_role` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `role_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
                `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
                PRIMARY KEY (`id_role`)
            ) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ");

    }

    public function down(Schema $schema)
    {
        $this->addSql("DROP TABLE IF EXISTS `es_admin_member_role`;");

    }
}
