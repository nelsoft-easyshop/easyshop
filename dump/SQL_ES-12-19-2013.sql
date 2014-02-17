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
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

/*Data for the table `es_attr` */

insert  into `es_attr`(`id_attr`,`cat_id`,`name`,`datatype_id`,`attr_lookuplist_id`) values (1,1,'OTHER',1,1),(2,2,'Color',5,2),(3,3,'Brand',4,3),(4,3,'Model',4,4),(6,2,'Mpn',1,5),(7,3,'Carrier',4,6),(8,3,'Contract',4,7),(9,3,'OPERATING SYSTEM',4,8),(11,3,'STORAGE CAPACITY',4,9),(12,3,'STYLE',4,10),(13,3,'Features',5,11),(14,3,'camera',4,12),(15,3,'BUNDLE ITEMS',5,13),(16,3,'CELLULAR BAND',1,14),(17,2,'COUNTRY OF MANUFACTURER',4,15);

/*Table structure for table `es_attr_lookuplist` */

DROP TABLE IF EXISTS `es_attr_lookuplist`;

CREATE TABLE `es_attr_lookuplist` (
  `id_attr_lookuplist` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id_attr_lookuplist`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

/*Data for the table `es_attr_lookuplist` */

insert  into `es_attr_lookuplist`(`id_attr_lookuplist`,`name`) values (1,'OTHER'),(2,'COLOR'),(3,'BRAND'),(4,'MODEL'),(5,'MPN'),(6,'CARRIER'),(7,'CONTRACT'),(8,'OPERATING SYSTEM'),(9,'STORAGE CAPACITY'),(10,'STYLE'),(11,'FEATURES'),(12,'CAMERA'),(13,'BUNDLE ITEMS'),(14,'CELLULAR BAND'),(15,'COUNTRY OF MANUFACTURER');

/*Table structure for table `es_attr_lookuplist_item` */

DROP TABLE IF EXISTS `es_attr_lookuplist_item`;

CREATE TABLE `es_attr_lookuplist_item` (
  `id_attr_lookuplist_item` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `attr_lookuplist_id` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_attr_lookuplist_item`),
  KEY `fk_es_attr_lookuplist_item_es_attr_lookuplist1_idx` (`attr_lookuplist_id`),
  CONSTRAINT `fk_es_attr_lookuplist_item_es_attr_lookuplist1` FOREIGN KEY (`attr_lookuplist_id`) REFERENCES `es_attr_lookuplist` (`id_attr_lookuplist`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=178 DEFAULT CHARSET=utf8;

/*Data for the table `es_attr_lookuplist_item` */

insert  into `es_attr_lookuplist_item`(`id_attr_lookuplist_item`,`attr_lookuplist_id`,`name`) values (1,1,'OTHER'),(2,3,'Apple'),(3,3,'ASUS'),(4,3,'Audiovox'),(5,3,'BlackBerry'),(6,3,'Casio'),(7,3,'CECT'),(8,3,'Dell'),(9,3,'HP'),(10,3,'HTC'),(11,3,'Huawei'),(12,3,'Kyocera'),(13,3,'LG'),(14,3,'Motorola'),(15,3,'Nokia'),(16,3,'Palm'),(17,3,'Panasonic'),(18,3,'Pantech'),(19,3,'Philips'),(20,3,'Samsung'),(21,3,'SANYO'),(22,3,'Sharp'),(23,3,'Siemens'),(24,3,'Sony Ericsson'),(25,3,'Toshiba'),(26,3,'UTStarcom'),(27,4,'iPhone 5s'),(28,4,'iPhone 5c'),(29,4,'iPhone 5'),(30,4,'iPhone 4s'),(31,4,'BlackBerry Q10'),(32,4,'BlackBerry Z10'),(33,4,'BlackBerry Bold 9930'),(34,4,'BlackBerry Bold 9900'),(35,4,'BlackBerry Porsche P\'9981'),(36,4,'HTC Droid Incredible 4G LTE'),(37,4,'HTC Titan II'),(38,4,'HTC Evo 4G LTE'),(39,4,'HTC One'),(40,4,'HTC One S'),(41,4,'HTC One V'),(42,4,'HTC One X'),(43,4,'LG Optimus Elite'),(44,4,'Nokia Lumia 928'),(45,4,'Nokia Lumia 925'),(46,4,'Nokia Lumia 920'),(47,4,'Nokia Lumia 620'),(48,4,'Samsung Galaxy Note II'),(49,4,'Samsung Galaxy Note III'),(50,4,'Samsung Galaxy S IV'),(51,4,'Samsung Galaxy S III'),(52,6,'Unlocked'),(53,6,'Alltel'),(54,6,'Amp\'d Mobile'),(55,6,'AT&T'),(56,6,'Bell Mobility'),(57,6,'Boost Mobile'),(58,6,'Cellular One'),(59,6,'Cellular South'),(60,6,'Cricket'),(61,6,'Fido'),(62,6,'Helio'),(63,6,'MetroPCS'),(64,6,'Net10'),(65,6,'nTelos'),(66,6,'Qwest'),(67,6,'Rogers Wireless'),(68,6,'Sprint'),(69,6,'Suncom'),(70,6,'Telus'),(71,6,'T-Mobile'),(72,6,'TracFone'),(73,6,'U.S. Cellular'),(74,6,'Verizon'),(75,6,'Virgin Mobile'),(76,6,'Vodafone'),(77,7,'Without Contract'),(78,7,'With Contract'),(79,7,'Prepaid'),(80,8,'Android'),(81,8,'BlackBerry 3-7'),(82,8,'BlackBerry 10'),(83,8,'Danger OS'),(84,8,'Firefox OS'),(85,8,'HP/Palm WebOS'),(86,8,'iOS - Apple'),(87,8,'Maemo'),(88,8,'Symbian'),(89,8,'Windows Mobile'),(90,8,'Windows Phone 7'),(91,8,'Windows Phone 7.5'),(92,8,'Windows Phone 8'),(93,9,'64GB'),(94,9,'32GB'),(95,9,'16GB'),(96,9,'8GB'),(97,9,'4GB'),(98,9,'2GB'),(99,9,'1GB'),(100,9,'512MB'),(101,9,'256MB'),(102,9,'150MB'),(103,9,'128MB'),(104,9,'100MB'),(105,9,'96MB'),(106,9,'80MB'),(107,9,'64MB'),(108,9,'60MB'),(109,9,'50MB'),(110,9,'40MB'),(111,9,'32MB'),(112,9,'30MB'),(113,9,'20MB'),(114,9,'16MB'),(115,9,'10MB'),(116,9,'8MB'),(117,9,'5MB'),(118,2,'Beige'),(119,2,'Black'),(120,2,'Blue'),(121,2,'Brown'),(122,2,'Clear'),(123,2,'Gold'),(124,2,'Green'),(125,2,'Grey'),(126,2,'Orange'),(127,2,'Pink'),(128,2,'Purple'),(129,2,'Red'),(130,2,'Silver'),(131,2,'White'),(132,2,'Yellow'),(133,2,'Multi-Color'),(134,10,'Bar'),(135,10,'Flip'),(136,10,'Slider'),(137,10,'Swivel'),(138,11,'3G Data Capable'),(139,11,'Near Field Communication'),(140,11,'Music Player'),(141,11,'4G Data Capable'),(142,11,'Bluetooth Enabled'),(143,11,'GPS'),(144,11,'QWERTY Keyboard'),(145,11,'Fingerprint Sensor'),(146,11,'Global Ready'),(147,11,'Internet Browser'),(148,12,'0.1 MP'),(149,12,'0.3 MP'),(150,12,'0.5 MP'),(151,12,'1.0 MP'),(152,12,'1.2 MP'),(153,12,'1.3 MP'),(154,12,'2.0 MP'),(155,12,'3.0 MP'),(156,12,'3.1 MP'),(157,12,'3.2 MP'),(158,12,'4.0 MP'),(159,12,'5.0 MP'),(160,12,'5.1 MP'),(161,12,'8.0 MP'),(162,12,'8.1 MP'),(163,12,'10.0 MP'),(164,12,'None'),(165,13,'Armband'),(166,13,'Case'),(167,13,'Extra Cable(s)'),(168,13,'Bluetooth/Hands-Free Headset'),(169,13,'Dock or Cradle'),(170,13,'Extra Power Charger (AC)'),(171,13,'Car Charger (12V)'),(172,13,'Car Mount'),(173,13,'Extra Battery'),(174,13,'Faceplate or Decals'),(175,15,'philippines'),(177,15,'united states of america (USA)');

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `es_brand` */

insert  into `es_brand`(`id_brand`,`name`,`description`,`image`,`sort_order`,`url`,`is_main`) values (1,'','','',0,'',0);

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
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

/*Data for the table `es_cat` */

insert  into `es_cat`(`id_cat`,`name`,`description`,`keywords`,`parent_id`,`sort_order`,`is_main`,`design1`,`design2`,`design3`) values (1,'PARENT','PARENT','PARENT',1,0,0,'','',''),(2,'Cell Phones & Accessories','','',1,0,1,'','',''),(3,'Cell Phones & Smartphones','','',2,0,0,'','',''),(4,'Smart Watches','','',2,0,0,'','',''),(5,'Cell Phone Accessories','','',2,0,0,'','',''),(6,'Display Phones','','',2,0,0,'','',''),(7,'Phone Cards & SIM Cards','','',2,0,0,'','',''),(8,'Replacement Parts & Tools','','',2,0,0,'','',''),(9,'Accessory Bundles','','',5,0,0,'','',''),(10,'Armbands','','',5,0,0,'','',''),(11,'Audio Docks & Speakers','','',5,0,0,'','',''),(12,'Batteries','','',5,0,0,'','',''),(13,'Cables & Adapters','','',5,0,0,'','',''),(14,'Car Speakerphones','','',5,0,0,'','',''),(15,'Cases, Covers & Skins','','',5,0,0,'','',''),(16,'Chargers & Cradles','','',5,0,0,'','',''),(17,'FM Transmitters','','',5,0,0,'','',''),(18,'Headsets','','',5,0,0,'','',''),(19,'Manuals & Guides','','',5,0,0,'','',''),(20,'Memory Cards','','',5,0,0,'','',''),(21,'Memory Card Readers & Adapters','','',5,0,0,'','',''),(22,'Mounts & Holders','','',5,0,0,'','',''),(23,'Screen Protectors','','',5,0,0,'','',''),(24,'Signal Boosters','','',5,0,0,'','',''),(25,'Straps & Charms','','',5,0,0,'','',''),(26,'Styluses','','',5,0,0,'','',''),(27,'Refills & Top Ups','','',7,0,0,'','',''),(28,'SIM Cards','','',7,0,0,'','',''),(29,'SIM Card Readers','','',7,0,0,'','','');

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

insert  into `es_member`(`id_member`,`username`,`usersession`,`password`,`contactno`,`is_contactno_verify`,`email`,`is_email_verify`,`gender`,`birthday`,`address_id`,`datecreated`,`lastmodifieddate`,`last_login_datetime`,`last_login_ip`,`login_count`,`rank`,`member_type_id`,`fullname`,`nickname`,`imgurl`,`region`) values (1,'Admin','62c47a865919ca7a58c8a2e831e7c0e1f4f9e796','191CC6AD11F4DF69396374AA9F8693991784D1D8*','09152801591',1,'samuel_gavinio55@yahoo.com',0,'F','2013-12-08',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','2013-12-17 17:08:09','::1',1,0,0,'sam1','sam22','assets/user/1_admin','NCR');

/*Table structure for table `es_product` */

DROP TABLE IF EXISTS `es_product`;

CREATE TABLE `es_product` (
  `id_product` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `sku` varchar(45) NOT NULL DEFAULT '',
  `brief` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `condition` varchar(255) DEFAULT '',
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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `es_product` */

insert  into `es_product`(`id_product`,`name`,`sku`,`brief`,`description`,`condition`,`keywords`,`price`,`brand_id`,`cat_id`,`style_id`,`is_real`,`is_delete`,`is_new`,`is_hot`,`is_promote`,`member_id`,`member_memo`,`createddate`,`lastmodifieddate`,`clickcount`) values (3,'Galaxy','987654321','This is my First item','This is my decription for my First item<br>','New other (see details)','KEYWORD',9000,1,3,1,0,0,0,0,0,1,'','2013-12-19 11:25:19','2013-12-19 11:25:19',0),(4,'Galaxy Car','01029384856','This is my Second item','This is my decription for my Second item<br>','New other (see details)','KEYWORD',9000,1,3,1,0,0,0,0,0,1,'','2013-12-19 11:27:40','2013-12-19 11:27:40',0),(5,'iPhone Selling with TV and E-Fan','01029384856','This is my Thirid item','This is my decription for my Thirid item<br>','New','KEYWORD',100000,1,3,1,0,0,0,0,0,1,'','2013-12-19 11:30:27','2013-12-19 11:30:27',0),(6,'Lace with zoo','008808080808','ikaw na bahala','judge it :D <br>','Manufacturer refurbished','KEYWORD',500,1,9,1,0,0,0,0,0,1,'','2013-12-19 11:49:40','2013-12-19 11:49:40',0),(7,'Title','createSmallSize','Description','createSmallSize<br>','Manufacturer refurbished','KEYWORD',200,1,9,1,0,0,0,0,0,1,'','2013-12-19 15:18:45','2013-12-19 15:18:45',0);

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
) ENGINE=InnoDB AUTO_INCREMENT=112 DEFAULT CHARSET=utf8;

/*Data for the table `es_product_attr` */

insert  into `es_product_attr`(`id_product_attr`,`product_id`,`attr_id`,`attr_value`,`attr_price`) values (29,3,14,'10.0 mp',0),(30,3,7,'Unlocked',0),(31,3,2,'Beige',0),(32,3,2,'Black',0),(33,3,2,'Purple',0),(34,3,8,'Prepaid',0),(35,3,17,'United states of america (usa)',0),(36,3,13,'Gps',0),(37,3,4,'Samsung galaxy note ii',0),(38,3,6,'123456789',0),(39,3,9,'Android',0),(40,3,11,'64gb',0),(41,3,12,'Bar',0),(42,3,1,'Casing',100),(43,4,3,'Philips',0),(44,4,14,'0.1 mp',0),(45,4,7,'Cellular one',0),(46,4,2,'Beige',0),(47,4,2,'Black',0),(48,4,2,'Blue',0),(49,4,2,'Brown',0),(50,4,2,'Clear',0),(51,4,2,'Gold',0),(52,4,2,'Green',0),(53,4,2,'Grey',0),(54,4,2,'Orange',0),(55,4,2,'Pink',0),(56,4,2,'Purple',0),(57,4,2,'Red',0),(58,4,2,'Silver',0),(59,4,2,'White',0),(60,4,2,'Yellow',0),(61,4,2,'Multi-color',0),(62,4,8,'Prepaid',0),(63,4,17,'United states of america (usa)',0),(64,4,13,'3g data capable',0),(65,4,13,'Near field communication',0),(66,4,13,'Music player',0),(67,4,13,'4g data capable',0),(68,4,13,'Bluetooth enabled',0),(69,4,13,'Gps',0),(70,4,13,'Qwerty keyboard',0),(71,4,13,'Fingerprint sensor',0),(72,4,13,'Global ready',0),(73,4,13,'Internet browser',0),(74,4,4,'Lg optimus elite',0),(75,4,6,'9998888654',0),(76,4,9,'Android',0),(77,4,11,'1gb',0),(78,4,12,'Slider',0),(79,4,1,'Casing',200),(80,4,1,'Lace',159),(81,5,3,'Apple',0),(82,5,15,'Extra battery',0),(83,5,14,'10.0 mp',0),(84,5,7,'Virgin mobile',0),(85,5,2,'Black',0),(86,5,8,'Without contract',0),(87,5,17,'Philippines',0),(88,5,13,'3g data capable',0),(89,5,13,'Near field communication',0),(90,5,13,'Music player',0),(91,5,13,'4g data capable',0),(92,5,13,'Bluetooth enabled',0),(93,5,13,'Gps',0),(94,5,13,'Qwerty keyboard',0),(95,5,13,'Fingerprint sensor',0),(96,5,13,'Global ready',0),(97,5,13,'Internet browser',0),(98,5,4,'Iphone 5s',0),(99,5,6,'00000000000000',0),(100,5,9,'Ios - apple',0),(101,5,11,'64gb',0),(102,5,12,'Bar',0),(103,5,1,'Casing',200),(104,5,1,'Lace',159),(105,5,1,'motolite Charger',2000),(106,6,2,'Red',0),(107,6,17,'Philippines',0),(108,6,6,'090909',0),(109,7,2,'Red',0),(110,7,17,'Philippines',0),(111,7,6,'12345678',0);

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `es_product_image` */

insert  into `es_product_image`(`id_product_image`,`product_image_path`,`product_image_type`,`product_id`) values (2,'./assets/product/3_1_20131219/3_1_201312190.png','image/png',3),(3,'./assets/product/4_1_20131219/4_1_201312190.jpg','image/jpeg',4),(4,'./assets/product/5_1_20131219/5_1_201312190.jpg','image/jpeg',5),(5,'./assets/product/6_1_20131219/6_1_201312190.jpg','image/jpeg',6),(6,'./assets/product/7_1_20131219/7_1_201312190.jpg','image/jpeg',7);

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
