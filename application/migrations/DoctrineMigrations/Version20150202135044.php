<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150202135044 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
        CREATE TABLE `es_student` (
        `id_student` INT NOT NULL AUTO_INCREMENT,
        `name` VARCHAR(45) NULL,
        `school_id` INT(11) NULL,
        PRIMARY KEY (`id_student`));
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("DROP TABLE `es_student`");
    }
}
