<?php 

/* 
 * Schema Stored Procedures
 */

return array(

    "es_sp_ChangePass_User" =>
       "
        CREATE DEFINER=`root`@`localhost` PROCEDURE `es_sp_ChangePass_User`(
            IN i_username VARCHAR(255),
            IN i_cur_password VARCHAR (255),
            IN i_password VARCHAR(255)
        )
        BEGIN
            DECLARE old_pass VARCHAR(255);
            DECLARE new_pass VARCHAR(255);
            DECLARE o_memberid VARCHAR(255);
            DECLARE o_success VARCHAR(255);

            START TRANSACTION;
            
            SELECT reverse(PASSWORD(concat(md5(i_username),sha1(i_cur_password)))) INTO old_pass;
            SELECT reverse(PASSWORD(concat(md5(i_username),sha1(i_password)))) INTO new_pass;

            SELECT `id_member` into o_memberid FROM `es_member` WHERE `username` = i_username AND `password` = old_pass;
            
            IF o_memberid IS NOT NULL THEN 
                SET o_success = 'true';
                UPDATE `es_member` SET `password` = new_pass WHERE `username` = i_username AND `password` = old_pass;
            ELSE
                SET o_success = 'false';
            END IF;
            
            COMMIT;
            SELECT o_success;
        END",
        
    "es_sp_CookieLogin_user" =>
       "
        CREATE DEFINER=`root`@`localhost` PROCEDURE `es_sp_CookieLogin_user`(
            IN i_ip varchar(255),
            IN i_useragent varchar(255),
            IN i_token varchar(255),
            IN i_usersession varchar(255)
        )
        BEGIN
            DECLARE	o_token varchar(255);
            DECLARE o_usersession varchar(255);
            DECLARE o_memberid varchar(255);
            DECLARE o_success BOOLEAN;
            DECLARE o_message VARCHAR(50);

            SELECT id_member into o_memberid
            FROM `es_keeplogin`
            WHERE token = i_token AND last_ip = i_ip AND useragent = i_useragent;

            START TRANSACTION;

            SET o_success = FALSE;
            SET o_message = 'No keeplogin entry';

            IF o_memberid IS NOT NULL THEN
                SET o_success = TRUE;
                SET o_message = '';

                SELECT sha1(o_memberid + NOW()) into o_usersession;

                # UPDATE es_member table and create usersession
                UPDATE `es_member` set `usersession`= o_usersession WHERE `id_member` = o_memberid;

                # UPDATE keeplogin table and generate new cookie token
                SELECT sha1(concat(o_memberid,i_usersession, NOW())) into o_token;

                UPDATE `es_keeplogin` SET `token` = o_token WHERE `id_member` = o_memberid AND `last_ip` = i_ip AND `useragent` = i_useragent AND `token` = i_token;

            END IF;
            COMMIT;
            SELECT o_usersession, o_token, o_memberid, o_success;
        END ",
    
    "es_sp_CreateCookie_Keeplogin" => 
       "
        CREATE DEFINER=`root`@`localhost` PROCEDURE `es_sp_CreateCookie_Keeplogin`(
            IN i_memberid VARCHAR(255),
            IN i_ip  VARCHAR(255),
            IN i_useragent varchar(255),
            IN i_usersession varchar(255)
               )
        BEGIN
            DECLARE o_memberid BOOLEAN;
            DECLARE o_ip varchar(255);
            DECLARE o_useragent varchar(255);
            DECLARE o_token varchar(255);
            DECLARE v_token varchar(255);

            START TRANSACTION;

            SELECT sha1(concat(i_memberid,i_usersession, NOW())) into v_token;

            INSERT INTO `es_keeplogin` (`id_member`, `last_ip`, `useragent`, `token`)
            VALUES (i_memberid, i_ip, i_useragent, v_token)
            ON DUPLICATE KEY UPDATE `token` = v_token;

            COMMIT;

            SELECT `id_member` as o_memberid, `last_ip` as o_ip, `useragent` as o_useragent, `token` as o_token FROM `es_keeplogin` WHERE `id_member` = i_memberid AND `token` = v_token; 
            #SELECT `token` as o_token FROM `es_keeplogin` WHERE `id_member` = i_memberid AND `token` = v_token; 
        END ",
    
    "es_sp_CreateDefaultShipping" => 
        "
        CREATE DEFINER=`root`@`localhost` PROCEDURE `es_sp_CreateDefaultShipping`(
        )
        BEGIN
            DECLARE count INT(10);
            DECLARE loop_cnt INT(10);
            DECLARE pi_count INT(10);
            DECLARE pi_loop_cnt INT(10);
            DECLARE current_id INT(10);
            DECLARE current_shipping_id INT(10);
            START TRANSACTION;
            DROP TEMPORARY TABLE IF EXISTS prod_tbl;
            CREATE TEMPORARY TABLE prod_tbl ENGINE=MyISAM AS(
                SELECT id_product
                FROM
                (SELECT p.id_product, sh.id_shipping FROM es_product p 
                LEFT JOIN es_product_shipping_head sh ON p.id_product = sh.product_id 
                ORDER BY sh.id_shipping)x
                WHERE x.id_shipping IS NULL);

            SELECT COUNT(id_product) INTO count FROM prod_tbl;
            SET loop_cnt = 0;
            WHILE loop_cnt < count DO
                SELECT id_product INTO current_id FROM prod_tbl LIMIT loop_cnt, 1;
                INSERT INTO es_product_shipping_head (location_id,price,product_id) VALUES (5,0,current_id);
                SET current_shipping_id = LAST_INSERT_ID();
                DROP TEMPORARY TABLE IF EXISTS product_item_tbl;
                CREATE TEMPORARY TABLE product_item_tbl ENGINE=MyISAM AS(
                    SELECT id_product_item FROM es_product_item WHERE product_id = current_id
                );
                SELECT COUNT(id_product_item) INTO pi_count FROM product_item_tbl;
                SET pi_loop_cnt = 0;
                WHILE pi_loop_cnt < pi_count DO
                    INSERT INTO es_product_shipping_detail (shipping_id,product_item_id) VALUES (current_shipping_id,(SELECT id_product_item FROM product_item_tbl LIMIT pi_loop_cnt, 1));
                    SET pi_loop_cnt = pi_loop_cnt + 1;
                END WHILE;
                DROP TEMPORARY TABLE IF EXISTS product_item_tbl;


                SET loop_cnt = loop_cnt + 1;
            END WHILE;

            DROP TEMPORARY TABLE IF EXISTS prod_tbl;
            COMMIT;
            SELECT count;
        END ",
    
    "es_sp_ForgotPass_User" => 
       "
        CREATE DEFINER=`root`@`localhost` PROCEDURE `es_sp_ForgotPass_User`(
            IN i_username VARCHAR(255),
            IN i_password VARCHAR(255)
        )
        BEGIN
            DECLARE new_pass VARCHAR(255);
            DECLARE o_memberid VARCHAR(255);
            DECLARE o_success VARCHAR(255);

            START TRANSACTION;
            
            SELECT reverse(PASSWORD(concat(md5(i_username),sha1(i_password)))) INTO new_pass;
            SELECT `id_member` into o_memberid FROM `es_member` WHERE `username` = i_username;
            
            IF o_memberid IS NOT NULL THEN 
                SET o_success = 'true';
                UPDATE `es_member` SET `password` = new_pass WHERE `username` = i_username;
            ELSE
                SET o_success = 'false';
            END IF;

            COMMIT;
            SELECT o_success;
        END ",
        
    "es_sp_FullDelete_product" => 
       "
        CREATE DEFINER=`root`@`localhost` PROCEDURE `es_sp_FullDelete_product`(
            IN i_productid INT(10),
            IN i_memberid INT(10)
            )
        BEGIN
            DECLARE o_success BOOLEAN;
            DECLARE o_productid INT(10);
            DECLARE o_imgpath VARCHAR(255);
            
            
            DECLARE EXIT HANDLER FOR SQLEXCEPTION
              BEGIN
                  ROLLBACK;
                  SELECT o_success AS o_success, o_productid as o_productid, o_imgpath as o_imgpath;
              END;
              
            DECLARE EXIT HANDLER FOR NOT FOUND
              BEGIN
                  ROLLBACK;
                  SELECT o_success AS o_success, o_productid as o_productid, o_imgpath as o_imgpath;
              END;
              
            START TRANSACTION;

            SET o_success = FALSE;	

            SELECT `id_product` INTO o_productid FROM `es_product` WHERE `id_product` = i_productid AND `member_id` = i_memberid;
            IF o_productid IS NOT NULL THEN
                SELECT `product_image_path` INTO o_imgpath FROM `es_product_image` WHERE `product_id` = i_productid AND `is_primary`=1;
                DELETE FROM `es_product_attr` WHERE `product_id` = i_productid;
                DELETE FROM `es_product_image` WHERE `product_id` = i_productid;
                DELETE FROM `es_product_review` WHERE `product_id` = i_productid;
                DELETE FROM `es_product` WHERE `id_product` = i_productid AND `member_id` = i_memberid;
                SET o_success = TRUE;
            ELSE
                SET o_success = FALSE;
            END IF;
            
            COMMIT;
                
            SELECT o_success AS o_success, o_productid AS o_productid, o_imgpath as o_imgpath;
            
        END ",
        
    "es_sp_getProductBySlug" =>
       "
        CREATE DEFINER=`root`@`localhost` PROCEDURE `es_sp_getProductBySlug`(
            IN i_slug VARCHAR(1024)
        )
        BEGIN
            DECLARE o_success BOOLEAN;
            DECLARE	o_message VARCHAR(50);
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
                      p.is_promote, p.startdate, p.enddate, p.cat_id as cat_id, p.price as price,  p.brief as brief, p.sku as sku,
                      p.is_cod, s.name as style_name, b.name as brand_name, p.member_id as sellerid, m.nickname as sellernickname, m.username as sellerusername, m.imgurl as userpic, o_success, o_message
                      FROM es_product p 
                      LEFT JOIN es_style s ON p.style_id = s.id_style
                      LEFT JOIN es_brand b ON p.brand_id = b.id_brand
                      LEFT JOIN es_member m on p.member_id = m.id_member 
                      WHERE p.id_product = o_productid AND p.is_delete = 0 AND p.is_draft = 0;		
            ELSE
                SELECT o_message, o_success;
            END IF;
        END ",
        
    "es_sp_Login_admin" => 
        "
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
        END ",
    
    "es_sp_Login_user" => 
       "
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
        END ",
    
    "es_sp_Logout_user" => 
       "
        CREATE DEFINER=`root`@`localhost` PROCEDURE `es_sp_Logout_user`(
            IN i_usersession VARCHAR(255),
            IN i_id  VARCHAR(255)
               )
        BEGIN
            DECLARE o_success BOOLEAN;
            DECLARE o_memberid VARCHAR(50); 
            DECLARE o_message VARCHAR(50); 
            
            
            DECLARE EXIT HANDLER FOR SQLEXCEPTION
              BEGIN
                  ROLLBACK;
               SELECT o_success AS o_success, o_memberid AS o_memberid, o_message AS o_message;
              END;
              
            DECLARE EXIT HANDLER FOR NOT FOUND
              BEGIN
                  ROLLBACK;
               SELECT o_success AS o_success, o_memberid AS o_memberid, o_message AS o_message;
              END;
              
            START TRANSACTION;
                
                SET o_success = FALSE;	
            SET o_message = 'An error occur.';
                ## Select if exist
                        SELECT id_member INTO o_memberid FROM `es_member` WHERE usersession = i_usersession AND id_member = i_id; 
            IF o_memberid IS NOT NULL THEN 
                        UPDATE `es_member` SET usersession = '' WHERE id_member = o_memberid;
                        SET o_success = TRUE;
                        SET o_message = '';
            ELSE
                SET o_success = FALSE;
            END IF;   
                   
                COMMIT;
                SELECT o_success AS o_success, o_memberid AS o_memberid, o_message AS o_message;
        END ",
    
    "es_sp_Payment_order" => 
        "
        CREATE DEFINER=`root`@`localhost` PROCEDURE `es_sp_Payment_order`(
            IN i_payment_type INT(10),
            IN i_invoice_no VARCHAR(150),
            IN i_total_amount DECIMAL(15,4),
            IN i_ip VARCHAR(50),
            IN i_member_id INT(10),
            IN i_product_string TEXT,
            IN i_product_count INT,
            IN i_data_response TEXT,
            IN i_transaction_id VARCHAR(1024)
            
        )
        BEGIN
            # ACCESSIBLE VARIABLES
            DECLARE o_success BOOLEAN;
            DECLARE	o_message VARCHAR(50);
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
                SET v_order_product_status = 3;
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
                    
                    SET v_product_external_charge = (v_total/i_total_amount) * v_external_charge;
                    SET o_message = 'Error Code: Payment007';
                    SET v_net = v_total - v_product_external_charge;
                    INSERT INTO `es_order_product` 
                    (`order_id`,`seller_id`,`product_id`,`order_quantity`,`price`,`handling_fee`,`total`,`product_item_id`,`status`, `payment_method_charge`, `net`)
                    VALUES
                    (v_order_id,v_seller_id,v_product_id,v_quantity,v_price,v_tax,v_total,v_product_item,v_order_product_status,v_product_external_charge,v_net);
                    
                    SET o_message = 'Error Code: Payment008';
                    SELECT `id_order_product` INTO v_order_product_id FROM `es_order_product` 
                    WHERE order_id = v_order_id  ORDER BY `id_order_product` DESC LIMIT 1;
                    
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
        END",
    
    "es_sp_Remove_draft" => 
        "
        CREATE DEFINER=`root`@`localhost` PROCEDURE `es_sp_Remove_draft`(
            IN i_userid INT(10),
            IN i_productid  INT(10)
               )
        BEGIN
            DECLARE o_success BOOLEAN;
            DECLARE v_productid VARCHAR(50);  
            DECLARE o_message VARCHAR(50);  
         
            
            DECLARE EXIT HANDLER FOR SQLEXCEPTION
              BEGIN
                  ROLLBACK;
               SELECT o_success AS o_success,  o_message AS o_message;
              END;
              
            DECLARE EXIT HANDLER FOR NOT FOUND
              BEGIN
                  ROLLBACK;
               SELECT o_success AS o_success,   o_message AS o_message;
              END;
              
            START TRANSACTION;
                
            SET o_success = FALSE;	
            SET o_message = 'This item does not belong to you!';
                ## Select if exist
                 
                        SELECT id_product INTO v_productid FROM `es_product` WHERE id_product = i_productid AND `member_id` = i_userid; 
            IF v_productid IS NOT NULL THEN 
                        UPDATE `es_product` SET `is_delete` = 1 WHERE `id_product` = v_productid; 
                        SET o_success = TRUE;
                        SET o_message = 'Remove Success!';
            ELSE
                SET o_success = FALSE;
            END IF;   
                   
                COMMIT;
               
                SELECT o_success AS o_success, o_message AS o_message;
            
        END ",
        
    "es_sp_Signup_admin" =>
        "
        CREATE DEFINER=`root`@`localhost` PROCEDURE `es_sp_Signup_admin`(
            IN i_username VARCHAR(255),
            IN i_password VARCHAR(255),
            IN i_fullname VARCHAR(255)
        )
        BEGIN
            DECLARE v_pass VARCHAR(255);
            
            START TRANSACTION;
            
            SELECT REVERSE(PASSWORD(CONCAT(MD5(i_username),SHA1(i_password)))) INTO v_pass;
            INSERT INTO `es_admin_member` (`username`, `password`,`fullname`)
            VALUES (i_username, v_pass, i_fullname)
            ON DUPLICATE KEY UPDATE username=i_username, `password`=v_pass, fullname=i_fullname;
            COMMIT;
            SELECT id_admin FROM `es_admin_member` WHERE username = i_username; 
        END ",
        
    "es_sp_Signup_user" => 
       "
        CREATE DEFINER=`root`@`localhost` PROCEDURE `es_sp_Signup_user`(
            IN i_username VARCHAR(255),
            IN i_password VARCHAR(255),
            IN i_contactno VARCHAR(45),
            IN i_email VARCHAR(255)
        )
        BEGIN
            DECLARE v_pass VARCHAR(255);
            

            START TRANSACTION;
            
            SELECT reverse(PASSWORD(concat(md5(i_username),sha1(i_password)))) into v_pass;

            INSERT INTO `es_member` (`username`, `password`, `contactno`, `email`, `datecreated`)
            VALUES (i_username, v_pass, i_contactno, i_email,NOW())
            ON DUPLICATE KEY UPDATE username=i_username, `password`=v_pass, contactno=i_contactno, email=i_email;

            COMMIT;

            SELECT id_member FROM es_member WHERE username = i_username; 

        END ",
        
    "es_sp_storeVerifcode" => 
        "
        CREATE DEFINER=`root`@`localhost` PROCEDURE `es_sp_storeVerifcode`(
            IN i_memberid INT(10),
            IN i_emailcode VARCHAR(255),
            IN i_mobilecode VARCHAR(255),
            IN i_mobile INT(4),
            IN i_email INT(4)
        )
        BEGIN
            DECLARE z_memberid INT(10);
            DECLARE z_time INT(10);
            DECLARE z_emailcount INT(4);
            DECLARE z_mobilecount INT(4);

            SELECT member_id, TIMESTAMPDIFF(MINUTE,`date`,NOW()), emailcount, mobilecount
                INTO z_memberid, z_time, z_emailcount, z_mobilecount
            FROM `es_verifcode`
            WHERE member_id = i_memberid;
            
            IF z_memberid IS NULL THEN
                SET z_mobilecount = 0;
                SET z_emailcount = 0;
            END IF;

            #If 30 minutes passed, reset email and mobile count
            IF Z_TIME > 30 THEN
                SET z_emailcount = 1;
                SET z_mobilecount = 1;
            ELSE
                IF i_mobile = 1 THEN
                    SET z_mobilecount = z_mobilecount + 1;
                END IF;

                IF i_email = 1 THEN
                    SET z_emailcount = z_emailcount + 1;
                END IF;

            END IF;

            START TRANSACTION;

            IF i_mobile = 1 OR i_email = 1 THEN

                INSERT INTO `es_verifcode` (member_id, emailcode, mobilecode, `date`, emailcount, mobilecount)
                    VALUES (i_memberid, i_emailcode, i_mobilecode, NOW(), z_emailcount, z_mobilecount)
                    ON DUPLICATE KEY UPDATE emailcode = i_emailcode, mobilecode = i_mobilecode, `date` = NOW(), emailcount = z_emailcount, mobilecount = z_mobilecount;
            
            END IF;

            COMMIT;

        END ",
        
    "es_sp_updateTransactionStatus" =>
        "
        CREATE DEFINER=`root`@`localhost` PROCEDURE `es_sp_updateTransactionStatus`(
            IN i_status INT(3),
            IN i_orderproductid INT(10),
            IN i_orderid INT(10),
            IN i_invoiceno VARCHAR(45),
            IN i_memberid INT(10)
        )
        BEGIN
            DECLARE z_orderproductid INT(10);
            DECLARE z_statusid INT(10);
            DECLARE o_success BOOLEAN;
            DECLARE o_message VARCHAR(50);
            DECLARE z_historylog VARCHAR(45);
            
            DECLARE EXIT HANDLER FOR SQLEXCEPTION
              BEGIN
                  ROLLBACK;
                    
               SELECT o_success AS o_success, 'Error Handler 1' AS o_message;
              END;
            
            SET o_success = FALSE;
            SET o_message = 'Product Order entry not found!';
            
            #search for entry to be updated
            # If forward to seller (memberid is buyerid)
            IF i_status = 1 THEN

                SELECT op.id_order_product INTO z_statusid
                FROM es_order_product op
                INNER JOIN es_order o
                    ON o.id_order = op.order_id AND op.order_id = i_orderid AND op.id_order_product = i_orderproductid AND o.invoice_no = i_invoiceno
                WHERE op.`status` = 0 AND o.buyer_id = i_memberid
                LIMIT 1;

                SET z_historylog = 'FORWARDED';
            # If return to buyer (memberid is sellerid) or Cash On Delivery
            ELSEIF i_status = 2 OR i_status = 3 THEN

                SELECT op.id_order_product INTO z_statusid
                FROM es_order_product op
                INNER JOIN es_order o
                    ON o.id_order = op.order_id AND o.invoice_no = i_invoiceno
                WHERE op.`status` = 0 AND op.id_order_product = i_orderproductid AND op.order_id = i_orderid AND op.seller_id = i_memberid
                LIMIT 1;

                CASE i_status
                    WHEN 2 THEN SET z_historylog = 'RETURNED';
                    WHEN 3 THEN SET z_historylog = 'COD - COMPLETED';
                    ELSE SET z_historylog = '';
                END CASE;
                
            END IF;

            #IF order product entry found, execute
            IF z_statusid IS NOT NULL THEN

                START TRANSACTION;

                    #update es_order_product status
                    UPDATE es_order_product
                    SET `status` = i_status, is_reject = 0
                    WHERE id_order_product = i_orderproductid AND order_id = i_orderid;

                    #create history log
                    INSERT INTO es_order_product_history (`order_product_id`, `order_product_status`, `comment`, `date_added`)
                    VALUES (i_orderproductid, i_status, z_historylog, NOW());

                    #check es_order_product based on transaction num if all users have responded per product order entry
                    SELECT id_order_product INTO z_orderproductid
                    FROM es_order_product
                    WHERE order_id = i_orderid AND `status` = 0
                    LIMIT 1;

                    #if all order_product have user response
                    IF z_orderproductid IS NULL THEN
                        #update es_order 
                        UPDATE es_order
                        SET order_status = 1, datemodified = NOW()
                        WHERE id_order = i_orderid AND invoice_no = i_invoiceno;

                        #insert es_order history log
                        INSERT INTO es_order_history (order_id, `comment`, date_added, order_status)
                        VALUES(i_orderid, 'COMPLETED', NOW(), 1);
                        
                    END IF;

                    SET o_success = TRUE;
                    SET o_message = 'Product Order entry updated!';

                COMMIT;

            END IF;

            SELECT o_success, o_message;

        END ",
        
);
