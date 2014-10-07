<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140910104405 extends AbstractMigration
{
    public function up(Schema $schema)
    {

        $this->addSql("
            CREATE TABLE `es_raffle` (
              `raffle_id` int(11) NOT NULL AUTO_INCREMENT,
              `raffle_name` varchar(60) DEFAULT NULL,
              `winners` varchar(255) DEFAULT NULL,
              `members` longtext,
              `prices` longtext,
              `updated_at` timestamp NULL DEFAULT NULL,
              `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
              `is_active` tinyint(7) DEFAULT '1',
              PRIMARY KEY (`raffle_id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8;

            ");

    }

    public function down(Schema $schema)
    {
        $this->addSql('DROP TABLE `es_raffle`');
    }
}
