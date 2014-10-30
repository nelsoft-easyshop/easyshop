<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141029160356 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("
            ALTER TABLE `es_tag_type` 
            ADD COLUMN `tag_color` VARCHAR(45) NULL AFTER `tag_description`; 
        ");

        $this->addSql("
            UPDATE `es_tag_type` SET `tag_color`='#159818' WHERE `id_tag_type`='1';
            UPDATE `es_tag_type` SET `tag_color`='#fc2929' WHERE `id_tag_type`='2';
            UPDATE `es_tag_type` SET `tag_color`='#eb6420' WHERE `id_tag_type`='3';
            UPDATE `es_tag_type` SET `tag_color`='#eb6420' WHERE `id_tag_type`='4';
            UPDATE `es_tag_type` SET `tag_color`='#84b6eb' WHERE `id_tag_type`='5';
        ");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("
            ALTER TABLE `es_tag_type` 
            DROP COLUMN `tag_color`; 
        ");
    }
}
