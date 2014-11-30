<?php


namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;


class Version20141130180913 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
           CREATE TABLE `es_category_nested_set` (
            `id_category_nested_set` int(11) NOT NULL AUTO_INCREMENT,
            `category_id` int(10) unsigned NOT NULL,
            `left` int(11) NOT NULL DEFAULT '0',
            `right` int(11) NOT NULL DEFAULT '0',
            PRIMARY KEY (`id_category_nested_set`),
            KEY `fk_es_category_nested_set_es_cat_idx` (`category_id`),
            CONSTRAINT `fk_es_category_nested_set_es_cat` FOREIGN KEY (`category_id`) REFERENCES `es_cat` (`id_cat`) ON DELETE NO ACTION ON UPDATE NO ACTION
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
           DROP TABLE es_category_nested_set
        ");
    }
}
