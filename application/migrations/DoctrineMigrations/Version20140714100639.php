<?php
namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140714100639 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
		$this->addSql("DROP PROCEDURE IF EXISTS `es_sp_vendorProdCatDetails`");
		$this->addSql("
CREATE DEFINER=`root`@`localhost` PROCEDURE `es_sp_vendorProdCatDetails`(
	IN i_memberid INT(10)
)
BEGIN
	DECLARE o_message VARCHAR(100);
	DECLARE o_success BOOLEAN;

	DECLARE v_catid INT(10);
	DECLARE v_prdcount INT(10);
	DECLARE v_parentcat INT(10);

	DECLARE done INT DEFAULT 0;

	DECLARE cur CURSOR FOR
		SELECT cat_id, COUNT(id_product) as prd_count
		FROM es_product
		WHERE member_id = i_memberid
		GROUP BY cat_id;

	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

	DROP TEMPORARY TABLE IF EXISTS tbl_prdcat;
	CREATE TEMPORARY TABLE tbl_prdcat
		(parent_cat INT(10) DEFAULT 0)
		SELECT cat_id, 
			COUNT(id_product) as prd_count
		FROM es_product
		WHERE member_id = i_memberid
		GROUP BY cat_id;
	
	SET o_message = 'Database error.';
	SET o_success = FALSE;
		
	OPEN cur;
	read_loop: LOOP

		FETCH cur INTO v_catid, v_prdcount;

		IF done THEN
			LEAVE read_loop;
		END IF;

		IF v_catid != 1 THEN
			SELECT cur_cat INTO v_parentcat
			FROM(
				SELECT
					@r as cur_cat,
					(SELECT @r:=parent_id FROM es_cat WHERE id_cat = @r) as parent_id
				FROM (SELECT @r := v_catid)var, es_cat
			) t1
			WHERE parent_id = 1 AND cur_cat != 1;
		ELSEIF v_catid = 1 THEN
			SET v_parentcat = 1;
		END IF;

		UPDATE tbl_prdcat 
		SET parent_cat = v_parentcat 
		WHERE cat_id = v_catid; 

	END LOOP;

	SELECT t.parent_cat, t.cat_id, t.prd_count, IF(t.cat_id != 1,c.name,'NULL') as p_cat_name, IF(t.cat_id != 1, c.slug, 'null') as p_cat_slug
	FROM tbl_prdcat t
	LEFT JOIN es_cat c
		ON t.parent_cat = c.id_cat;

	DROP TEMPORARY TABLE IF EXISTS tbl_prdcat;

END");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
		$this->addSql("DROP PROCEDURE IF EXISTS `es_sp_vendorProdCatDetails`");
		$this->addSql("
CREATE DEFINER=`root`@`localhost` PROCEDURE `es_sp_vendorProdCatDetails`(
	IN i_memberid INT(10)
)
BEGIN
	DECLARE o_message VARCHAR(100);
	DECLARE o_success BOOLEAN;

	DECLARE v_catid INT(10);
	DECLARE v_prdcount INT(10);
	DECLARE v_parentcat INT(10);

	DECLARE done INT DEFAULT 0;

	DECLARE cur CURSOR FOR
		SELECT cat_id, COUNT(id_product) as prd_count
		FROM es_product
		WHERE member_id = i_memberid
		GROUP BY cat_id;

	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

	DROP TEMPORARY TABLE IF EXISTS tbl_prdcat;
	CREATE TEMPORARY TABLE tbl_prdcat
		(parent_cat INT(10) DEFAULT 0)
		SELECT cat_id, 
			COUNT(id_product) as prd_count
		FROM es_product
		WHERE member_id = i_memberid
		GROUP BY cat_id;
	
	SET o_message = 'Database error.';
	SET o_success = FALSE;
		
	OPEN cur;
	read_loop: LOOP

		FETCH cur INTO v_catid, v_prdcount;

		IF done THEN
			LEAVE read_loop;
		END IF;

		SELECT cur_cat INTO v_parentcat
		FROM(
			SELECT
				@r as cur_cat,
				(SELECT @r:=parent_id FROM es_cat WHERE id_cat = @r) as parent_id
			FROM (SELECT @r := v_catid)var, es_cat
		) t1
		WHERE parent_id = 1 AND cur_cat != 1;

		UPDATE tbl_prdcat 
		SET parent_cat = v_parentcat 
		WHERE cat_id = v_catid; 

	END LOOP;

	SELECT t.parent_cat, t.cat_id, t.prd_count, c.name as p_cat_name, c.slug as p_cat_slug
	FROM tbl_prdcat t
	LEFT JOIN es_cat c
		ON t.parent_cat = c.id_cat;

	DROP TEMPORARY TABLE IF EXISTS tbl_prdcat;

END
");
    }
}
