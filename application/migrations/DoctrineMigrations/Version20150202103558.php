<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150202103558 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE `es_school`
            DROP COLUMN `count`,
            DROP COLUMN `level`,
            DROP COLUMN `year`,
            DROP COLUMN `id_member`,
            DROP COLUMN `id`,
            CHANGE COLUMN `schoolname` `id_school` INT NOT NULL ,
            ADD COLUMN `name` VARCHAR(45) NULL DEFAULT '' AFTER `id_school`,
            DROP PRIMARY KEY,
            ADD PRIMARY KEY (`id_school`);
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE `es_school`
            CHANGE COLUMN `id_school` `id` INT(11) NOT NULL AUTO_INCREMENT ,
            CHANGE COLUMN `name` `id_member` INT(10) NULL ,
            ADD COLUMN `schoolname` VARCHAR(45) NULL AFTER `id_member`,
            ADD COLUMN `year` VARCHAR(45) NULL AFTER `schoolname`,
            ADD COLUMN `level` VARCHAR(45) NULL AFTER `year`,
            ADD COLUMN `count` TINYINT NULL AFTER `level`;
        ");
    }
}
