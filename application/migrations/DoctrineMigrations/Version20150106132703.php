<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150106132703 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("
            CREATE TABLE `es_search_topic` (
                `id_search_topic` int(11) NOT NULL AUTO_INCREMENT,
                `topic` varchar(45) DEFAULT NULL,
                `category` int(10) unsigned DEFAULT NULL,
                `weight` decimal(10,4) DEFAULT '0',
                PRIMARY KEY (`id_search_topic`),
                KEY `fk_es_topic_table_1_idx` (`category`),
                CONSTRAINT `fk_es_topic_table_1` FOREIGN KEY (`category`) REFERENCES `es_cat` (`id_cat`) ON DELETE NO ACTION ON UPDATE NO ACTION
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
        ");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("
            DROP table `es_search_topic`
        ");
    }
}

