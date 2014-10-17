<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141016185916 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP PROCEDURE IF EXISTS `es_sp_Signup_user`");
        $this->addSql("
CREATE DEFINER=`root`@`localhost` PROCEDURE `es_sp_Signup_user`(
                IN i_username VARCHAR(255),
                IN i_password VARCHAR(255),
                IN i_contactno VARCHAR(45),
                IN i_email VARCHAR(255),
                IN i_fullname VARCHAR(1024),
                IN i_datenow DATETIME
            )
BEGIN
                DECLARE v_pass VARCHAR(255);
                DECLARE v_memberid VARCHAR(255);

                START TRANSACTION;
                
                SELECT reverse(PASSWORD(concat(md5(i_username),sha1(i_password)))) into v_pass;

                SELECT id_member INTO v_memberid FROM `es_member` WHERE BINARY `username` = i_username OR `slug` = i_username;

                IF v_memberid IS NULL THEN

                    INSERT INTO `es_member` (`username`, `password`, `contactno`, `email`, `datecreated`, `slug`, fullname)
                    VALUES (i_username, v_pass, i_contactno, i_email, i_datenow, i_username, i_fullname);

                END IF;

                COMMIT;

                SELECT id_member FROM es_member WHERE username = i_username; 

            END
");
        $this->addSql("DROP PROCEDURE IF EXISTS `es_sp_Login_user`");
        $this->addSql("
CREATE DEFINER=`root`@`localhost` PROCEDURE `es_sp_Login_user`(
            #IN i_username VARCHAR(255),
            IN i_login VARCHAR(255),
            IN i_password  VARCHAR(255),
            in i_ip varchar(255)
               )
BEGIN
            DECLARE o_success BOOLEAN;
            DECLARE o_memberid VARCHAR(50); 
            DECLARE o_session VARCHAR(150); 
            DECLARE o_message VARCHAR(50); 
            declare v_encpass varchar(250);
            
            DECLARE i_username VARCHAR(255);
            
            DECLARE EXIT HANDLER FOR SQLEXCEPTION
              BEGIN
                  ROLLBACK;
               SELECT o_success AS o_success, o_memberid AS o_memberid, o_session AS o_session, o_message AS o_message;
              END;
              
            DECLARE EXIT HANDLER FOR NOT FOUND
              BEGIN
                  ROLLBACK;
               SELECT o_success AS o_success, o_memberid AS o_memberid, o_session AS o_session, o_message AS o_message;
              END;
              
            START TRANSACTION;
                
                SET o_success = FALSE;  
                SET o_message = 'Invalid Username / Password';
                
                #Retrieve username based on email OR verify username if exists
                SELECT `username` INTO i_username FROM es_member WHERE `email` = i_login OR BINARY `username` = i_login LIMIT 1;

                ## Select if exist
                select reverse(PASSword(concat(md5(i_username),sha1(i_password)))) into v_encpass;
                SELECT id_member INTO o_memberid FROM `es_member` WHERE BINARY username = i_username AND PASSWORD = v_encpass; 
            
            IF o_memberid IS NOT NULL THEN 
                        SELECT SHA1(o_memberid + NOW()) INTO o_session;
                        UPDATE `es_member` SET usersession = o_session, `login_count` = `login_count` + 1 , `last_login_ip` = i_ip ,`last_login_datetime` = NOW()  WHERE id_member = o_memberid;
                        SET o_success = TRUE;
                        set o_message = '';
            ELSE
                SET o_success = FALSE;
            END IF;   
                   
                COMMIT;
                SELECT o_success AS o_success, o_memberid AS o_memberid, o_session AS o_session, o_message AS o_message;
        END
");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP PROCEDURE IF EXISTS `es_sp_Signup_user`");
        $this->addSql("
CREATE DEFINER=`root`@`localhost` PROCEDURE `es_sp_Signup_user`(
                IN i_username VARCHAR(255),
                IN i_password VARCHAR(255),
                IN i_contactno VARCHAR(45),
                IN i_email VARCHAR(255),
                IN i_fullname VARCHAR(1024),
                IN i_datenow DATETIME
            )
BEGIN
                DECLARE v_pass VARCHAR(255);
                DECLARE v_memberid VARCHAR(255);

                START TRANSACTION;
                
                SELECT reverse(PASSWORD(concat(md5(i_username),sha1(i_password)))) into v_pass;

                SELECT id_member INTO v_memberid FROM `es_member` WHERE `username` = i_username OR `slug` = i_username;

                IF v_memberid IS NULL THEN

                    INSERT INTO `es_member` (`username`, `password`, `contactno`, `email`, `datecreated`, `slug`, fullname)
                    VALUES (i_username, v_pass, i_contactno, i_email, i_datenow, i_username, i_fullname);

                END IF;

                COMMIT;

                SELECT id_member FROM es_member WHERE username = i_username; 

            END
");
        $this->addSql("DROP PROCEDURE IF EXISTS `es_sp_Login_user`");
        $this->addSql("
CREATE DEFINER=`root`@`localhost` PROCEDURE `es_sp_Login_user`(
            #IN i_username VARCHAR(255),
            IN i_login VARCHAR(255),
            IN i_password  VARCHAR(255),
            in i_ip varchar(255)
               )
BEGIN
            DECLARE o_success BOOLEAN;
            DECLARE o_memberid VARCHAR(50); 
            DECLARE o_session VARCHAR(150); 
            DECLARE o_message VARCHAR(50); 
            declare v_encpass varchar(250);
            
            DECLARE i_username VARCHAR(255);
            
            DECLARE EXIT HANDLER FOR SQLEXCEPTION
              BEGIN
                  ROLLBACK;
               SELECT o_success AS o_success, o_memberid AS o_memberid, o_session AS o_session, o_message AS o_message;
              END;
              
            DECLARE EXIT HANDLER FOR NOT FOUND
              BEGIN
                  ROLLBACK;
               SELECT o_success AS o_success, o_memberid AS o_memberid, o_session AS o_session, o_message AS o_message;
              END;
              
            START TRANSACTION;
                
                SET o_success = FALSE;  
                SET o_message = 'Invalid Username / Password';
                
                #Retrieve username based on email OR verify username if exists
                SELECT `username` INTO i_username FROM es_member WHERE `email` = i_login OR `username` = i_login LIMIT 1;

                ## Select if exist
                select reverse(PASSword(concat(md5(i_username),sha1(i_password)))) into v_encpass;
                SELECT id_member INTO o_memberid FROM `es_member` WHERE username = i_username AND PASSWORD = v_encpass; 
            
            IF o_memberid IS NOT NULL THEN 
                        SELECT SHA1(o_memberid + NOW()) INTO o_session;
                        UPDATE `es_member` SET usersession = o_session, `login_count` = `login_count` + 1 , `last_login_ip` = i_ip ,`last_login_datetime` = NOW()  WHERE id_member = o_memberid;
                        SET o_success = TRUE;
                        set o_message = '';
            ELSE
                SET o_success = FALSE;
            END IF;   
                   
                COMMIT;
                SELECT o_success AS o_success, o_memberid AS o_memberid, o_session AS o_session, o_message AS o_message;
        END
");
    }
}
