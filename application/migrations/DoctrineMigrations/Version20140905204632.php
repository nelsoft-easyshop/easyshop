<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;
/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140905204632 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("DROP TABLE es_authenticated_session");
        $this->addSql("CREATE TABLE `es_authenticated_session` (
            `id_authenticated_session` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `member_id` int(10) unsigned NOT NULL,
            `session_id` varchar(40) NOT NULL DEFAULT '0',
            PRIMARY KEY (`id_authenticated_session`),
            KEY `IDX_AC8D08427597D3FE` (`member_id`),
            KEY `IDX_AC8D0842613FECDF` (`session_id`),
            KEY `PRIMARY_KEY` (`id_authenticated_session`),
            CONSTRAINT `FK_AC8D0842613FECDF` FOREIGN KEY (`session_id`) REFERENCES `ci_sessions` (`session_id`) ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT `FK_AC8D08427597D3FE` FOREIGN KEY (`member_id`) REFERENCES `es_member` (`id_member`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");
     
    }

    public function down(Schema $schema)
    {
        $this->addSql("DROP TABLE es_authenticated_session");
        $this->addSql("CREATE TABLE `es_authenticated_session` (
              `member_id` int(10) unsigned NOT NULL,
              `session_id` varchar(40) NOT NULL DEFAULT '0',
              PRIMARY KEY (`member_id`,`session_id`),
              KEY `IDX_AC8D08427597D3FE` (`member_id`),
              KEY `IDX_AC8D0842613FECDF` (`session_id`),
              CONSTRAINT `FK_AC8D0842613FECDF` FOREIGN KEY (`session_id`) REFERENCES `ci_sessions` (`session_id`) ON DELETE CASCADE ON UPDATE CASCADE,
              CONSTRAINT `FK_AC8D08427597D3FE` FOREIGN KEY (`member_id`) REFERENCES `es_member` (`id_member`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

    }
}
