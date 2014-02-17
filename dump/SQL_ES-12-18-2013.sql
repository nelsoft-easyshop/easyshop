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
  `id_address` int(11) NOT NULL AUTO_INCREMENT,
  `id_member` varchar(45) NOT NULL,
  `streetno` varchar(45) DEFAULT '',
  `streetname` varchar(45) DEFAULT '',
  `barangay` varchar(45) DEFAULT '',
  `citytown` varchar(45) DEFAULT '',
  `country` varchar(45) DEFAULT '',
  `postalcode` varchar(45) DEFAULT '',
  `type` varchar(45) DEFAULT '',
  `telephone` varchar(45) DEFAULT '',
  `mobile` varchar(45) DEFAULT '',
  `consignee` varchar(45) DEFAULT '',
  PRIMARY KEY (`id_address`),
  UNIQUE KEY `UNIQUE PAIR` (`id_member`,`type`),
  KEY `idx_id_member` (`id_member`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8;

/*Data for the table `es_address` */

insert  into `es_address`(`id_address`,`id_member`,`streetno`,`streetname`,`barangay`,`citytown`,`country`,`postalcode`,`type`,`telephone`,`mobile`,`consignee`) values (27,'1','32312','3123123','123123','312','12312','123','0','','',''),(50,'1','232311111112323423','hello world12','bar','baz','sadjsa','3231','1','878','2378','sam gavinio');

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `es_attr` */

insert  into `es_attr`(`id_attr`,`cat_id`,`name`,`datatype_id`,`attr_lookuplist_id`) values (1,1,'OTHER',1,1),(2,2,'COLOR',3,10),(4,6,'OPERATING SYSTEM',4,5);

/*Table structure for table `es_attr_lookuplist` */

DROP TABLE IF EXISTS `es_attr_lookuplist`;

CREATE TABLE `es_attr_lookuplist` (
  `id_attr_lookuplist` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id_attr_lookuplist`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

/*Data for the table `es_attr_lookuplist` */

insert  into `es_attr_lookuplist`(`id_attr_lookuplist`,`name`) values (1,'OTHER'),(2,'WEIGHT'),(3,'DISPLAY SIZE'),(4,'SIZE'),(5,'OPERATING SYSTEM'),(6,'PRICE'),(7,'MEGA PIXELS'),(8,'CPU SPEED'),(9,'PRODUCT WARRANTY'),(10,'COLOR');

/*Table structure for table `es_attr_lookuplist_item` */

DROP TABLE IF EXISTS `es_attr_lookuplist_item`;

CREATE TABLE `es_attr_lookuplist_item` (
  `id_attr_lookuplist_item` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `attr_lookuplist_id` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_attr_lookuplist_item`),
  KEY `fk_es_attr_lookuplist_item_es_attr_lookuplist1_idx` (`attr_lookuplist_id`),
  CONSTRAINT `fk_es_attr_lookuplist_item_es_attr_lookuplist1` FOREIGN KEY (`attr_lookuplist_id`) REFERENCES `es_attr_lookuplist` (`id_attr_lookuplist`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

/*Data for the table `es_attr_lookuplist_item` */

insert  into `es_attr_lookuplist_item`(`id_attr_lookuplist_item`,`attr_lookuplist_id`,`name`) values (1,10,'RED'),(2,10,'BLUE'),(3,10,'YELLOW'),(4,10,'GREEN'),(5,10,'PINK'),(6,10,'WHITE'),(7,10,'BLACK'),(8,10,'GRAY'),(10,5,'iOS'),(11,5,'Android'),(12,5,'Windows'),(13,5,'Symbian');

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
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;

/*Data for the table `es_cat` */

insert  into `es_cat`(`id_cat`,`name`,`description`,`keywords`,`parent_id`,`sort_order`,`is_main`,`design1`,`design2`,`design3`) values (1,'PARENT','PARENT','PARENT',1,0,0,'','',''),(2,'Mobile and Tablets','','',1,1,0,'','',''),(3,'Computer and Laptops','','',1,2,0,'','',''),(4,'Cameras','','',1,3,0,'','',''),(5,'Home Appliances','','',1,4,0,'','',''),(6,'Mobiles','','',2,0,1,'','',''),(7,'Tablets','','',2,0,1,'','',''),(8,'Landline Phones','','',2,0,1,'','',''),(9,'Mobile Accessories','','',2,0,1,'','',''),(10,'Tablet Accessories','','',2,0,1,'','',''),(11,'Apple Phones','','',6,0,1,'','',''),(13,'Samsung Phones','','',6,0,1,'','',''),(14,'Sony Xperia Phones','','',6,0,1,'','',''),(15,'Nokia Phones','','',6,0,1,'','',''),(16,'Blackberry Phones','','',6,0,1,'','',''),(17,'Cherry Mobiles Phones','','',6,0,1,'','',''),(18,'Apple Tablets','','',7,0,1,'','',''),(19,'Samsung Tablets','','',7,0,1,'','',''),(20,'Cherry Mobile Tablets','','',7,0,1,'','',''),(21,'Coby Tablets','','',7,0,1,'','',''),(22,'Haipad Tablets','','',7,0,1,'','',''),(23,'Batteries and Chargers','','',9,0,1,'','',''),(24,'Cases and Covers','','',9,0,1,'','',''),(25,'Headsets','','',9,0,1,'','',''),(26,'Cases and Covers','','',10,0,1,'','',''),(27,'Speakers','','',10,0,1,'','',''),(28,'Laptops','','',3,0,1,'','',''),(29,'Essential Laptops','','',28,0,1,'','',''),(30,'Professional Laptops','','',28,0,1,'','',''),(31,'Gaming Laptops','','',28,0,1,'','',''),(32,'Ultraportable Laptops','','',28,0,1,'','',''),(33,'Entertainment Laptops','','',28,0,1,'','',''),(34,'Windows 8 Laptops','','',28,0,1,'','','');

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
  `usersession` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL DEFAULT '',
  `contactno` varchar(45) NOT NULL DEFAULT '',
  `is_contactno_verify` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `email` varchar(255) NOT NULL DEFAULT '',
  `is_email_verify` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `gender` varchar(1) NOT NULL DEFAULT '0',
  `birthday` date NOT NULL DEFAULT '0000-00-00',
  `address_id` int(10) unsigned NOT NULL DEFAULT '0',
  `datecreated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lastmodifieddate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_login_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_login_ip` varchar(45) NOT NULL DEFAULT '',
  `login_count` int(10) unsigned NOT NULL DEFAULT '0',
  `rank` int(10) unsigned NOT NULL DEFAULT '0',
  `member_type_id` int(10) unsigned NOT NULL DEFAULT '0',
  `fullname` varchar(255) DEFAULT '',
  `nickname` varchar(255) DEFAULT '',
  `imgurl` varchar(255) DEFAULT '',
  `region` varchar(45) DEFAULT '',
  PRIMARY KEY (`id_member`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `es_member` */

insert  into `es_member`(`id_member`,`username`,`usersession`,`password`,`contactno`,`is_contactno_verify`,`email`,`is_email_verify`,`gender`,`birthday`,`address_id`,`datecreated`,`lastmodifieddate`,`last_login_datetime`,`last_login_ip`,`login_count`,`rank`,`member_type_id`,`fullname`,`nickname`,`imgurl`,`region`) values (1,'admin','62c47a865919ca7a58c8a2e831e7c0e1f4f9e796','191CC6AD11F4DF69396374AA9F8693991784D1D8*','09152801591',1,'samuel_gavinio55@yahoo.com',0,'F','2013-12-08',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','2013-12-17 17:08:09','::1',1,0,0,'sam1','sam22','assets/user/1_admin','NCR'),(2,'admin2',NULL,'191CC6AD11F4DF69396374AA9F8693991784D1D8*','23456',0,'',0,'0','0000-00-00',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','',0,0,0,'','','','NCR');

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
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

/*Data for the table `es_product` */

insert  into `es_product`(`id_product`,`name`,`sku`,`brief`,`description`,`keywords`,`price`,`brand_id`,`cat_id`,`style_id`,`is_real`,`is_delete`,`is_new`,`is_hot`,`is_promote`,`member_id`,`member_memo`,`createddate`,`lastmodifieddate`,`clickcount`) values (23,'Title','2858381','Brief','Description','KEYWORD',10000,1,11,1,0,0,0,0,0,1,'','2013-12-18 11:50:47','2013-12-18 11:50:47',0),(24,'title','sdasdasd','adasda','asdasd','KEYWORD',233,1,11,1,0,0,0,0,0,1,'','2013-12-18 12:11:48','2013-12-18 12:11:48',0),(25,'title','sdasdasd','adasda','asdasd','KEYWORD',233,1,11,1,0,0,0,0,0,1,'','2013-12-18 12:14:00','2013-12-18 12:14:00',0);

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
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;

/*Data for the table `es_product_attr` */

insert  into `es_product_attr`(`id_product_attr`,`product_id`,`attr_id`,`attr_value`,`attr_price`) values (31,23,2,'GREEN',0),(32,23,1,'Casing',300),(33,23,1,'Charge',100),(34,24,2,'RED',0),(35,24,4,'iOS',0),(36,25,2,'RED',0),(37,25,4,'iOS',0);

/*Table structure for table `es_product_image` */

DROP TABLE IF EXISTS `es_product_image`;

CREATE TABLE `es_product_image` (
  `id_product_image` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_image_path` text NOT NULL,
  `product_image_type` varchar(1024) DEFAULT NULL,
  `product_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_product_image`),
  KEY `fk_es_product_es_product1` (`product_id`),
  CONSTRAINT `fk_es_product_es_product1` FOREIGN KEY (`product_id`) REFERENCES `es_product` (`id_product`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

/*Data for the table `es_product_image` */

insert  into `es_product_image`(`id_product_image`,`product_image_path`,`product_image_type`,`product_id`) values (14,'assets/product/1_20131218-45046.jpg','image/jpeg',23),(15,'assets/product/1_20131218-45046.png','image/png',23),(16,'assets/product/1_20131218-51148.png','image/png',24),(17,'assets/product/1_20131218-51359.png','image/png',25);

/*Table structure for table `es_school` */

DROP TABLE IF EXISTS `es_school`;

CREATE TABLE `es_school` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_member` int(11) NOT NULL,
  `schoolname` varchar(45) DEFAULT '',
  `year` year(4) DEFAULT '0000',
  `level` varchar(45) DEFAULT '',
  `count` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE PAIR` (`id_member`,`count`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `es_school` */

insert  into `es_school`(`id`,`id_member`,`schoolname`,`year`,`level`,`count`) values (1,1,'1222',2001,'1',1);

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

/*Table structure for table `es_work` */

DROP TABLE IF EXISTS `es_work`;

CREATE TABLE `es_work` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_member` int(11) NOT NULL,
  `companyname` varchar(45) DEFAULT '',
  `designation` varchar(45) DEFAULT '',
  `year` year(4) DEFAULT '0000',
  `count` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE PAIR` (`id_member`,`count`)
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=utf8;

/*Data for the table `es_work` */

insert  into `es_work`(`id`,`id_member`,`companyname`,`designation`,`year`,`count`) values (86,1,'iomkomko','87787',2155,1),(88,1,'8u8u8','h8u8',2155,2);

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

/* Function  structure for function  `SimpleCompare` */

/*!50003 DROP FUNCTION IF EXISTS `SimpleCompare` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` FUNCTION `SimpleCompare`(n INT, m INT) RETURNS varchar(20) CHARSET utf8
BEGIN
    DECLARE s VARCHAR(20);
    IF n > m THEN SET s = '>';
    ELSEIF n = m THEN SET s = '=';
    ELSE SET s = '<';
    END IF;
    SET s = CONCAT(n, ' ', s, ' ', m);
    RETURN s;
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

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
