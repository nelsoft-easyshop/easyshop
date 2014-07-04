<?php

namespace ;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140704100328 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
		$this->addSql('CREATE TABLE `es_vendor_subscribe` (
			  `id_vendor_subscribe` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `member_id` int(10) unsigned DEFAULT NULL,
			  `vendor_id` int(10) unsigned DEFAULT NULL,
			  PRIMARY KEY (`id_vendor_subscribe`)
			) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
		');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
		$this->addSql('DROP TABLE `es_vendor_subscribe`');
    }
}
