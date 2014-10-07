<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140908140223 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("
            ALTER TABLE `es_point` 
            DROP FOREIGN KEY `fk_es_point_m_id`;
            ALTER TABLE `es_point` 
            CHANGE COLUMN `m_id` `member_id` INT(10) UNSIGNED NOT NULL DEFAULT '0' ;
            ALTER TABLE `es_point` 
            ADD CONSTRAINT `fk_es_point_m_id`
                FOREIGN KEY (`member_id`)
                REFERENCES `es_member` (`id_member`)
                ON UPDATE CASCADE;");

        $this->addSql("
            ALTER TABLE `es_point_history` 
            DROP FOREIGN KEY `fk_es_point_history_m_id`;
            ALTER TABLE `es_point_history` 
            CHANGE COLUMN `m_id` `member_id` INT(10) UNSIGNED NOT NULL DEFAULT '0' ;
            ALTER TABLE `es_point_history` 
            ADD CONSTRAINT `fk_es_point_history_m_id`
                FOREIGN KEY (`member_id`)
                REFERENCES `es_member` (`id_member`)
                ON UPDATE CASCADE;
            ");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("
            ALTER TABLE `es_point` 
            DROP FOREIGN KEY `fk_es_point_m_id`;
            ALTER TABLE `es_point` 
            CHANGE COLUMN `member_id` `m_id` INT(10) UNSIGNED NOT NULL DEFAULT '0' ;
            ALTER TABLE `es_point` 
            ADD CONSTRAINT `fk_es_point_m_id`
                FOREIGN KEY (`m_id`)
                REFERENCES `es_member` (`id_member`)
                ON UPDATE CASCADE;
            ");

        $this->addSql("
            ALTER TABLE `es_point_history` 
            DROP FOREIGN KEY `fk_es_point_history_m_id`;
            ALTER TABLE `es_point_history` 
            CHANGE COLUMN `member_id` `m_id` INT(10) UNSIGNED NOT NULL DEFAULT '0' ;
            ALTER TABLE `es_point_history` 
            ADD CONSTRAINT `fk_es_point_history_m_id`
                FOREIGN KEY (`m_id`)
                REFERENCES `es_member` (`id_member`)
                ON UPDATE CASCADE;
            ");
    }
}
