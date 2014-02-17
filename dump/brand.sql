/*
SQLyog Ultimate v11.11 (64 bit)
MySQL - 5.6.15-log : Database - easyshop
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
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

/*Data for the table `es_brand` */

insert  into `es_brand`(`id_brand`,`name`,`description`,`image`,`sort_order`,`url`,`is_main`) values (2,'NOKIA','','',0,'',0),(5,'NOKIA','','',0,'',0),(6,'SONY','','',0,'',0),(7,'BLACKBERRY','','',0,'',0),(8,'CHERRYMOBILE','','',0,'',0),(23,'SAMSUNG','','',0,'',0);

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
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=utf8;

/*Data for the table `es_cat` */

insert  into `es_cat`(`id_cat`,`name`,`description`,`keywords`,`parent_id`,`sort_order`,`is_main`,`design1`,`design2`,`design3`) values (1,'PARENT','PARENT','PARENT',1,0,0,'','',''),(2,'Cell Phones & Accessories','','',1,0,1,'','',''),(3,'Cell Phones & Smartphones','','',2,0,1,'','',''),(4,'Smart Watches','','',2,0,1,'','',''),(5,'Cell Phone Accessories','','',2,0,1,'','',''),(6,'Display Phones','','',2,0,1,'','',''),(7,'Phone Cards & SIM Cards','','',2,0,1,'','',''),(8,'Replacement Parts & Tools','','',2,0,1,'','',''),(9,'Accessory Bundles','','',5,0,1,'','',''),(10,'Armbands','','',5,0,1,'','',''),(11,'Audio Docks & Speakers','','',5,0,1,'','',''),(12,'Batteries','','',5,0,1,'','',''),(13,'Cables & Adapters','','',5,0,1,'','',''),(14,'Car Speakerphones','','',5,0,1,'','',''),(15,'Cases, Covers & Skins','','',5,0,1,'','',''),(16,'Chargers & Cradles','','',5,0,1,'','',''),(17,'FM Transmitters','','',5,0,1,'','',''),(18,'Headsets','','',5,0,1,'','',''),(19,'Manuals & Guides','','',5,0,1,'','',''),(20,'Memory Cards','','',5,0,1,'','',''),(21,'Memory Card Readers & Adapters','','',5,0,1,'','',''),(22,'Mounts & Holders','','',5,0,1,'','',''),(23,'Screen Protectors','','',5,0,1,'','',''),(24,'Signal Boosters','','',5,0,1,'','',''),(25,'Straps & Charms','','',5,0,1,'','',''),(26,'Styluses','','',5,0,1,'','',''),(27,'Refills & Top Ups','','',7,0,1,'','',''),(28,'SIM Cards','','',7,0,1,'','',''),(29,'SIM Card Readers','','',7,0,1,'','',''),(30,'Clothing','','',1,0,1,'','',''),(31,'Bag and Shoes','','',1,0,1,'','',''),(32,'Digital','','',1,0,1,'','',''),(33,'Home and Garden','','',1,0,1,'','',''),(34,'Baby','','',1,0,1,'','',''),(35,'Sporting Goods','','',1,0,1,'','',''),(36,'CWomen\'s Apparel','','',30,0,1,'','',''),(37,'Pants','','',36,0,1,'','',''),(38,'Jacket','','',36,0,1,'','',''),(39,'Chiffon','','',36,0,1,'','',''),(40,'Knit wear','','',36,0,1,'','',''),(41,'shirt','','',36,0,1,'','',''),(42,'Harness','','',36,0,1,'','',''),(43,'Skirts','','',36,0,1,'','',''),(44,'Blazer','','',36,0,1,'','',''),(45,'CMen\'s Apparel','','',30,0,1,'','',''),(46,'T-shirts','','',45,0,1,'','',''),(47,'Shorts','','',45,0,1,'','',''),(48,'Shirts','','',45,0,1,'','',''),(49,'Sweaters','','',45,0,1,'','',''),(50,'Short-sleeved','','',45,0,1,'','',''),(51,'Jackets','','',45,0,1,'','',''),(52,'Singlets','','',45,0,1,'','',''),(53,'Coats','','',45,0,1,'','',''),(54,'CUnderwear','','',30,0,1,'','',''),(55,'Pants','','',54,0,1,'','',''),(56,'Jacket','','',54,0,1,'','',''),(57,'Chiffon','','',54,0,1,'','',''),(58,'Knit wear','','',54,0,1,'','',''),(59,'shirt','','',54,0,1,'','',''),(60,'Harness','','',54,0,1,'','',''),(61,'Skirts','','',54,0,1,'','',''),(62,'Blazer','','',54,0,1,'','',''),(63,'CAccessories','','',30,0,1,'','',''),(64,'T-shirts','','',63,0,1,'','',''),(65,'Shorts','','',63,0,1,'','',''),(66,'Shirts','','',63,0,1,'','',''),(67,'Sweaters','','',63,0,1,'','',''),(68,'Short-sleeved','','',63,0,1,'','',''),(69,'Jackets','','',63,0,1,'','',''),(70,'Singlets','','',63,0,1,'','',''),(71,'Coats','','',63,0,1,'','',''),(72,'Women\'s Apparel','','',31,0,1,'','',''),(73,'Pants','','',72,0,1,'','',''),(74,'Jacket','','',72,0,1,'','',''),(75,'Chiffon','','',72,0,1,'','',''),(76,'Knit wear','','',72,0,1,'','',''),(77,'shirt','','',72,0,1,'','',''),(78,'Harness','','',72,0,1,'','',''),(79,'Skirts','','',72,0,1,'','',''),(80,'Blazer','','',72,0,1,'','',''),(81,'Men\'s Apparel','','',31,0,1,'','',''),(82,'T-shirts','','',81,0,1,'','',''),(83,'Shorts','','',81,0,1,'','',''),(84,'Shirts','','',81,0,1,'','',''),(85,'Sweaters','','',81,0,1,'','',''),(86,'Short-sleeved','','',81,0,1,'','',''),(87,'Jackets','','',81,0,1,'','',''),(88,'Singlets','','',81,0,1,'','',''),(89,'Coats','','',81,0,1,'','','');

/*Table structure for table `es_cat_brand` */

DROP TABLE IF EXISTS `es_cat_brand`;

CREATE TABLE `es_cat_brand` (
  `id_cat_brand` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cat_id` int(10) unsigned NOT NULL DEFAULT '0',
  `brand_id` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_cat_brand`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

/*Data for the table `es_cat_brand` */

insert  into `es_cat_brand`(`id_cat_brand`,`cat_id`,`brand_id`) values (1,3,2),(10,3,5),(11,3,6),(12,3,7),(13,3,8);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
