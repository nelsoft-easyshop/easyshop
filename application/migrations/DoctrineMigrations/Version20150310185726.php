<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;


/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150310185726 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql('
            ALTER TABLE `es_member_feedback` modify COLUMN `feedb_msg` VARCHAR(255)
        ');

    }

    public function down(Schema $schema)
    {
        $this->addSql('
            ALTER TABLE `es_member_feedback` modify COLUMN `feedb_msg` VARCHAR(45)
        ');
    }
}
