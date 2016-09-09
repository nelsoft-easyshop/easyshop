<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141002101515 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP PROCEDURE IF EXISTS `es_sp_Signup_user`;");
        $this->addSql("
                CREATE DEFINER=`root`@`localhost` PROCEDURE `es_sp_Signup_user`(
                                IN i_username VARCHAR(255),
                                IN i_password VARCHAR(255),
                                IN i_fullname VARCHAR(255),
                                IN i_contactno VARCHAR(45),
                                IN i_email VARCHAR(255)
                            )
                BEGIN
                DECLARE v_pass VARCHAR(255);
                DECLARE v_memberid VARCHAR(255);

                START TRANSACTION;

                SELECT reverse(PASSWORD(concat(md5(i_username),sha1(i_password)))) into v_pass;

                SELECT id_member INTO v_memberid FROM `es_member` WHERE `username` = i_username OR `slug` = i_username;

                IF v_memberid IS NULL THEN

                    INSERT INTO `es_member` (`username`, `password`, `contactno`, `email`, `datecreated`, `fullname`, `slug`)
                    VALUES (i_username, v_pass, i_contactno, i_email, NOW(), i_fullname, i_username);

                END IF;

                COMMIT;

                SELECT id_member FROM es_member WHERE username = i_username;

            END
        ");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP PROCEDURE IF EXISTS `es_sp_Signup_user`;");
        $this->addSql("
            CREATE DEFINER=`root`@`localhost` PROCEDURE `es_sp_Signup_user`(
                IN i_username VARCHAR(255),
                IN i_password VARCHAR(255),
                IN i_contactno VARCHAR(45),
                IN i_email VARCHAR(255)
            )
            BEGIN
                DECLARE v_pass VARCHAR(255);
                DECLARE v_memberid VARCHAR(255);

                START TRANSACTION;

                SELECT reverse(PASSWORD(concat(md5(i_username),sha1(i_password)))) into v_pass;

                SELECT id_member INTO v_memberid FROM `es_member` WHERE `username` = i_username OR `slug` = i_username;

                IF v_memberid IS NULL THEN

                    INSERT INTO `es_member` (`username`, `password`, `contactno`, `email`, `datecreated`, `slug`)
                    VALUES (i_username, v_pass, i_contactno, i_email, NOW(), i_username);

                END IF;

                COMMIT;

                SELECT id_member FROM es_member WHERE username = i_username;

            END
        ");
    }
}
