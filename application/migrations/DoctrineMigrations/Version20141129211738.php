<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;


class Version20141129211738 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE `es_member` 
            ADD COLUMN `store_color_id` INT(11) NOT NULL DEFAULT '1';
        ");
        $this->addSql("
            ALTER TABLE `es_member`
            ADD CONSTRAINT fk_es_store_color_es_member
            FOREIGN KEY (store_color_id)
            REFERENCES es_store_color(id_store_color)
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE `es_member` 
            DROP COLUMN `store_color_id`;
        ");
    }
}
