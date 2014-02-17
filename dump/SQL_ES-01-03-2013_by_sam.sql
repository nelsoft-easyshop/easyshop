CREATE DATABASE  IF NOT EXISTS `easyshop` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `easyshop`;
-- MySQL dump 10.13  Distrib 5.5.16, for Win32 (x86)
--
-- Host: localhost    Database: easyshop
-- ------------------------------------------------------
-- Server version	5.5.27

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `es_address`
--

DROP TABLE IF EXISTS `es_address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `es_address`
--

LOCK TABLES `es_address` WRITE;
/*!40000 ALTER TABLE `es_address` DISABLE KEYS */;
INSERT INTO `es_address` VALUES (27,'1','32312','3123123','123123','312','12312','123','0','','',''),(50,'1','232311111112323423','hello world12','bar','baz','sadjsa','3231','1','878','2378','sam gavinio');
/*!40000 ALTER TABLE `es_address` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `es_attr`
--

DROP TABLE IF EXISTS `es_attr`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `es_attr`
--

LOCK TABLES `es_attr` WRITE;
/*!40000 ALTER TABLE `es_attr` DISABLE KEYS */;
INSERT INTO `es_attr` VALUES (1,1,'OTHER',1,1),(2,2,'Color',5,2),(3,3,'Brand',4,3),(4,3,'Model',4,4),(6,2,'Mpn',1,5),(7,3,'Carrier',4,6),(8,3,'Contract',4,7),(9,3,'OPERATING SYSTEM',4,8),(11,3,'STORAGE CAPACITY',4,9),(12,3,'STYLE',4,10),(13,3,'Features',5,11),(14,3,'camera',4,12),(15,3,'BUNDLE ITEMS',5,13),(16,3,'CELLULAR BAND',1,14),(17,2,'COUNTRY OF MANUFACTURER',4,15),(18,37,'COLOR',5,1),(19,37,'SIZE',3,1);
/*!40000 ALTER TABLE `es_attr` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `es_attr_lookuplist`
--

DROP TABLE IF EXISTS `es_attr_lookuplist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `es_attr_lookuplist` (
  `id_attr_lookuplist` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id_attr_lookuplist`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `es_attr_lookuplist`
--

LOCK TABLES `es_attr_lookuplist` WRITE;
/*!40000 ALTER TABLE `es_attr_lookuplist` DISABLE KEYS */;
INSERT INTO `es_attr_lookuplist` VALUES (1,'OTHER'),(2,'COLOR'),(3,'BRAND'),(4,'MODEL'),(5,'MPN'),(6,'CARRIER'),(7,'CONTRACT'),(8,'OPERATING SYSTEM'),(9,'STORAGE CAPACITY'),(10,'STYLE'),(11,'FEATURES'),(12,'CAMERA'),(13,'BUNDLE ITEMS'),(14,'CELLULAR BAND'),(15,'COUNTRY OF MANUFACTURER');
/*!40000 ALTER TABLE `es_attr_lookuplist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `es_attr_lookuplist_item`
--

DROP TABLE IF EXISTS `es_attr_lookuplist_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `es_attr_lookuplist_item` (
  `id_attr_lookuplist_item` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `attr_lookuplist_id` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_attr_lookuplist_item`),
  KEY `fk_es_attr_lookuplist_item_es_attr_lookuplist1_idx` (`attr_lookuplist_id`),
  CONSTRAINT `fk_es_attr_lookuplist_item_es_attr_lookuplist1` FOREIGN KEY (`attr_lookuplist_id`) REFERENCES `es_attr_lookuplist` (`id_attr_lookuplist`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=178 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `es_attr_lookuplist_item`
--

LOCK TABLES `es_attr_lookuplist_item` WRITE;
/*!40000 ALTER TABLE `es_attr_lookuplist_item` DISABLE KEYS */;
INSERT INTO `es_attr_lookuplist_item` VALUES (1,1,'OTHER'),(2,3,'Apple'),(3,3,'ASUS'),(4,3,'Audiovox'),(5,3,'BlackBerry'),(6,3,'Casio'),(7,3,'CECT'),(8,3,'Dell'),(9,3,'HP'),(10,3,'HTC'),(11,3,'Huawei'),(12,3,'Kyocera'),(13,3,'LG'),(14,3,'Motorola'),(15,3,'Nokia'),(16,3,'Palm'),(17,3,'Panasonic'),(18,3,'Pantech'),(19,3,'Philips'),(20,3,'Samsung'),(21,3,'SANYO'),(22,3,'Sharp'),(23,3,'Siemens'),(24,3,'Sony Ericsson'),(25,3,'Toshiba'),(26,3,'UTStarcom'),(27,4,'iPhone 5s'),(28,4,'iPhone 5c'),(29,4,'iPhone 5'),(30,4,'iPhone 4s'),(31,4,'BlackBerry Q10'),(32,4,'BlackBerry Z10'),(33,4,'BlackBerry Bold 9930'),(34,4,'BlackBerry Bold 9900'),(35,4,'BlackBerry Porsche P\'9981'),(36,4,'HTC Droid Incredible 4G LTE'),(37,4,'HTC Titan II'),(38,4,'HTC Evo 4G LTE'),(39,4,'HTC One'),(40,4,'HTC One S'),(41,4,'HTC One V'),(42,4,'HTC One X'),(43,4,'LG Optimus Elite'),(44,4,'Nokia Lumia 928'),(45,4,'Nokia Lumia 925'),(46,4,'Nokia Lumia 920'),(47,4,'Nokia Lumia 620'),(48,4,'Samsung Galaxy Note II'),(49,4,'Samsung Galaxy Note III'),(50,4,'Samsung Galaxy S IV'),(51,4,'Samsung Galaxy S III'),(52,6,'Unlocked'),(53,6,'Alltel'),(54,6,'Amp\'d Mobile'),(55,6,'AT&T'),(56,6,'Bell Mobility'),(57,6,'Boost Mobile'),(58,6,'Cellular One'),(59,6,'Cellular South'),(60,6,'Cricket'),(61,6,'Fido'),(62,6,'Helio'),(63,6,'MetroPCS'),(64,6,'Net10'),(65,6,'nTelos'),(66,6,'Qwest'),(67,6,'Rogers Wireless'),(68,6,'Sprint'),(69,6,'Suncom'),(70,6,'Telus'),(71,6,'T-Mobile'),(72,6,'TracFone'),(73,6,'U.S. Cellular'),(74,6,'Verizon'),(75,6,'Virgin Mobile'),(76,6,'Vodafone'),(77,7,'Without Contract'),(78,7,'With Contract'),(79,7,'Prepaid'),(80,8,'Android'),(81,8,'BlackBerry 3-7'),(82,8,'BlackBerry 10'),(83,8,'Danger OS'),(84,8,'Firefox OS'),(85,8,'HP/Palm WebOS'),(86,8,'iOS - Apple'),(87,8,'Maemo'),(88,8,'Symbian'),(89,8,'Windows Mobile'),(90,8,'Windows Phone 7'),(91,8,'Windows Phone 7.5'),(92,8,'Windows Phone 8'),(93,9,'64GB'),(94,9,'32GB'),(95,9,'16GB'),(96,9,'8GB'),(97,9,'4GB'),(98,9,'2GB'),(99,9,'1GB'),(100,9,'512MB'),(101,9,'256MB'),(102,9,'150MB'),(103,9,'128MB'),(104,9,'100MB'),(105,9,'96MB'),(106,9,'80MB'),(107,9,'64MB'),(108,9,'60MB'),(109,9,'50MB'),(110,9,'40MB'),(111,9,'32MB'),(112,9,'30MB'),(113,9,'20MB'),(114,9,'16MB'),(115,9,'10MB'),(116,9,'8MB'),(117,9,'5MB'),(118,2,'Beige'),(119,2,'Black'),(120,2,'Blue'),(121,2,'Brown'),(122,2,'Clear'),(123,2,'Gold'),(124,2,'Green'),(125,2,'Grey'),(126,2,'Orange'),(127,2,'Pink'),(128,2,'Purple'),(129,2,'Red'),(130,2,'Silver'),(131,2,'White'),(132,2,'Yellow'),(133,2,'Multi-Color'),(134,10,'Bar'),(135,10,'Flip'),(136,10,'Slider'),(137,10,'Swivel'),(138,11,'3G Data Capable'),(139,11,'Near Field Communication'),(140,11,'Music Player'),(141,11,'4G Data Capable'),(142,11,'Bluetooth Enabled'),(143,11,'GPS'),(144,11,'QWERTY Keyboard'),(145,11,'Fingerprint Sensor'),(146,11,'Global Ready'),(147,11,'Internet Browser'),(148,12,'0.1 MP'),(149,12,'0.3 MP'),(150,12,'0.5 MP'),(151,12,'1.0 MP'),(152,12,'1.2 MP'),(153,12,'1.3 MP'),(154,12,'2.0 MP'),(155,12,'3.0 MP'),(156,12,'3.1 MP'),(157,12,'3.2 MP'),(158,12,'4.0 MP'),(159,12,'5.0 MP'),(160,12,'5.1 MP'),(161,12,'8.0 MP'),(162,12,'8.1 MP'),(163,12,'10.0 MP'),(164,12,'None'),(165,13,'Armband'),(166,13,'Case'),(167,13,'Extra Cable(s)'),(168,13,'Bluetooth/Hands-Free Headset'),(169,13,'Dock or Cradle'),(170,13,'Extra Power Charger (AC)'),(171,13,'Car Charger (12V)'),(172,13,'Car Mount'),(173,13,'Extra Battery'),(174,13,'Faceplate or Decals'),(175,15,'philippines'),(177,15,'united states of america (USA)');
/*!40000 ALTER TABLE `es_attr_lookuplist_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `es_brand`
--

DROP TABLE IF EXISTS `es_brand`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `es_brand` (
  `id_brand` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(1023) NOT NULL DEFAULT '',
  `image` varchar(512) NOT NULL DEFAULT '',
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `url` varchar(512) NOT NULL DEFAULT '',
  `is_main` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_brand`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `es_brand`
--

LOCK TABLES `es_brand` WRITE;
/*!40000 ALTER TABLE `es_brand` DISABLE KEYS */;
INSERT INTO `es_brand` VALUES (1,'','','',0,'',0),(2,'Serafico Clothing','Clothes','',0,'',0),(3,'NOKIA','','',0,'',0),(4,'SAMSUNG','','',0,'',0),(5,'APPLE','','',0,'',0),(6,'SONY','','',0,'',0),(7,'BLACKBERRY','','',0,'',0),(8,'CHERRYMOBILE','','',0,'',0);
/*!40000 ALTER TABLE `es_brand` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `es_cat`
--

DROP TABLE IF EXISTS `es_cat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=92 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `es_cat`
--

LOCK TABLES `es_cat` WRITE;
/*!40000 ALTER TABLE `es_cat` DISABLE KEYS */;
INSERT INTO `es_cat` VALUES (1,'PARENT','PARENT','PARENT',1,0,0,'','',''),(2,'Cell Phones & Accessories','','',1,0,1,'','',''),(3,'Cell Phones & Smartphones','','',2,0,0,'','',''),(4,'Smart Watches','','',2,0,0,'','',''),(5,'Cell Phone Accessories','','',2,0,0,'','',''),(6,'Display Phones','','',2,0,0,'','',''),(7,'Phone Cards & SIM Cards','','',2,0,0,'','',''),(8,'Replacement Parts & Tools','','',2,0,0,'','',''),(9,'Accessory Bundles','','',5,0,0,'','',''),(10,'Armbands','','',5,0,0,'','',''),(11,'Audio Docks & Speakers','','',5,0,0,'','',''),(12,'Batteries','','',5,0,0,'','',''),(13,'Cables & Adapters','','',5,0,0,'','',''),(14,'Car Speakerphones','','',5,0,0,'','',''),(15,'Cases, Covers & Skins','','',5,0,0,'','',''),(16,'Chargers & Cradles','','',5,0,0,'','',''),(17,'FM Transmitters','','',5,0,0,'','',''),(18,'Headsets','','',5,0,0,'','',''),(19,'Manuals & Guides','','',5,0,0,'','',''),(20,'Memory Cards','','',5,0,0,'','',''),(21,'Memory Card Readers & Adapters','','',5,0,0,'','',''),(22,'Mounts & Holders','','',5,0,0,'','',''),(23,'Screen Protectors','','',5,0,0,'','',''),(24,'Signal Boosters','','',5,0,0,'','',''),(25,'Straps & Charms','','',5,0,0,'','',''),(26,'Styluses','','',5,0,0,'','',''),(27,'Refills & Top Ups','','',7,0,0,'','',''),(28,'SIM Cards','','',7,0,0,'','',''),(29,'SIM Card Readers','','',7,0,0,'','',''),(30,'Clothing','','',1,0,1,'','',''),(31,'Bag and Shoes','','',1,0,1,'','',''),(32,'Digital','','',1,0,1,'','',''),(33,'Home and Garden','','',1,0,1,'','',''),(34,'Baby','','',1,0,1,'','',''),(35,'Sporting Goods','','',1,0,1,'','',''),(36,'CWomen\'s Apparel','','',30,0,0,'','',''),(37,'Pants','','',36,0,0,'','',''),(38,'Jacket','','',36,0,0,'','',''),(39,'Chiffon','','',36,0,0,'','',''),(40,'Knit wear','','',36,0,0,'','',''),(41,'shirt','','',36,0,0,'','',''),(42,'Harness','','',36,0,0,'','',''),(43,'Skirts','','',36,0,0,'','',''),(44,'Blazer','','',36,0,0,'','',''),(45,'CMen\'s Apparel','','',30,0,0,'','',''),(46,'T-shirts','','',45,0,0,'','',''),(47,'Shorts','','',45,0,0,'','',''),(48,'Shirts','','',45,0,0,'','',''),(49,'Sweaters','','',45,0,0,'','',''),(50,'Short-sleeved','','',45,0,0,'','',''),(51,'Jackets','','',45,0,0,'','',''),(52,'Singlets','','',45,0,0,'','',''),(53,'Coats','','',45,0,0,'','',''),(54,'CUnderwear','','',30,0,0,'','',''),(55,'Pants','','',54,0,0,'','',''),(56,'Jacket','','',54,0,0,'','',''),(57,'Chiffon','','',54,0,0,'','',''),(58,'Knit wear','','',54,0,0,'','',''),(59,'shirt','','',54,0,0,'','',''),(60,'Harness','','',54,0,0,'','',''),(61,'Skirts','','',54,0,0,'','',''),(62,'Blazer','','',54,0,0,'','',''),(63,'CAccessories','','',30,0,0,'','',''),(64,'T-shirts','','',63,0,0,'','',''),(65,'Shorts','','',63,0,0,'','',''),(66,'Shirts','','',63,0,0,'','',''),(67,'Sweaters','','',63,0,0,'','',''),(68,'Short-sleeved','','',63,0,0,'','',''),(69,'Jackets','','',63,0,0,'','',''),(70,'Singlets','','',63,0,0,'','',''),(71,'Coats','','',63,0,0,'','',''),(72,'bWomen\'s Apparel','','',31,0,0,'','',''),(73,'Pants','','',72,0,0,'','',''),(74,'Jacket','','',72,0,0,'','',''),(75,'Chiffon','','',72,0,0,'','',''),(76,'Knit wear','','',72,0,0,'','',''),(77,'shirt','','',72,0,0,'','',''),(78,'Harness','','',72,0,0,'','',''),(79,'Skirts','','',72,0,0,'','',''),(80,'Blazer','','',72,0,0,'','',''),(81,'bMen\'s Apparel','','',31,0,0,'','',''),(82,'T-shirts','','',81,0,0,'','',''),(83,'Shorts','','',81,0,0,'','',''),(84,'Shirts','','',81,0,0,'','',''),(85,'Sweaters','','',81,0,0,'','',''),(86,'Short-sleeved','','',81,0,0,'','',''),(87,'Jackets','','',81,0,0,'','',''),(88,'Singlets','','',81,0,0,'','',''),(89,'Coats','','',81,0,0,'','',''),(90,'bsWomen\'s Apparel','','',32,0,0,'','',''),(91,'bsMen\'s Apparel','','',32,0,0,'','','');
/*!40000 ALTER TABLE `es_cat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `es_cat_img`
--

DROP TABLE IF EXISTS `es_cat_img`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `es_cat_img` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_cat` int(11) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `es_cat_img`
--

LOCK TABLES `es_cat_img` WRITE;
/*!40000 ALTER TABLE `es_cat_img` DISABLE KEYS */;
INSERT INTO `es_cat_img` VALUES (1,2,'images/img_icon_clothing.png'),(2,3,'images/img_icon_bag.png'),(3,4,'images/img_icon_digital.png'),(4,5,'images/img_icon_home.png'),(5,6,'images/img_icon_baby.png'),(6,7,'images/img_icon_sportinggoods.png'),(7,8,NULL),(8,9,NULL),(9,10,NULL),(10,11,NULL),(11,12,NULL),(12,13,NULL),(13,14,NULL),(14,15,NULL),(15,16,NULL),(16,17,NULL),(17,18,NULL),(18,19,''),(19,20,NULL),(20,21,NULL),(21,22,NULL),(22,23,NULL),(23,24,NULL),(24,25,NULL),(25,26,NULL),(26,27,NULL),(27,28,NULL),(28,29,NULL),(29,30,NULL),(30,31,NULL),(31,32,NULL),(32,33,NULL),(33,34,NULL),(34,35,NULL),(35,36,NULL),(36,37,NULL),(37,38,NULL),(38,39,NULL),(39,40,NULL),(40,41,NULL),(41,42,NULL),(42,43,NULL),(43,44,NULL),(44,45,NULL),(45,46,NULL),(46,47,NULL),(47,48,NULL),(48,49,NULL),(49,50,NULL),(50,51,NULL),(51,52,NULL),(52,53,NULL),(53,54,NULL),(54,55,NULL),(55,56,NULL),(56,57,NULL),(57,58,NULL),(58,59,NULL),(59,60,NULL),(60,61,NULL),(61,62,NULL),(62,63,NULL);
/*!40000 ALTER TABLE `es_cat_img` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `es_datatype`
--

DROP TABLE IF EXISTS `es_datatype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `es_datatype` (
  `id_datatype` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id_datatype`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `es_datatype`
--

LOCK TABLES `es_datatype` WRITE;
/*!40000 ALTER TABLE `es_datatype` DISABLE KEYS */;
INSERT INTO `es_datatype` VALUES (1,'TEXT'),(2,'TEXTAREA'),(3,'RADIO'),(4,'SELECT'),(5,'CHECKBOX');
/*!40000 ALTER TABLE `es_datatype` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `es_member`
--

DROP TABLE IF EXISTS `es_member`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `es_member`
--

LOCK TABLES `es_member` WRITE;
/*!40000 ALTER TABLE `es_member` DISABLE KEYS */;
INSERT INTO `es_member` VALUES (1,'Admin','62c47a865919ca7a58c8a2e831e7c0e1f4f9e796','191CC6AD11F4DF69396374AA9F8693991784D1D8*','09152801591',1,'samuel_gavinio55@yahoo.com',0,'F','2013-12-08',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','2013-12-17 17:08:09','::1',1,0,0,'sam1','sam22','assets/user/1_admin','NCR'),(2,'sam','9e1e13da569b89d8335e4ea11cf0b3eb751f3bd7','AB0FF14279365334BF9BCBD96104400452F48797*','09152801591',1,'samuel_gavinio55@yahoo.com',0,'M','0000-00-00',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','2014-01-03 13:33:51','::1',6,0,0,'','','','');
/*!40000 ALTER TABLE `es_member` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `es_product`
--

DROP TABLE IF EXISTS `es_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
  `quantity` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_product`),
  KEY `fk_es_product_es_cat1_idx` (`cat_id`),
  KEY `fk_es_product_es_brand1_idx` (`brand_id`),
  KEY `fk_es_product_es_style1_idx` (`style_id`),
  KEY `fk_es_product_es_member1_idx` (`member_id`),
  CONSTRAINT `fk_es_product_es_brand1` FOREIGN KEY (`brand_id`) REFERENCES `es_brand` (`id_brand`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_es_product_es_cat1` FOREIGN KEY (`cat_id`) REFERENCES `es_cat` (`id_cat`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_es_product_es_member1` FOREIGN KEY (`member_id`) REFERENCES `es_member` (`id_member`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_es_product_es_style1` FOREIGN KEY (`style_id`) REFERENCES `es_style` (`id_style`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `es_product`
--

LOCK TABLES `es_product` WRITE;
/*!40000 ALTER TABLE `es_product` DISABLE KEYS */;
INSERT INTO `es_product` VALUES (3,'Galaxy','987654321','This is my First item','This is my decription for my First item<br>','New other (see details)','KEYWORD',9000,1,3,1,0,0,0,0,0,1,'','2013-12-19 11:25:19','2013-12-19 11:25:19',0,0),(4,'Galaxy Car','01029384856','This is my Second item','This is my decription for my Second item<br>','New other (see details)','KEYWORD',9000,1,3,1,0,0,0,0,0,1,'','2013-12-19 11:27:40','2013-12-19 11:27:40',0,0),(5,'iPhone Selling with TV and E-Fan','01029384856','This is my Thirid item','This is my decription for my Thirid item<br>','New','KEYWORD',100000,1,3,1,0,0,0,0,0,1,'','2013-12-19 11:30:27','2013-12-19 11:30:27',0,0),(6,'Lace with zoo','008808080808','ikaw na bahala','judge it :D <br>','Manufacturer refurbished','KEYWORD',500,1,9,1,0,0,0,0,0,1,'','2013-12-19 11:49:40','2013-12-19 11:49:40',0,0),(7,'Title','createSmallSize','Description','createSmallSize<br>','Manufacturer refurbished','KEYWORD',200,1,9,1,0,0,0,0,0,1,'','2013-12-19 15:18:45','2013-12-19 15:18:45',0,0),(8,'PRINTED THICK WINTER PANTS','123445667789876543','Some more description here. ','This is the description describing the product.','New','KEYWORD',2199.99,2,37,1,0,0,0,0,0,1,'','2013-12-17 09:50:57','2013-12-19 15:18:45',0,31),(9,'asasasas','asasasa','asasass','asasas<br>','New','KEYWORD',1233,2,3,1,0,0,0,0,0,1,'','2014-01-02 10:28:25','2014-01-02 10:28:25',0,0),(10,'asdasdas','21212','dasdas','dasdas<br>','New','KEYWORD',3232,1,61,1,0,0,0,0,0,1,'','2014-01-02 10:30:04','2014-01-02 10:30:04',0,0),(11,'dsadsad','sadasd','asdas','dasd<br>','New','KEYWORD',2122,1,58,1,0,0,0,0,0,1,'','2014-01-02 10:31:03','2014-01-02 10:31:03',0,0),(12,'2121','2121','2121','212<br>','New','KEYWORD',2121,1,47,1,0,0,0,0,0,1,'','2014-01-02 10:31:46','2014-01-02 10:31:46',0,0),(13,'21212','2121','1212','2121<br>','New','KEYWORD',21212,1,57,1,0,0,0,0,0,1,'','2014-01-02 10:34:42','2014-01-02 10:34:42',0,0),(14,'asdasd','asdasdsa','asdasd','asdasd<br>','New','KEYWORD',2112,1,48,1,0,0,0,0,0,1,'','2014-01-02 10:35:44','2014-01-02 10:35:44',0,0),(15,'2121','2121','2121','2121<br>','New','KEYWORD',2121,1,60,1,0,0,0,0,0,1,'','2014-01-02 10:39:59','2014-01-02 10:39:59',0,0),(16,'dsdsd','sdfsd','fsdf','sdfsd<br>','New','KEYWORD',2121,1,3,1,0,0,0,0,0,1,'','2014-01-02 10:51:42','2014-01-02 10:51:42',0,0);
/*!40000 ALTER TABLE `es_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `es_product_attr`
--

DROP TABLE IF EXISTS `es_product_attr`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=134 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `es_product_attr`
--

LOCK TABLES `es_product_attr` WRITE;
/*!40000 ALTER TABLE `es_product_attr` DISABLE KEYS */;
INSERT INTO `es_product_attr` VALUES (29,3,14,'10.0 mp',0),(30,3,7,'Unlocked',0),(31,3,2,'Beige',0),(32,3,2,'Black',0),(33,3,2,'Purple',0),(34,3,8,'Prepaid',0),(35,3,17,'Philippines',0),(36,3,13,'Gps',0),(37,3,4,'Samsung galaxy note ii',0),(38,3,6,'123456789',0),(39,3,9,'Android',0),(40,3,11,'64gb',0),(41,3,12,'Bar',0),(42,3,1,'Casing',100),(43,4,3,'Philips',0),(44,4,14,'0.1 mp',0),(45,4,7,'Cellular one',0),(46,4,2,'Beige',0),(47,4,2,'Black',0),(48,4,2,'Blue',0),(49,4,2,'Brown',0),(50,4,2,'Clear',0),(51,4,2,'Gold',0),(52,4,2,'Green',0),(53,4,2,'Grey',0),(54,4,2,'Orange',0),(55,4,2,'Pink',0),(56,4,2,'Purple',0),(57,4,2,'Red',0),(58,4,2,'Silver',0),(59,4,2,'White',0),(60,4,2,'Yellow',0),(61,4,2,'Multi-color',0),(62,4,8,'Prepaid',0),(63,4,17,'Philippines',0),(64,4,13,'3g data capable',0),(65,4,13,'Near field communication',0),(66,4,13,'Music player',0),(67,4,13,'4g data capable',0),(68,4,13,'Bluetooth enabled',0),(69,4,13,'Gps',0),(70,4,13,'Qwerty keyboard',0),(71,4,13,'Fingerprint sensor',0),(72,4,13,'Global ready',0),(73,4,13,'Internet browser',0),(74,4,4,'Lg optimus elite',0),(75,4,6,'9998888654',0),(76,4,9,'Android',0),(77,4,11,'1gb',0),(78,4,12,'Slider',0),(79,4,1,'Casing',200),(80,4,1,'Lace',159),(81,5,3,'Apple',0),(82,5,15,'Extra battery',0),(83,5,14,'10.0 mp',0),(84,5,7,'Virgin mobile',0),(85,5,2,'Black',0),(86,5,8,'Without contract',0),(87,5,17,'Philippines',0),(88,5,13,'3g data capable',0),(89,5,13,'Near field communication',0),(90,5,13,'Music player',0),(91,5,13,'4g data capable',0),(92,5,13,'Bluetooth enabled',0),(93,5,13,'Gps',0),(94,5,13,'Qwerty keyboard',0),(95,5,13,'Fingerprint sensor',0),(96,5,13,'Global ready',0),(97,5,13,'Internet browser',0),(98,5,4,'Iphone 5s',0),(99,5,6,'00000000000000',0),(100,5,9,'Ios - apple',0),(101,5,11,'64gb',0),(102,5,12,'Bar',0),(103,5,1,'Casing',200),(104,5,1,'Lace',159),(105,5,1,'motolite Charger',2000),(106,6,2,'Red',0),(107,6,17,'Philippines',0),(108,6,6,'090909',0),(109,7,2,'Red',0),(110,7,17,'Philippines',0),(111,7,6,'12345678',0),(112,8,18,'Red',0),(113,8,18,'White',0),(114,8,18,'Gold',1000),(115,8,19,'XS',0),(116,8,19,'S',0),(117,8,19,'L',500),(118,16,3,'Lg',0),(119,16,15,'Armband',0),(120,16,15,'Faceplate or decals',0),(121,16,14,'0.3 mp',0),(122,16,7,'Rogers wireless',0),(123,16,16,'dasdas',0),(124,16,2,'Beige',0),(125,16,8,'Without contract',0),(126,16,17,'Philippines',0),(127,16,13,'3g data capable',0),(128,16,13,'Bluetooth enabled',0),(129,16,4,'Htc one s',0),(130,16,6,'fsdfsd',0),(131,16,9,'Blackberry 3-7',0),(132,16,11,'16gb',0),(133,16,12,'Flip',0);
/*!40000 ALTER TABLE `es_product_attr` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `es_product_image`
--

DROP TABLE IF EXISTS `es_product_image`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `es_product_image` (
  `id_product_image` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_image_path` text NOT NULL,
  `product_image_type` varchar(1024) DEFAULT '',
  `product_id` int(10) unsigned NOT NULL,
  `is_primary` int(1) DEFAULT '0',
  PRIMARY KEY (`id_product_image`),
  KEY `fk_es_product_es_product1` (`product_id`),
  CONSTRAINT `fk_es_product_es_product1` FOREIGN KEY (`product_id`) REFERENCES `es_product` (`id_product`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `es_product_image`
--

LOCK TABLES `es_product_image` WRITE;
/*!40000 ALTER TABLE `es_product_image` DISABLE KEYS */;
INSERT INTO `es_product_image` VALUES (2,'./assets/product/3_1_20131219/3_1_201312190.png','image/png',3,1),(3,'./assets/product/4_1_20131219/4_1_201312190.jpg','image/jpeg',4,1),(4,'./assets/product/5_1_20131219/5_1_201312190.jpg','image/jpeg',5,1),(5,'./assets/product/6_1_20131219/6_1_201312190.jpg','image/jpeg',6,1),(6,'./assets/product/7_1_20131219/7_1_201312190.jpg','image/jpeg',7,1),(7,'./assets/product/8_1_20131218/8_1_201312180.jpg','image/jpeg',8,1),(8,'./assets/product/8_1_20131218/8_1_201312181.jpg','image/jpeg',8,0),(9,'./assets/product/8_1_20131218/8_1_201312182.jpg','image/jpeg',8,0),(10,'./assets/product/12_1_20140102/12_1_201401020.jpg','image/jpeg',12,0),(11,'./assets/product/13_1_20140102/13_1_201401020.jpg','image/jpeg',13,0),(12,'./assets/product/14_1_20140102/14_1_201401020.jpg','image/jpeg',14,0),(13,'./assets/product/15_1_20140102/15_1_201401020.jpg','image/jpeg',15,0),(14,'./assets/product/16_1_20140102/16_1_201401020.jpg','image/jpeg',16,0),(15,'./assets/product/16_1_20140102/16_1_201401021.jpg','image/jpeg',16,0),(16,'./assets/product/16_1_20140102/16_1_201401022.jpg','image/jpeg',16,1),(17,'./assets/product/16_1_20140102/16_1_201401023.jpg','image/jpeg',16,0),(18,'./assets/product/16_1_20140102/16_1_201401024.jpg','image/jpeg',16,0),(19,'./assets/product/16_1_20140102/16_1_201401025.jpg','image/jpeg',16,0),(20,'./assets/product/16_1_20140102/16_1_201401026.jpg','image/jpeg',16,0),(21,'./assets/product/16_1_20140102/16_1_201401027.jpg','image/jpeg',16,0);
/*!40000 ALTER TABLE `es_product_image` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `es_product_review`
--

DROP TABLE IF EXISTS `es_product_review`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `es_product_review` (
  `id_review` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NOT NULL,
  `datesubmitted` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `rating` int(11) NOT NULL DEFAULT '0',
  `title` varchar(45) NOT NULL DEFAULT '',
  `review` varchar(255) NOT NULL DEFAULT '',
  `is_approved` tinyint(1) NOT NULL DEFAULT '0',
  `product_id` int(11) NOT NULL,
  PRIMARY KEY (`id_review`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `es_product_review`
--

LOCK TABLES `es_product_review` WRITE;
/*!40000 ALTER TABLE `es_product_review` DISABLE KEYS */;
/*!40000 ALTER TABLE `es_product_review` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `es_school`
--

DROP TABLE IF EXISTS `es_school`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `es_school`
--

LOCK TABLES `es_school` WRITE;
/*!40000 ALTER TABLE `es_school` DISABLE KEYS */;
INSERT INTO `es_school` VALUES (1,1,'1222',2001,'1',1);
/*!40000 ALTER TABLE `es_school` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `es_style`
--

DROP TABLE IF EXISTS `es_style`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `es_style` (
  `id_style` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `value` varchar(512) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_style`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `es_style`
--

LOCK TABLES `es_style` WRITE;
/*!40000 ALTER TABLE `es_style` DISABLE KEYS */;
INSERT INTO `es_style` VALUES (1,'Style','Style');
/*!40000 ALTER TABLE `es_style` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `es_work`
--

DROP TABLE IF EXISTS `es_work`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `es_work`
--

LOCK TABLES `es_work` WRITE;
/*!40000 ALTER TABLE `es_work` DISABLE KEYS */;
INSERT INTO `es_work` VALUES (86,1,'iomkomko','87787',2155,1),(88,1,'8u8u8','h8u8',2155,2);
/*!40000 ALTER TABLE `es_work` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'easyshop'
--
/*!50003 DROP FUNCTION IF EXISTS `getAllParent` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`127.0.0.1`*/ /*!50003 FUNCTION `getAllParent`(GivenID INT) RETURNS varchar(1024) CHARSET utf8
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
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `SimpleCompare` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 FUNCTION `SimpleCompare`(n INT, m INT) RETURNS varchar(20) CHARSET utf8
BEGIN
    DECLARE s VARCHAR(20);
    IF n > m THEN SET s = '>';
    ELSEIF n = m THEN SET s = '=';
    ELSE SET s = '<';
    END IF;
    SET s = CONCAT(n, ' ', s, ' ', m);
    RETURN s;
  END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `es_sp_Login_user` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `es_sp_Login_user`(
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
	
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `es_sp_Logout_user` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `es_sp_Logout_user`(
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
	
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-01-03 15:35:28
