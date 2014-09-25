<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140925145810 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP PROCEDURE IF EXISTS `es_sp_SocialMediaLogin_user`;");
        $this->addSql("
            CREATE DEFINER=`root`@`localhost` PROCEDURE `es_sp_SocialMediaLogin_user`(
                        #IN i_username VARCHAR(255),
                        IN i_login VARCHAR(255),
                        IN i_oauthId VARCHAR(255),
                        IN i_oauthProvider VARCHAR(255),
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
                SELECT id_member INTO o_memberid FROM `es_member` WHERE username = i_username AND oauth_id = i_oauthId AND oauth_provider = i_oauthProvider;

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
        $this->addSql("DROP PROCEDURE IF EXISTS `es_sp_SocialMediaLogin_user`;");
    }
}
