<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150202105836 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE `es_promo`
            ADD COLUMN `student_id` INT(11) NULL DEFAULT '0' AFTER `code`;
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE `es_promo`
            DROP COLUMN `student_id`;
        ");
    }
}
