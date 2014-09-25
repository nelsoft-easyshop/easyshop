<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140918112922 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("CREATE TABLE IF NOT EXISTS `es_member_cat` (
          `id_memcat` int(10) unsigned NOT NULL AUTO_INCREMENT,
          `member_id` int(10) unsigned NOT NULL,
          `cat_name` varchar(45) DEFAULT NULL,
          `is_featured` tinyint(4) NOT NULL DEFAULT '0',
          PRIMARY KEY (`id_memcat`),
          KEY `fk_es_member_cat_1_idx` (`member_id`),
          CONSTRAINT `fk_es_member_cat_1` FOREIGN KEY (`member_id`) REFERENCES `es_member` (`id_member`) ON DELETE NO ACTION ON UPDATE NO ACTION
        ) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;
        ");
        $this->addSql("CREATE TABLE IF NOT EXISTS `es_member_prodcat` (
          `id_memprod` int(10) unsigned NOT NULL AUTO_INCREMENT,
          `memcat_id` int(10) unsigned NOT NULL,
          `product_id` int(10) unsigned NOT NULL,
          PRIMARY KEY (`id_memprod`),
          KEY `fk_es_member_prodcat_1_idx` (`memcat_id`),
          KEY `fk_es_member_prodcat_2_idx` (`product_id`),
          CONSTRAINT `fk_es_member_prodcat_1` FOREIGN KEY (`memcat_id`) REFERENCES `es_member_cat` (`id_memcat`) ON DELETE NO ACTION ON UPDATE NO ACTION,
          CONSTRAINT `fk_es_member_prodcat_2` FOREIGN KEY (`product_id`) REFERENCES `es_product` (`id_product`) ON DELETE NO ACTION ON UPDATE NO ACTION
        ) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;
        ");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TABLE IF EXISTS `es_member_cat`");
        $this->addSql("DROP TABLE IF EXISTS `es_member_prodcat`");
    }
}
