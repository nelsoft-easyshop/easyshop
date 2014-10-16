<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141016165139 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("CREATE TABLE `es_queue` (
          `id_queue` int(10) NOT NULL AUTO_INCREMENT,
          `data` longtext NOT NULL,
          `type` smallint(6) NOT NULL,
          `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
          `date_executed` datetime DEFAULT NULL,
          `status` smallint(6) NOT NULL DEFAULT '1',
          PRIMARY KEY (`id_queue`),
          KEY `fk_es_queue_type_idx` (`type`),
          KEY `fk_es_queue_status_idx` (`status`),
          CONSTRAINT `fk_es_queue_status` FOREIGN KEY (`status`) REFERENCES `es_queue_status` (`id_status`) ON DELETE NO ACTION ON UPDATE NO ACTION,
          CONSTRAINT `fk_es_queue_type` FOREIGN KEY (`type`) REFERENCES `es_queue_type` (`id_type`) ON DELETE NO ACTION ON UPDATE NO ACTION
        ) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
        ");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TABLE `es_queue`");
    }
}
