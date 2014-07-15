<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;


/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140704144415 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
       	$this->addSql('ALTER TABLE `es_product`   
  			ADD COLUMN `is_sold_out` TINYINT(1) DEFAULT 0  NOT NULL AFTER `promo_type`;
		'); 

       	$this->addSql('CREATE TABLE `es_order_billing_info` (
			  `id_es_order_billing_info` int(10) NOT NULL AUTO_INCREMENT,
			  `order_id` int(10) DEFAULT "0",
			  `order_product_id` int(10) DEFAULT "0",
			  `bank_name` varchar(1024) DEFAULT "",
			  `account_name` varchar(1024) DEFAULT "",
			  `account_number` varchar(1024) DEFAULT "",
			  PRIMARY KEY (`id_es_order_billing_info`)
			) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
       	'); 


       	$this->addSql("


			DROP PROCEDURE IF EXISTS `es_sp_Payment_order`;

			CREATE DEFINER=`root`@`localhost` PROCEDURE `es_sp_Payment_order`(
			          IN i_payment_type INT (10)
			          , IN i_invoice_no VARCHAR (150)
			          , IN i_total_amount DECIMAL (15, 4)
			          , IN i_ip VARCHAR (50)
			          , IN i_member_id INT (10)
			          , IN i_product_string TEXT
			          , IN i_product_count INT
			          , IN i_data_response TEXT
			          , IN i_transaction_id VARCHAR (1024)
			        )
			BEGIN
			          # ACCESSIBLE VARIABLES
			          DECLARE o_success BOOLEAN ;
			          
			                      DECLARE   o_message VARCHAR(50);
			                    DECLARE v_order_id INT(10);
			                    DECLARE v_address_id INT(10);
			                    DECLARE v_order_status INT(10);
			                    
			                    DECLARE v_product_counter INT DEFAULT 1;
			                    DECLARE v_product_data TEXT;
			                    
			                    DECLARE v_seller_id INT(10);
			                    DECLARE v_product_id INT(10);
			                    DECLARE v_quantity INT(10);
			                    DECLARE v_price DECIMAL(15,4);
			                    DECLARE v_tax  DECIMAL(15,4);
			                    DECLARE v_total DECIMAL(15,4);
			                    DECLARE v_product_item INT(10);
			                    DECLARE v_billing_info_id INT(10);
			                    DECLARE v_attr_count INT(10);
			                    DECLARE v_attr_string TEXT;
			                    DECLARE v_attr_string1 TEXT;
			                    DECLARE v_attr_string_name TEXT;
			                    DECLARE v_attr_string_value TEXT;
			                    DECLARE v_attr_string_price DECIMAL(15,4);
			                    DECLARE v_productattr_counter INT DEFAULT 1;
			                    
			                    DECLARE v_order_product_id INT(10); 
			                    DECLARE v_order_product_status INT(10);
			                    DECLARE v_external_charge DECIMAL(15,4);
			                    DECLARE v_product_external_charge DECIMAL(15,4);
			                    DECLARE v_net DECIMAL(15,4);
			                    
			                    DECLARE t_stateregion INT(10);
			                    DECLARE t_city INT(10);
			                    DECLARE t_country INT(10);
			                    DECLARE t_address VARCHAR(250);
			                    DECLARE t_consignee VARCHAR(45);
			                    DECLARE t_mobile VARCHAR(45);
			                    DECLARE t_telephone VARCHAR(45);
			                    DECLARE t_lat DOUBLE;
			                    DECLARE t_lng DOUBLE;
			                    
			                    DECLARE EXIT HANDLER FOR SQLEXCEPTION
			                          BEGIN
			                          ROLLBACK;
			                         SELECT o_success AS o_success, o_message AS o_message;
			                      END;
			                      
			                    DECLARE EXIT HANDLER FOR NOT FOUND
			                      BEGIN
			                          ROLLBACK;
			                       SELECT o_success AS o_success, 'Error Handler 2' AS o_message;
			                      END;
			                      
			                    START TRANSACTION;
			                    
			                        
			                        SET GLOBAL log_bin_trust_function_creators = 1;
			                        SET o_success = FALSE;
			                        SET o_message = 'Error Code: Payment000';
			                            
			                        SET o_message = 'Error Code: Payment001';
			                        SELECT `stateregion`, `city`, `country`, `address`, `consignee`, `mobile`, `telephone`, `lat`, `lng` 
			                            INTO t_stateregion, t_city, t_country, t_address, t_consignee, t_mobile, t_telephone, t_lat, t_lng  FROM `es_address` WHERE `type` = 1 AND `id_member` = i_member_id LIMIT 1;
			                        
			                        SET o_message = 'Error Code: Payment001.1';
			                        INSERT INTO es_order_shipping_address (`stateregion`, `city`, `country`, `address`,`consignee`,`mobile`,`telephone`,`lat`,`lng`)
			                        VALUES (t_stateregion, t_city, t_country, t_address, t_consignee, t_mobile, t_telephone, t_lat, t_lng);
			                        SET o_message = 'Error Code: Payment001.2';
			                        SET v_address_id = LAST_INSERT_ID();
			                        SET o_message = 'Error Code: Payment002';
			                             
			                        CASE i_payment_type
			                    WHEN '1' THEN 
			                        SET v_order_status = 99;
			                        SET v_order_product_status = 0;
			                        SET v_external_charge = (i_total_amount * 0.044) + 15;
			                    WHEN '2' THEN 
			                        SET v_order_status = 99;
			                        SET v_order_product_status = 0;
			                        SET v_external_charge = 20;
			                    WHEN '3' THEN
			                        SET v_order_status = 0;
			                        SET v_order_product_status = 0;
			                        SET v_external_charge = 0;
			                    WHEN '4' THEN
			                        SET v_order_status = 99;
			                        SET v_order_product_status = 0;
			                        SET v_external_charge = 20;
			                    WHEN '5' THEN
			                        SET v_order_status = 99;
			                        SET v_order_product_status = 0;
			                        SET v_external_charge = 0;
			                    ELSE 
			                        SET v_order_status = 0;
			                        SET v_order_product_status = 0;
			                        SET v_external_charge = 0;
			                END CASE;
			                        
			                        SET o_message = 'Error Code: Payment002.1';
			                        SET v_net = i_total_amount - v_external_charge;
			                        INSERT INTO `es_order` (`invoice_no`,`buyer_id`,`total`,`dateadded`,`ip`,`shipping_address_id`,`payment_method_id`,`order_status`,`data_response`,`transaction_id`,`payment_method_charge`, `net`) 
			                        VALUES (i_invoice_no,i_member_id,i_total_amount,NOW(),i_ip,v_address_id,i_payment_type,v_order_status,i_data_response,i_transaction_id,v_external_charge, v_net);
			                        SET o_message = 'Error Code: Payment003';               
			                        SELECT `id_order` INTO v_order_id FROM `es_order` WHERE buyer_id = i_member_id  ORDER BY `id_order` DESC LIMIT 1; 
			                          
			                        SET o_message = 'Error Code: [HISTORY]Payment003';
			                        INSERT INTO `es_order_history` (`order_id`,`comment`,`date_added`,`order_status`) VALUES (v_order_id,'CREATED',NOW(),v_order_status);
			                        
			                        SET o_message = 'Error Code: Payment003.1'; 
			                        UPDATE `es_order` SET `invoice_no` = CONCAT(v_order_id,'-',i_invoice_no) WHERE `id_order` = v_order_id;
			                        
			                        SET o_message = 'Error Code: Payment004';
			                        WHILE v_product_counter <= i_product_count DO
			                        
			                        SET o_message = 'Error Code: Payment005';
			                        SELECT SPLIT_STRING(i_product_string, '<||>',v_product_counter) INTO v_product_data;
			                            
			                            
			                            SET o_message = 'Error Code: Payment006';
			                            SELECT SPLIT_STRING(v_product_data, '{+}',1) INTO v_seller_id;  
			                            SELECT SPLIT_STRING(v_product_data, '{+}',2) INTO v_product_id;
			                            SELECT SPLIT_STRING(v_product_data, '{+}',3) INTO v_quantity;
			                            SELECT SPLIT_STRING(v_product_data, '{+}',4) INTO v_price;
			                            SELECT SPLIT_STRING(v_product_data, '{+}',5) INTO v_tax;
			                            SELECT SPLIT_STRING(v_product_data, '{+}',6) INTO v_total;
			                            SELECT SPLIT_STRING(v_product_data, '{+}',7) INTO v_product_item;
			                            SELECT SPLIT_STRING(v_product_data, '{+}',8) INTO v_attr_count;
			                            SELECT SPLIT_STRING(v_product_data, '{+}',9) INTO v_attr_string;
			                            
			                            
			                            SET v_product_external_charge = (v_total/i_total_amount) * v_external_charge;
			                            
			                            SET o_message = 'Error Code: Payment007a';
			                            SELECT billing_info_id INTO v_billing_info_id
			                                FROM es_product
			                                WHERE id_product = v_product_id;
			                                
			                            SET o_message = 'Error Code: Payment007b';
			                            SET v_net = v_total - v_product_external_charge;
			                            INSERT INTO `es_order_product` 
			                            (`order_id`,`seller_id`,`product_id`,`order_quantity`,`price`,`handling_fee`,`total`,`product_item_id`,`status`, `payment_method_charge`, `net`,`seller_billing_id`)
			                            VALUES
			                            (v_order_id,v_seller_id,v_product_id,v_quantity,v_price,v_tax,v_total,v_product_item,v_order_product_status,v_product_external_charge,v_net,v_billing_info_id);
			                            
			                            SET o_message = 'Error Code: Payment008';
			                            SELECT `id_order_product` INTO v_order_product_id FROM `es_order_product` 
			                            WHERE order_id = v_order_id  ORDER BY `id_order_product` DESC LIMIT 1;
			                            
			                            SET o_message = 'Error Code: Payment008c';
			                            CASE
			                            WHEN v_billing_info_id = 0 THEN
											BEGIN END;
			                            ELSE
										   INSERT INTO `es_order_billing_info` (
											  `order_id`
											  , `order_product_id`
											  , `bank_name`
											  , `account_name`
											  , `account_number`
											) 
											SELECT 
											  v_order_id
											  , v_order_product_id
											  , b.bank_name
											  , a.bank_account_name
											  , bank_account_number 
											FROM
											  `es_billing_info` a
											  , `es_bank_info` b
											  , `es_product` c 
											WHERE a.bank_id = b.id_bank 
											  AND id_product = v_product_id;
			                            END CASE;
			 
			                            SET o_message = 'Error Code: Payment008a';
			                            CASE
			                            WHEN v_attr_count > 0 THEN
			                                
			                                SET o_message = 'Error Code: Payment008b';
			                                WHILE v_productattr_counter <= v_attr_count DO
			                                    SELECT SPLIT_STRING(v_attr_string, '(-)',v_productattr_counter) INTO v_attr_string1;

			                                        SELECT SPLIT_STRING(v_attr_string1, '[]',1) INTO v_attr_string_name;
			                                        SELECT SPLIT_STRING(v_attr_string1, '[]',2) INTO v_attr_string_value;
			                                        SELECT SPLIT_STRING(v_attr_string1, '[]',3) INTO v_attr_string_price;
			                                    
			                                        INSERT INTO `es_order_product_attr` (`order_product_id`,`attr_name`,`attr_value`,`attr_price`)
			                                        VALUES(v_order_product_id,v_attr_string_name,v_attr_string_value,v_attr_string_price);
			                                    SET v_productattr_counter = v_productattr_counter + 1;
			                                END WHILE;
			                            ELSE 
			                                BEGIN END;
			                            END CASE;
			                            
			                            
			                            SET o_message = 'Error Code: Payment009';
			                 
			                            INSERT INTO `es_order_product_history` 
			                            (`order_product_id`) 
			                            VALUES 
			                            (v_order_product_id);
			                            
			             
			                            
			                            SET v_product_counter = v_product_counter + 1;
			                                    
			                        END WHILE;
			                        
			                                SET o_message ='Success! Transaction Saved';
			                                SET o_success = TRUE;
			                            
			                    COMMIT;
			                        
			                        SELECT o_success, o_message,v_order_id,`invoice_no`,total,dateadded FROM `es_order` WHERE `id_order` = v_order_id;
			                    
			                END

		"); 


    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    	$this->addSql("ALTER TABLE `es_product`   
			  DROP COLUMN `is_sold_out`;
		");

    	$this->addSql("
    			DROP TABLE `es_order_billing_info`;
		");

        $this->addSql("


			DROP PROCEDURE IF EXISTS `es_sp_Payment_order`;

			CREATE DEFINER=`root`@`localhost` PROCEDURE `es_sp_Payment_order`(
			          IN i_payment_type INT (10)
			          , IN i_invoice_no VARCHAR (150)
			          , IN i_total_amount DECIMAL (15, 4)
			          , IN i_ip VARCHAR (50)
			          , IN i_member_id INT (10)
			          , IN i_product_string TEXT
			          , IN i_product_count INT
			          , IN i_data_response TEXT
			          , IN i_transaction_id VARCHAR (1024)
			        )
			BEGIN
			          # ACCESSIBLE VARIABLES
			          DECLARE o_success BOOLEAN ;
			          
			                      DECLARE   o_message VARCHAR(50);
			                    DECLARE v_order_id INT(10);
			                    DECLARE v_address_id INT(10);
			                    DECLARE v_order_status INT(10);
			                    
			                    DECLARE v_product_counter INT DEFAULT 1;
			                    DECLARE v_product_data TEXT;
			                    
			                    DECLARE v_seller_id INT(10);
			                    DECLARE v_product_id INT(10);
			                    DECLARE v_quantity INT(10);
			                    DECLARE v_price DECIMAL(15,4);
			                    DECLARE v_tax  DECIMAL(15,4);
			                    DECLARE v_total DECIMAL(15,4);
			                    DECLARE v_product_item INT(10);
			                    DECLARE v_billing_info_id INT(10);
			                    DECLARE v_attr_count INT(10);
			                    DECLARE v_attr_string TEXT;
			                    DECLARE v_attr_string1 TEXT;
			                    DECLARE v_attr_string_name TEXT;
			                    DECLARE v_attr_string_value TEXT;
			                    DECLARE v_attr_string_price DECIMAL(15,4);
			                    DECLARE v_productattr_counter INT DEFAULT 1;
			                    
			                    DECLARE v_order_product_id INT(10); 
			                    DECLARE v_order_product_status INT(10);
			                    DECLARE v_external_charge DECIMAL(15,4);
			                    DECLARE v_product_external_charge DECIMAL(15,4);
			                    DECLARE v_net DECIMAL(15,4);
			                    
			                    DECLARE t_stateregion INT(10);
			                    DECLARE t_city INT(10);
			                    DECLARE t_country INT(10);
			                    DECLARE t_address VARCHAR(250);
			                    DECLARE t_consignee VARCHAR(45);
			                    DECLARE t_mobile VARCHAR(45);
			                    DECLARE t_telephone VARCHAR(45);
			                    DECLARE t_lat DOUBLE;
			                    DECLARE t_lng DOUBLE;
			                    
			                    DECLARE EXIT HANDLER FOR SQLEXCEPTION
			                          BEGIN
			                          ROLLBACK;
			                         SELECT o_success AS o_success, o_message AS o_message;
			                      END;
			                      
			                    DECLARE EXIT HANDLER FOR NOT FOUND
			                      BEGIN
			                          ROLLBACK;
			                       SELECT o_success AS o_success, 'Error Handler 2' AS o_message;
			                      END;
			                      
			                    START TRANSACTION;
			                    
			                        
			                        SET GLOBAL log_bin_trust_function_creators = 1;
			                        SET o_success = FALSE;
			                        SET o_message = 'Error Code: Payment000';
			                            
			                        SET o_message = 'Error Code: Payment001';
			                        SELECT `stateregion`, `city`, `country`, `address`, `consignee`, `mobile`, `telephone`, `lat`, `lng` 
			                            INTO t_stateregion, t_city, t_country, t_address, t_consignee, t_mobile, t_telephone, t_lat, t_lng  FROM `es_address` WHERE `type` = 1 AND `id_member` = i_member_id LIMIT 1;
			                        
			                        SET o_message = 'Error Code: Payment001.1';
			                        INSERT INTO es_order_shipping_address (`stateregion`, `city`, `country`, `address`,`consignee`,`mobile`,`telephone`,`lat`,`lng`)
			                        VALUES (t_stateregion, t_city, t_country, t_address, t_consignee, t_mobile, t_telephone, t_lat, t_lng);
			                        SET o_message = 'Error Code: Payment001.2';
			                        SET v_address_id = LAST_INSERT_ID();
			                        SET o_message = 'Error Code: Payment002';
			                             
			                        CASE i_payment_type
			                    WHEN '1' THEN 
			                        SET v_order_status = 99;
			                        SET v_order_product_status = 0;
			                        SET v_external_charge = (i_total_amount * 0.044) + 15;
			                    WHEN '2' THEN 
			                        SET v_order_status = 99;
			                        SET v_order_product_status = 0;
			                        SET v_external_charge = 20;
			                    WHEN '3' THEN
			                        SET v_order_status = 0;
			                        SET v_order_product_status = 0;
			                        SET v_external_charge = 0;
			                    WHEN '4' THEN
			                        SET v_order_status = 99;
			                        SET v_order_product_status = 0;
			                        SET v_external_charge = 20;
			                    WHEN '5' THEN
			                        SET v_order_status = 99;
			                        SET v_order_product_status = 0;
			                        SET v_external_charge = 0;
			                    ELSE 
			                        SET v_order_status = 0;
			                        SET v_order_product_status = 0;
			                        SET v_external_charge = 0;
			                END CASE;
			                        
			                        SET o_message = 'Error Code: Payment002.1';
			                        SET v_net = i_total_amount - v_external_charge;
			                        INSERT INTO `es_order` (`invoice_no`,`buyer_id`,`total`,`dateadded`,`ip`,`shipping_address_id`,`payment_method_id`,`order_status`,`data_response`,`transaction_id`,`payment_method_charge`, `net`) 
			                        VALUES (i_invoice_no,i_member_id,i_total_amount,NOW(),i_ip,v_address_id,i_payment_type,v_order_status,i_data_response,i_transaction_id,v_external_charge, v_net);
			                        SET o_message = 'Error Code: Payment003';               
			                        SELECT `id_order` INTO v_order_id FROM `es_order` WHERE buyer_id = i_member_id  ORDER BY `id_order` DESC LIMIT 1; 
			                          
			                        SET o_message = 'Error Code: [HISTORY]Payment003';
			                        INSERT INTO `es_order_history` (`order_id`,`comment`,`date_added`,`order_status`) VALUES (v_order_id,'CREATED',NOW(),v_order_status);
			                        
			                        SET o_message = 'Error Code: Payment003.1'; 
			                        UPDATE `es_order` SET `invoice_no` = CONCAT(v_order_id,'-',i_invoice_no) WHERE `id_order` = v_order_id;
			                        
			                        SET o_message = 'Error Code: Payment004';
			                        WHILE v_product_counter <= i_product_count DO
			                        
			                        SET o_message = 'Error Code: Payment005';
			                        SELECT SPLIT_STRING(i_product_string, '<||>',v_product_counter) INTO v_product_data;
			                            
			                            
			                            SET o_message = 'Error Code: Payment006';
			                            SELECT SPLIT_STRING(v_product_data, '{+}',1) INTO v_seller_id;  
			                            SELECT SPLIT_STRING(v_product_data, '{+}',2) INTO v_product_id;
			                            SELECT SPLIT_STRING(v_product_data, '{+}',3) INTO v_quantity;
			                            SELECT SPLIT_STRING(v_product_data, '{+}',4) INTO v_price;
			                            SELECT SPLIT_STRING(v_product_data, '{+}',5) INTO v_tax;
			                            SELECT SPLIT_STRING(v_product_data, '{+}',6) INTO v_total;
			                            SELECT SPLIT_STRING(v_product_data, '{+}',7) INTO v_product_item;
			                            SELECT SPLIT_STRING(v_product_data, '{+}',8) INTO v_attr_count;
			                            SELECT SPLIT_STRING(v_product_data, '{+}',9) INTO v_attr_string;
			                            
			                            
			                            SET v_product_external_charge = (v_total/i_total_amount) * v_external_charge;
			                            
			                            SET o_message = 'Error Code: Payment007a';
			                            SELECT billing_info_id INTO v_billing_info_id
			                                FROM es_product
			                                WHERE id_product = v_product_id;
			                                
			                            SET o_message = 'Error Code: Payment007b';
			                            SET v_net = v_total - v_product_external_charge;
			                            INSERT INTO `es_order_product` 
			                            (`order_id`,`seller_id`,`product_id`,`order_quantity`,`price`,`handling_fee`,`total`,`product_item_id`,`status`, `payment_method_charge`, `net`,`seller_billing_id`)
			                            VALUES
			                            (v_order_id,v_seller_id,v_product_id,v_quantity,v_price,v_tax,v_total,v_product_item,v_order_product_status,v_product_external_charge,v_net,v_billing_info_id);
			                            
			                            SET o_message = 'Error Code: Payment008';
			                            SELECT `id_order_product` INTO v_order_product_id FROM `es_order_product` 
			                            WHERE order_id = v_order_id  ORDER BY `id_order_product` DESC LIMIT 1;
			                            
			                            
			                            SET o_message = 'Error Code: Payment008a';
			                            CASE
			                            WHEN v_attr_count > 0 THEN
			                                
			                                SET o_message = 'Error Code: Payment008b';
			                                WHILE v_productattr_counter <= v_attr_count DO
			                                    SELECT SPLIT_STRING(v_attr_string, '(-)',v_productattr_counter) INTO v_attr_string1;

			                                        SELECT SPLIT_STRING(v_attr_string1, '[]',1) INTO v_attr_string_name;
			                                        SELECT SPLIT_STRING(v_attr_string1, '[]',2) INTO v_attr_string_value;
			                                        SELECT SPLIT_STRING(v_attr_string1, '[]',3) INTO v_attr_string_price;
			                                    
			                                        INSERT INTO `es_order_product_attr` (`order_product_id`,`attr_name`,`attr_value`,`attr_price`)
			                                        VALUES(v_order_product_id,v_attr_string_name,v_attr_string_value,v_attr_string_price);
			                                    SET v_productattr_counter = v_productattr_counter + 1;
			                                END WHILE;
			                            ELSE 
			                                BEGIN END;
			                            END CASE;
			                            
			                            
			                            SET o_message = 'Error Code: Payment009';
			                 
			                            INSERT INTO `es_order_product_history` 
			                            (`order_product_id`) 
			                            VALUES 
			                            (v_order_product_id);
			                            
			                            CASE
			                            WHEN i_payment_type IN (3,5) THEN
			                                UPDATE `es_product_item` SET `quantity` = `quantity` - v_quantity WHERE `product_id` = v_product_id AND `id_product_item` = v_product_item;
			                            ELSE 
			                            BEGIN END;
			                            END CASE;
			                            
			                            SET v_product_counter = v_product_counter + 1;
			                                    
			                        END WHILE;
			                        
			                                SET o_message ='Success! Transaction Saved';
			                                SET o_success = TRUE;
			                            
			                    COMMIT;
			                        
			                        SELECT o_success, o_message,v_order_id,`invoice_no`,total,dateadded FROM `es_order` WHERE `id_order` = v_order_id;
			                    
			                END

        ");

    }
}
