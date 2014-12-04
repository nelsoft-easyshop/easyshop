<?php

namespace ;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141204204004 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE `es_member_cat` 
            ADD COLUMN `sort_order` TINYINT(5) NOT NULL DEFAULT '0';
        ");         
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE `es_member_cat` 
            DROP COLUMN `sort_order`;
        ");     
    }
}
