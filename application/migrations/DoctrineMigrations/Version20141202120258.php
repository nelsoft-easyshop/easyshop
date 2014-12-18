<?php


namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141202120258 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("
            ALTER TABLE `es_activity_history` 
            ADD COLUMN `member_id` INT(11) UNSIGNED NOT NULL AFTER `activity_datetime`,
            ADD INDEX `member_id_idx` (`member_id` ASC);
            ALTER TABLE `es_activity_history` 
            ADD CONSTRAINT `member_id`
                FOREIGN KEY (`member_id`)
                REFERENCES `es_member` (`id_member`)
                ON DELETE NO ACTION
                ON UPDATE NO ACTION;
        ");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("
            ALTER TABLE `es_activity_history` 
                DROP FOREIGN KEY `member_id`;
                ALTER TABLE `es_activity_history` 
                DROP COLUMN `member_id`,
                DROP INDEX `member_id_idx` ;
        ");
    }
}
