<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;


/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140819122050 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
    	$this->addSql("
    		CREATE TABLE `es_point` (
  				`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  				`m_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
  				`point` INT UNSIGNED NOT NULL DEFAULT '0',
  				PRIMARY KEY (`id`),
  				INDEX `fk_es_point_m_id_idx` (`m_id` ASC),
  				CONSTRAINT `fk_es_point_m_id`
    				FOREIGN KEY (`m_id`)
    				REFERENCES `es_member` (`id_member`)
    				ON DELETE RESTRICT
    				ON UPDATE CASCADE);");

    	$this->addSql("
    		CREATE TABLE `es_point_type` (
  				`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  				`name` VARCHAR(255) NOT NULL DEFAULT '',
  				`point` INT UNSIGNED NOT NULL DEFAULT '0',
  				PRIMARY KEY (`id`));");

    	$this->addSql("
    		CREATE TABLE `es_point_history` (
  				`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  				`m_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
  				`date_added` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  				`point` INT(10) UNSIGNED NOT NULL DEFAULT '0',
  				`type` INT(10) UNSIGNED NOT NULL DEFAULT '0',
  				PRIMARY KEY (`id`),
  				INDEX `fk_es_point_history_m_id_idx` (`m_id` ASC),
  				INDEX `fk_es_point_history_pt_id_idx` (`type` ASC),
  				CONSTRAINT `fk_es_point_history_m_id`
    				FOREIGN KEY (`m_id`)
    				REFERENCES `es_member` (`id_member`)
    				ON DELETE RESTRICT
    				ON UPDATE CASCADE,
  				CONSTRAINT `fk_es_point_history_pt_id`
    				FOREIGN KEY (`type`)
					REFERENCES `es_point_type` (`id`)
					ON DELETE RESTRICT
					ON UPDATE CASCADE);");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
    	$this->addSql("DROP TABLE `es_point`;");
    	$this->addSql("DROP TABLE `es_point_type`;");
    	$this->addSql("DROP TABLE `es_point_history`;");
    }
}
