<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;


/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150112115528 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE `es_member` 
            ADD COLUMN `is_banned` TINYINT(5) NOT NULL DEFAULT '0';
        ");    
        $this->addSql("
            ALTER TABLE `es_member` 
            ADD COLUMN `ban_type` INT(10) NOT NULL DEFAULT '0';
        ");    
        $this->addSql("
            CREATE TABLE `es_ban_type` (
            `id_ban_type` int(10) NOT NULL,
            `title` varchar(45) NOT NULL DEFAULT '',
            `message` varchar(255) NOT NULL DEFAULT '',
            PRIMARY KEY (`id_ban_type`)
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
        ");   
        $this->addSql("
            INSERT INTO es_ban_type (`id_ban_type`, `title`,`message`) VALUES ('0', 'Not banned', '')
        ");   
        $this->addSql("
            INSERT INTO es_ban_type (`id_ban_type`, `title`,`message`) VALUES ('1', 'Paypal Dispute', 'This account has temporarily been suspended due to an ongoing investigation arising from a PayPal dispute. The suspension will be lifted after the case has been closed.')
        ");   
         $this->addSql("
            ALTER TABLE es_member
            ADD CONSTRAINT fk_es_member_es_ban_type
            FOREIGN KEY (ban_type)
            REFERENCES es_ban_type(id_ban_type)
        ");

    }

    public function down(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE `es_member` 
            DROP COLUMN `is_banned`;
        ");    
        $this->addSql("
            ALTER TABLE `es_member` 
            DROP COLUMN `ban_type` ;
        ");    
        $this->addSql("
            DROP TABLE `es_ban_type`;
        ");   
    }
}
