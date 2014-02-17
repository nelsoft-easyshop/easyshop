/*
SQLyog Ultimate v11.11 (64 bit)
MySQL - 5.5.27 : Database - easyshop
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`easyshop` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `easyshop`;

/*Table structure for table `es_address` */

DROP TABLE IF EXISTS `es_address`;

CREATE TABLE `es_address` (
  `id_address` int(11) NOT NULL,
  `member_id` varchar(45) DEFAULT NULL,
  `streetno` varchar(45) DEFAULT NULL,
  `streetname` varchar(45) DEFAULT NULL,
  `barangay` varchar(45) DEFAULT NULL,
  `citytown` varchar(45) DEFAULT NULL,
  `country` varchar(45) DEFAULT NULL,
  `postalcode` varchar(45) DEFAULT NULL,
  `type` varchar(45) DEFAULT NULL,
  `telephone` varchar(45) DEFAULT NULL,
  `mobile` varchar(45) DEFAULT NULL,
  `consignee` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_address`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `es_address` */

/*Table structure for table `es_attr` */

DROP TABLE IF EXISTS `es_attr`;

CREATE TABLE `es_attr` (
  `id_attr` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cat_id` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `datatype_id` int(10) unsigned NOT NULL DEFAULT '0',
  `attr_lookuplist_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_attr`),
  KEY `fk_es_attr_es_attr_lookuplist1_idx` (`attr_lookuplist_id`),
  KEY `fk_es_attr_es_cat1_idx` (`cat_id`),
  KEY `fk_es_attr_es_datatype1_idx` (`datatype_id`),
  CONSTRAINT `fk_es_attr_es_attr_lookuplist1` FOREIGN KEY (`attr_lookuplist_id`) REFERENCES `es_attr_lookuplist` (`id_attr_lookuplist`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_es_attr_es_cat1` FOREIGN KEY (`cat_id`) REFERENCES `es_cat` (`id_cat`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_es_attr_es_datatype1` FOREIGN KEY (`datatype_id`) REFERENCES `es_datatype` (`id_datatype`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `es_attr` */

/*Table structure for table `es_attr_lookuplist` */

DROP TABLE IF EXISTS `es_attr_lookuplist`;

CREATE TABLE `es_attr_lookuplist` (
  `id_attr_lookuplist` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id_attr_lookuplist`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

/*Data for the table `es_attr_lookuplist` */

insert  into `es_attr_lookuplist`(`id_attr_lookuplist`,`name`) values (1,'COLOR'),(2,'WEIGHT'),(3,'DISPLAY SIZE'),(4,'SIZE'),(5,'OPERATING SYSTEM'),(6,'PRICE'),(7,'MEGA PIXELS'),(8,'CPU SPEED'),(9,'PRODUCT WARRANTY');

/*Table structure for table `es_attr_lookuplist_item` */

DROP TABLE IF EXISTS `es_attr_lookuplist_item`;

CREATE TABLE `es_attr_lookuplist_item` (
  `id_attr_lookuplist_item` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `attr_lookuplist_id` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_attr_lookuplist_item`),
  KEY `fk_es_attr_lookuplist_item_es_attr_lookuplist1_idx` (`attr_lookuplist_id`),
  CONSTRAINT `fk_es_attr_lookuplist_item_es_attr_lookuplist1` FOREIGN KEY (`attr_lookuplist_id`) REFERENCES `es_attr_lookuplist` (`id_attr_lookuplist`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Data for the table `es_attr_lookuplist_item` */

insert  into `es_attr_lookuplist_item`(`id_attr_lookuplist_item`,`attr_lookuplist_id`,`name`) values (1,1,'RED'),(2,1,'BLUE'),(3,1,'YELLOW'),(4,1,'GREEN'),(5,1,'PINK'),(6,1,'WHITE'),(7,1,'BLACK'),(8,1,'GRAY');

/*Table structure for table `es_brand` */

DROP TABLE IF EXISTS `es_brand`;

CREATE TABLE `es_brand` (
  `id_brand` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(1023) NOT NULL DEFAULT '',
  `image` varchar(512) NOT NULL DEFAULT '',
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `url` varchar(512) NOT NULL DEFAULT '',
  `is_main` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_brand`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `es_brand` */

insert  into `es_brand`(`id_brand`,`name`,`description`,`image`,`sort_order`,`url`,`is_main`) values (1,'NOKIA','','',0,'',0),(2,'SAMSUNG','','',0,'',0),(3,'APPLE','','',0,'',0),(4,'SONY','','',0,'',0),(5,'BLACKBERRY','','',0,'',0),(6,'CHERRYMOBILE','','',0,'',0);

/*Table structure for table `es_cat` */

DROP TABLE IF EXISTS `es_cat`;

CREATE TABLE `es_cat` (
  `id_cat` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(512) NOT NULL DEFAULT '',
  `keywords` varchar(512) NOT NULL DEFAULT '',
  `parent_id` int(10) unsigned NOT NULL,
  `sort_order` tinyint(3) unsigned NOT NULL,
  `is_main` smallint(5) unsigned NOT NULL,
  `design1` varchar(255) NOT NULL DEFAULT '',
  `design2` varchar(255) NOT NULL DEFAULT '',
  `design3` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_cat`),
  KEY `fk_es_cat_es_cat1_idx` (`parent_id`),
  CONSTRAINT `fk_es_cat_es_cat1` FOREIGN KEY (`parent_id`) REFERENCES `es_cat` (`id_cat`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8;

/*Data for the table `es_cat` */

insert  into `es_cat`(`id_cat`,`name`,`description`,`keywords`,`parent_id`,`sort_order`,`is_main`,`design1`,`design2`,`design3`) values (1,'PARENT','PARENT','PARENT',1,0,0,'','',''),(2,'Clothing','','',1,0,1,'','',''),(3,'Bag and Shoes','','',1,0,1,'','',''),(4,'Digital','','',1,0,1,'','',''),(5,'Home and Garden','','',1,0,1,'','',''),(6,'Baby','','',1,0,1,'','',''),(7,'Sporting Goods','','',1,0,1,'','',''),(8,'CWomen\'s Apparel','','',2,0,0,'','',''),(9,'Pants','','',8,0,0,'','',''),(10,'Jacket','','',8,0,1,'','',''),(11,'Chiffon','','',8,0,0,'','',''),(12,'Knit wear','','',8,0,0,'','',''),(13,'shirt','','',8,0,0,'','',''),(14,'Harness','','',8,0,0,'','',''),(15,'Skirts','','',8,0,0,'','',''),(16,'Blazer','','',8,0,0,'','',''),(17,'CMen\'s Apparel','','',2,0,0,'','',''),(18,'T-shirts','','',17,0,0,'','',''),(19,'Shorts ','','',17,0,0,'','',''),(20,'Shirts','','',17,0,0,'','',''),(21,'Sweaters','','',17,0,0,'','',''),(22,'Short-sleeved','','',17,0,0,'','',''),(23,'Jackets','','',17,0,0,'','',''),(24,'Singlets','','',17,0,0,'','',''),(25,'Coats','','',17,0,0,'','',''),(26,'CUnderwear','','',2,0,0,'','',''),(27,'Pants','','',26,0,0,'','',''),(28,'Jacket','','',26,0,0,'','',''),(29,'Chiffon','','',26,0,0,'','',''),(30,'Knit wear','','',26,0,0,'','',''),(31,'shirt','','',26,0,0,'','',''),(32,'Harness','','',26,0,0,'','',''),(33,'Skirts','','',26,0,0,'','',''),(34,'Blazer','','',26,0,0,'','',''),(35,'CAccessories','','',2,0,0,'','',''),(36,'T-shirts','','',35,0,0,'','',''),(37,'Shorts ','','',35,0,0,'','',''),(38,'Shirts','','',35,0,0,'','',''),(39,'Sweaters','','',35,0,0,'','',''),(40,'Short-sleeved','','',35,0,0,'','',''),(41,'Jackets','','',35,0,0,'','',''),(42,'Singlets','','',35,0,0,'','',''),(43,'Coats','','',35,0,0,'','',''),(44,'bWomen\'s Apparel','','',3,0,0,'','',''),(45,'Pants','','',44,0,0,'','',''),(46,'Jacket','','',44,0,0,'','',''),(47,'Chiffon','','',44,0,0,'','',''),(48,'Knit wear','','',44,0,0,'','',''),(49,'shirt','','',44,0,0,'','',''),(50,'Harness','','',44,0,0,'','',''),(51,'Skirts','','',44,0,0,'','',''),(52,'Blazer','','',44,0,0,'','',''),(53,'bMen\'s Apparel','','',3,0,0,'','',''),(54,'T-shirts','','',53,0,0,'','',''),(55,'Shorts ','','',53,0,0,'','',''),(56,'Shirts','','',53,0,0,'','',''),(57,'Sweaters','','',53,0,0,'','',''),(58,'Short-sleeved','','',53,0,0,'','',''),(59,'Jackets','','',53,0,0,'','',''),(60,'Singlets','','',53,0,0,'','',''),(61,'Coats','','',53,0,0,'','',''),(62,'bsWomen\'s Apparel','','',4,0,0,'','',''),(63,'bsMen\'s Apparel','','',4,0,0,'','','');

/*Table structure for table `es_cat_img` */

DROP TABLE IF EXISTS `es_cat_img`;

CREATE TABLE `es_cat_img` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_cat` int(11) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8;

/*Data for the table `es_cat_img` */

insert  into `es_cat_img`(`id`,`id_cat`,`path`) values (1,2,'images/img_icon_clothing.png'),(2,3,'images/img_icon_bag.png'),(3,4,'images/img_icon_digital.png'),(4,5,'images/img_icon_home.png'),(5,6,'images/img_icon_baby.png'),(6,7,'images/img_icon_sportinggoods.png'),(7,8,NULL),(8,9,NULL),(9,10,NULL),(10,11,NULL),(11,12,NULL),(12,13,NULL),(13,14,NULL),(14,15,NULL),(15,16,NULL),(16,17,NULL),(17,18,NULL),(18,19,''),(19,20,NULL),(20,21,NULL),(21,22,NULL),(22,23,NULL),(23,24,NULL),(24,25,NULL),(25,26,NULL),(26,27,NULL),(27,28,NULL),(28,29,NULL),(29,30,NULL),(30,31,NULL),(31,32,NULL),(32,33,NULL),(33,34,NULL),(34,35,NULL),(35,36,NULL),(36,37,NULL),(37,38,NULL),(38,39,NULL),(39,40,NULL),(40,41,NULL),(41,42,NULL),(42,43,NULL),(43,44,NULL),(44,45,NULL),(45,46,NULL),(46,47,NULL),(47,48,NULL),(48,49,NULL),(49,50,NULL),(50,51,NULL),(51,52,NULL),(52,53,NULL),(53,54,NULL),(54,55,NULL),(55,56,NULL),(56,57,NULL),(57,58,NULL),(58,59,NULL),(59,60,NULL),(60,61,NULL),(61,62,NULL),(62,63,NULL);

/*Table structure for table `es_datatype` */

DROP TABLE IF EXISTS `es_datatype`;

CREATE TABLE `es_datatype` (
  `id_datatype` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id_datatype`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `es_datatype` */

insert  into `es_datatype`(`id_datatype`,`name`) values (1,'TEXT'),(2,'TEXTAREA'),(3,'RADIO'),(4,'SELECT'),(5,'CHECKBOX');

/*Table structure for table `es_member` */

DROP TABLE IF EXISTS `es_member`;

CREATE TABLE `es_member` (
  `id_member` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '',
  `contactno` varchar(45) NOT NULL DEFAULT '',
  `is_contactno_verify` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `email` varchar(255) NOT NULL DEFAULT '',
  `is_email_verify` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `gender` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `birthday` date NOT NULL,
  `address_id` int(10) unsigned NOT NULL DEFAULT '0',
  `datecreated` datetime NOT NULL,
  `lastmodifieddate` datetime NOT NULL,
  `last_login_datetime` datetime NOT NULL,
  `last_login_ip` varchar(45) NOT NULL DEFAULT '',
  `login_count` int(10) unsigned NOT NULL DEFAULT '0',
  `rank` int(10) unsigned NOT NULL DEFAULT '0',
  `member_type_id` int(10) unsigned NOT NULL DEFAULT '0',
  `usersession` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_member`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `es_member` */

insert  into `es_member`(`id_member`,`username`,`password`,`contactno`,`is_contactno_verify`,`email`,`is_email_verify`,`gender`,`birthday`,`address_id`,`datecreated`,`lastmodifieddate`,`last_login_datetime`,`last_login_ip`,`login_count`,`rank`,`member_type_id`,`usersession`) values (1,'admin','191CC6AD11F4DF69396374AA9F8693991784D1D8*','',0,'',0,0,'2013-12-03',0,'2013-12-25 16:34:46','2013-12-16 16:34:49','2013-12-20 12:13:15','::1',62,0,0,'');

/*Table structure for table `es_product` */

DROP TABLE IF EXISTS `es_product`;

CREATE TABLE `es_product` (
  `id_product` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `sku` varchar(45) NOT NULL DEFAULT '',
  `brief` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `keywords` varchar(1024) NOT NULL DEFAULT '',
  `price` double NOT NULL,
  `brand_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cat_id` int(10) unsigned NOT NULL DEFAULT '0',
  `style_id` int(10) unsigned NOT NULL DEFAULT '0',
  `is_real` smallint(5) unsigned NOT NULL DEFAULT '0',
  `is_delete` smallint(5) unsigned NOT NULL DEFAULT '0',
  `is_new` smallint(5) unsigned NOT NULL DEFAULT '0',
  `is_hot` smallint(5) unsigned NOT NULL DEFAULT '0',
  `is_promote` smallint(5) unsigned NOT NULL DEFAULT '0',
  `member_id` int(10) unsigned NOT NULL DEFAULT '0',
  `member_memo` varchar(1024) NOT NULL DEFAULT '',
  `createddate` datetime NOT NULL,
  `lastmodifieddate` datetime NOT NULL,
  `clickcount` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_product`),
  KEY `fk_es_product_es_cat1_idx` (`cat_id`),
  KEY `fk_es_product_es_brand1_idx` (`brand_id`),
  KEY `fk_es_product_es_style1_idx` (`style_id`),
  KEY `fk_es_product_es_member1_idx` (`member_id`),
  CONSTRAINT `fk_es_product_es_brand1` FOREIGN KEY (`brand_id`) REFERENCES `es_brand` (`id_brand`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_es_product_es_cat1` FOREIGN KEY (`cat_id`) REFERENCES `es_cat` (`id_cat`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_es_product_es_member1` FOREIGN KEY (`member_id`) REFERENCES `es_member` (`id_member`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_es_product_es_style1` FOREIGN KEY (`style_id`) REFERENCES `es_style` (`id_style`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `es_product` */

/*Table structure for table `es_product_attr` */

DROP TABLE IF EXISTS `es_product_attr`;

CREATE TABLE `es_product_attr` (
  `id_product_attr` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(10) unsigned NOT NULL DEFAULT '0',
  `attr_id` int(10) unsigned NOT NULL DEFAULT '0',
  `attr_value` text NOT NULL,
  `attr_price` double unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_product_attr`),
  KEY `product_id` (`product_id`),
  KEY `fk_es_product_attr_es_attr1_idx` (`attr_id`),
  CONSTRAINT `fk_es_product_attr_es_attr1` FOREIGN KEY (`attr_id`) REFERENCES `es_attr` (`id_attr`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_es_product_attr_es_product` FOREIGN KEY (`product_id`) REFERENCES `es_product` (`id_product`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `es_product_attr` */

/*Table structure for table `es_style` */

DROP TABLE IF EXISTS `es_style`;

CREATE TABLE `es_style` (
  `id_style` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `value` varchar(512) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_style`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `es_style` */

insert  into `es_style`(`id_style`,`name`,`value`) values (1,'Style','Style');

/* Function  structure for function  `getAllParent` */

/*!50003 DROP FUNCTION IF EXISTS `getAllParent` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`127.0.0.1` FUNCTION `getAllParent`(GivenID INT) RETURNS varchar(1024) CHARSET utf8
    DETERMINISTIC
BEGIN
    DECLARE rv VARCHAR(1024);
    DECLARE cm CHAR(1);
    DECLARE ch INT;
    SET rv = '';
    SET cm = '';
    SET ch = GivenID;
    WHILE ch > 0 DO
        SELECT IFNULL(`parent_id`,1) INTO ch FROM
        (SELECT `parent_id` FROM es_cat WHERE id_cat = ch) A;
        IF ch > 0 THEN
            SET rv = CONCAT(rv,cm,ch);
            SET cm = ',';
        END IF;
    END WHILE;
    RETURN rv;
END */$$
DELIMITER ;

/* Procedure structure for procedure `es_sp_Login_user` */

/*!50003 DROP PROCEDURE IF EXISTS  `es_sp_Login_user` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `es_sp_Login_user`(
	IN i_username VARCHAR(255),
	IN i_password  VARCHAR(255),
	in i_ip varchar(255)
       )
BEGIN
	DECLARE o_success BOOLEAN;
	DECLARE o_memberid VARCHAR(50); 
	DECLARE o_session VARCHAR(150); 
	DECLARE o_message VARCHAR(50); 
	declare v_encpass varchar(250);
	
	
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
	SET o_message = 'Invalid Email / Password';
        ## Select if exist
		select reverse(PASSword(concat(md5(i_username),sha1(i_password)))) into v_encpass;
                SELECT id_member INTO o_memberid FROM `es_member` WHERE username = i_username AND PASSWORD = v_encpass; 
	IF o_memberid IS NOT NULL THEN 
                SELECT SHA1(o_memberid + NOW()) INTO o_session;
                UPDATE `es_member` SET usersession = o_session, `login_count` = `login_count` + 1 , `last_login_ip` = i_ip ,`last_login_datetime` = NOW()  WHERE id_member = o_memberid;
             	SET o_success = TRUE;
             	set o_message = "";
	ELSE
		SET o_success = FALSE;
	END IF;   
	       
        COMMIT;
        
        
        SELECT o_success AS o_success, o_memberid AS o_memberid, o_session AS o_session, o_message AS o_message;
	
END */$$
DELIMITER ;

/* Procedure structure for procedure `es_sp_Logout_user` */

/*!50003 DROP PROCEDURE IF EXISTS  `es_sp_Logout_user` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `es_sp_Logout_user`(
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
             	SET o_message = "";
	ELSE
		SET o_success = FALSE;
	END IF;   
	       
        COMMIT;
        
        
        SELECT o_success AS o_success, o_memberid AS o_memberid, o_message AS o_message;
	
END */$$
DELIMITER ;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
