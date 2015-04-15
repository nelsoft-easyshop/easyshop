<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150414174945 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE `es_member_prodcat`
            ADD COLUMN `lastmodifieddate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
       $this->addSql("
            ALTER TABLE `es_member_prodcat`
            DROP COLUMN `lastmodifieddate`
        ");

    }
}
