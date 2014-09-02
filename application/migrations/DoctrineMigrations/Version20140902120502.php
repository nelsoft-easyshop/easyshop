<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140902120502 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("DROP PROCEDURE IF EXISTS `es_sp_Login_admin`;");
        $this->addSql("

            CREATE DEFINER=`root`@`localhost` PROCEDURE `es_sp_Login_admin`(
                IN i_username VARCHAR(255),
                IN i_password  VARCHAR(255)
                )
            BEGIN
                DECLARE o_success BOOLEAN;
                DECLARE o_adminid VARCHAR(50); 
                DECLARE o_message VARCHAR(50); 
                DECLARE v_encpass VARCHAR(250);
                
                
                DECLARE EXIT HANDLER FOR SQLEXCEPTION
                BEGIN
                    ROLLBACK;
                SELECT o_success AS o_success, o_adminid AS o_memberid, o_message AS o_message;
                END;
                
                DECLARE EXIT HANDLER FOR NOT FOUND
                BEGIN
                    ROLLBACK;
                SELECT o_success AS o_success, o_adminid AS o_adminid, o_message AS o_message;
                END;
                
                START TRANSACTION;
                    
                    SET o_success = FALSE;  
                SET o_message = 'Invalid Username / Password';
                    ## Select if exist
                    SELECT REVERSE(PASSWORD(CONCAT(MD5(i_username),SHA1(i_password)))) INTO v_encpass;
                            SELECT id_admin_member INTO o_adminid FROM `es_admin_member` WHERE username = i_username AND PASSWORD = v_encpass; 
                IF o_adminid IS NOT NULL THEN 
                            SET o_success = TRUE;
                            SET o_message = '';
                ELSE
                    SET o_success = FALSE;
                END IF;   
                    
                    COMMIT;        
                    SELECT o_success AS o_success, o_adminid AS o_adminid, o_message AS o_message;
            END ");
        

    }

    public function down(Schema $schema)
    {
          $this->addSql("DROP PROCEDURE IF EXISTS `es_sp_Login_admin`;");
          $this->addSql("

            CREATE DEFINER=`root`@`localhost` PROCEDURE `es_sp_Login_admin`(
                IN i_username VARCHAR(255),
                IN i_password  VARCHAR(255)
                )
            BEGIN
                DECLARE o_success BOOLEAN;
                DECLARE o_adminid VARCHAR(50); 
                DECLARE o_message VARCHAR(50); 
                DECLARE v_encpass VARCHAR(250);
                
                
                DECLARE EXIT HANDLER FOR SQLEXCEPTION
                BEGIN
                    ROLLBACK;
                SELECT o_success AS o_success, o_adminid AS o_memberid, o_message AS o_message;
                END;
                
                DECLARE EXIT HANDLER FOR NOT FOUND
                BEGIN
                    ROLLBACK;
                SELECT o_success AS o_success, o_adminid AS o_adminid, o_message AS o_message;
                END;
                
                START TRANSACTION;
                    
                    SET o_success = FALSE;  
                SET o_message = 'Invalid Username / Password';
                    ## Select if exist
                    SELECT REVERSE(PASSWORD(CONCAT(MD5(i_username),SHA1(i_password)))) INTO v_encpass;
                            SELECT id_admin INTO o_adminid FROM `es_admin_member` WHERE username = i_username AND PASSWORD = v_encpass; 
                IF o_adminid IS NOT NULL THEN 
                            SET o_success = TRUE;
                            SET o_message = '';
                ELSE
                    SET o_success = FALSE;
                END IF;   
                    
                    COMMIT;        
                    SELECT o_success AS o_success, o_adminid AS o_adminid, o_message AS o_message;
            END ");
        

    }
}
