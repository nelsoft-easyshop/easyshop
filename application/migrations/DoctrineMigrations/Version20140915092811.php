<?php

namespace ;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140915092811 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("ALTER TABLE `es_raffle` CHANGE COLUMN `winners` LONGTEXT; ");

    }

    public function down(Schema $schema)
    {
        $this->addSql("ALTER TABLE `es_raffle` CHANGE COLUMN `winners` VARCHAR(255) DEFAULT NULL; ");

    }
}
