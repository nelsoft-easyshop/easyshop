<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;
/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141007024621 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("DROP PROCEDURE IF EXISTS `es_sp_getProductBySlug`");
        $this->addSql("CREATE DEFINER=`root`@`localhost` PROCEDURE `es_sp_getProductBySlug`(
                        IN i_slug VARCHAR(1024))
                BEGIN
                    DECLARE o_success BOOLEAN;
                    DECLARE   o_message VARCHAR(50);
                    DECLARE o_productid INT(10);
                    
                    START TRANSACTION;
            
                    SET o_success = FALSE;
                    SET o_message = 'An error occured.';
            
                    SELECT id_product INTO o_productid
                    FROM es_product
                    WHERE slug = i_slug;
            
                    IF o_productid IS NOT NULL THEN
                        UPDATE `es_product` SET `clickcount`=`clickcount`+1 WHERE `id_product` = o_productid;
                        SET o_success = TRUE;
                        SET o_message = '';
                        ELSE
                        SET o_success = FALSE;
                
                    END IF;
            
                    COMMIT;
            
                    IF o_success = TRUE THEN
                    SELECT p.id_product as id_product, p.promo_type, p.condition, p.slug,p.brand_id as brand_id, p.brand_other_name as custombrand, p.name as product_name, p.description as description, 
                        p.is_meetup, p.is_promote, p.startdate, p.enddate, p.cat_id as cat_id, p.price as price,  p.brief as brief, p.sku as sku,
                        p.is_sold_out, p.is_cod, p.discount, s.name as style_name, b.name as brand_name, p.member_id as sellerid, m.nickname as sellernickname, m.username as sellerusername, m.store_name as storename, 
                        m.slug as sellerslug, m.imgurl as userpic, o_success, o_message
                        FROM es_product p 
                        LEFT JOIN es_style s ON p.style_id = s.id_style
                        LEFT JOIN es_brand b ON p.brand_id = b.id_brand
                        LEFT JOIN es_member m on p.member_id = m.id_member 
                        WHERE p.id_product = o_productid AND p.is_delete = 0 AND p.is_draft = 0;        
                    ELSE
                        SELECT o_message, o_success;
                    END IF;
                END");


    }

    public function down(Schema $schema)
    {
        $this->addSql("DROP PROCEDURE IF EXISTS `es_sp_getProductBySlug`");
        $this->addSql("CREATE DEFINER=`root`@`localhost` PROCEDURE `es_sp_getProductBySlug`(
                        IN i_slug VARCHAR(1024))
                BEGIN
                    DECLARE o_success BOOLEAN;
                    DECLARE   o_message VARCHAR(50);
                    DECLARE o_productid INT(10);
                    
                    START TRANSACTION;
            
                    SET o_success = FALSE;
                    SET o_message = 'An error occured.';
            
                    SELECT id_product INTO o_productid
                    FROM es_product
                    WHERE slug = i_slug;
            
                    IF o_productid IS NOT NULL THEN
                        UPDATE `es_product` SET `clickcount`=`clickcount`+1 WHERE `id_product` = o_productid;
                        SET o_success = TRUE;
                        SET o_message = '';
                        ELSE
                        SET o_success = FALSE;
                
                    END IF;
            
                    COMMIT;
            
                    IF o_success = TRUE THEN
                    SELECT p.id_product as id_product, p.promo_type, p.condition, p.slug,p.brand_id as brand_id, p.brand_other_name as custombrand, p.name as product_name, p.description as description, 
                        p.is_meetup, p.is_promote, p.startdate, p.enddate, p.cat_id as cat_id, p.price as price,  p.brief as brief, p.sku as sku,
                        p.is_sold_out, p.is_cod, p.discount, s.name as style_name, b.name as brand_name, p.member_id as sellerid, m.nickname as sellernickname, m.username as sellerusername, m.slug as sellerslug, m.imgurl as userpic, o_success, o_message
                        FROM es_product p 
                        LEFT JOIN es_style s ON p.style_id = s.id_style
                        LEFT JOIN es_brand b ON p.brand_id = b.id_brand
                        LEFT JOIN es_member m on p.member_id = m.id_member 
                        WHERE p.id_product = o_productid AND p.is_delete = 0 AND p.is_draft = 0;        
                    ELSE
                        SELECT o_message, o_success;
                    END IF;
                END");

    }
}
