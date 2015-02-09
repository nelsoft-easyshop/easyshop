<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150202152414 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE `es_student`
            ADD INDEX `fk_es_student_1_idx` (`school_id` ASC);
            ALTER TABLE `es_student`
            ADD CONSTRAINT `fk_es_student_1`
              FOREIGN KEY (`school_id`)
              REFERENCES `es_school` (`id_school`)
              ON DELETE NO ACTION
              ON UPDATE NO ACTION;
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE `es_student`
            DROP FOREIGN KEY `fk_es_student_1`;
            ALTER TABLE `es_student`
            DROP INDEX `fk_es_student_1_idx` ;
        ");
    }
}
