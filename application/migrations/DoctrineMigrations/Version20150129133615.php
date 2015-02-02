<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150129133615 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
                  ALTER TABLE `es_messages`
                  CHANGE COLUMN `opened` `opened` TINYINT NULL DEFAULT '0' COMMENT '0 = unread msgs , 1 = read' ;
                ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
          ALTER TABLE `es_messages`
          CHANGE COLUMN `opened` `opened` ENUM('0','1') NULL DEFAULT '0' COMMENT '0 = unread msgs , 1 = read' ;
        ");
    }
}
