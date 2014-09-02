<?php


namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140902120935 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("DROP PROCEDURE IF EXISTS `es_sp_Signup_admin`;");
        $this->addSql(" 
            CREATE DEFINER=`root`@`localhost` PROCEDURE `es_sp_Signup_admin`(
                IN i_username VARCHAR(255),
                IN i_password VARCHAR(255),
                IN i_fullname VARCHAR(255))
            BEGIN
                DECLARE v_pass VARCHAR(255);
                
                START TRANSACTION;
                
                SELECT REVERSE(PASSWORD(CONCAT(MD5(i_username),SHA1(i_password)))) INTO v_pass;
                INSERT INTO `es_admin_member` (`username`, `password`,`fullname`)
                VALUES (i_username, v_pass, i_fullname)
                ON DUPLICATE KEY UPDATE username=i_username, `password`=v_pass, fullname=i_fullname;
                COMMIT;
                SELECT id_admin_member FROM `es_admin_member` WHERE username = i_username; 
            END ");

    }

    public function down(Schema $schema)
    {
        $this->addSql("DROP PROCEDURE IF EXISTS `es_sp_Signup_admin`;");
        $this->addSql(" 
            CREATE DEFINER=`root`@`localhost` PROCEDURE `es_sp_Signup_admin`(
                IN i_username VARCHAR(255),
                IN i_password VARCHAR(255),
                IN i_fullname VARCHAR(255))
            BEGIN
                DECLARE v_pass VARCHAR(255);
                
                START TRANSACTION;
                
                SELECT REVERSE(PASSWORD(CONCAT(MD5(i_username),SHA1(i_password)))) INTO v_pass;
                INSERT INTO `es_admin_member` (`username`, `password`,`fullname`)
                VALUES (i_username, v_pass, i_fullname)
                ON DUPLICATE KEY UPDATE username=i_username, `password`=v_pass, fullname=i_fullname;
                COMMIT;
                SELECT id_admin FROM `es_admin_member` WHERE username = i_username; 
            END ");


    }
}
