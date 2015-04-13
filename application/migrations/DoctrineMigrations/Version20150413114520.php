<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150413114520 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE `es_student`
            CHANGE COLUMN `name` `name` VARCHAR(90) NULL DEFAULT NULL ;
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE `es_student`
            CHANGE COLUMN `name` `name` VARCHAR(45) NULL DEFAULT NULL ;
        ");
    }
}
