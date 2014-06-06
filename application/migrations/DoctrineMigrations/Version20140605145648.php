<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * 
 */
class Version20140605145648 extends AbstractMigration
{
    /**
     * Add `ON UPDATE CASCADE` constraint
     * 
     * @param \Doctrine\DBAL\Schema\Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql(
        "CREATE TABLE IF NOT EXISTS `es_authenticated_session` (
            `member_id` int(10) unsigned NOT NULL,
            `session_id` varchar(40) NOT NULL DEFAULT '0',
            PRIMARY KEY (`member_id`,`session_id`),
            KEY `IDX_AC8D08427597D3FE` (`member_id`),
            KEY `IDX_AC8D0842613FECDF` (`session_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        $this->addSql("ALTER TABLE es_authenticated_session ADD CONSTRAINT FK_AC8D08427597D3FE FOREIGN KEY (member_id) REFERENCES es_member (id_member) ON UPDATE CASCADE ON DELETE CASCADE");
        $this->addSql("ALTER TABLE es_authenticated_session ADD CONSTRAINT FK_AC8D0842613FECDF FOREIGN KEY (session_id) REFERENCES ci_sessions (session_id) ON UPDATE CASCADE ON DELETE CASCADE");
    }

    /**
     * Remove `ON UPDATE CASCADE` constraint
     * 
     * @param \Doctrine\DBAL\Schema\Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("DROP TABLE IF EXISTS `es_authenticated_session`;");
    }
}
