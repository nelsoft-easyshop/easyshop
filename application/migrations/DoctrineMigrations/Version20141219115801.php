<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;


class Version20141219115801 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE `es_keywords` 
            ADD COLUMN `occurences` INT(11) NOT NULL DEFAULT 0
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE `es_keywords` 
            DROP COLUMN `occurences`
        ");
    }
    
}

