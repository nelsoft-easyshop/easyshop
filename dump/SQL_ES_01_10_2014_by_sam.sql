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
-- Table structure for table `ci_sessions`
--

DROP TABLE IF EXISTS `ci_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ci_sessions`
--

LOCK TABLES `ci_sessions` WRITE;
/*!40000 ALTER TABLE `ci_sessions` DISABLE KEYS */;
INSERT INTO `ci_sessions` VALUES ('08bd11bf8a8d22193521951494ce101f','192.168.3.116','Mozilla/5.0 (Windows NT 6.1; WOW64; rv:26.0) Gecko/20100101 Firefox/26.0',1389343170,'a:4:{s:9:\"user_data\";s:0:\"\";s:9:\"member_id\";s:1:\"1\";s:11:\"usersession\";s:40:\"b292678b17c8b228b032a57596f2303f04390635\";s:10:\"product_id\";s:1:\"4\";}'),('0af949c0cccd103ddf62d8a472bc6b73','192.168.3.116','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.63 Safari/537.36',1389340581,'a:3:{s:9:\"user_data\";s:0:\"\";s:12:\"captcha_word\";s:6:\"MFUSI5\";s:9:\"member_id\";s:2:\"47\";}'),('86dbeec28ad4feb72560bdc55ce70504','192.168.3.116','Mozilla/5.0 (Windows NT 6.1; WOW64; rv:26.0) Gecko/20100101 Firefox/26.0',1389344157,'a:4:{s:9:\"user_data\";s:0:\"\";s:9:\"member_id\";s:1:\"2\";s:11:\"usersession\";s:40:\"8ed7ab7970e4b3e60a1cf35a0453c4697b163051\";s:10:\"product_id\";s:1:\"8\";}'),('a853e2ffa6979b27902fc11c71ea92b2','192.168.3.116','Mozilla/5.0 (Windows NT 6.1; WOW64; rv:26.0) Gecko/20100101 Firefox/26.0',1389343799,''),('dee613e92e8c68d2c8f17feb89248ddb','::1','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.63 Safari/537.36',1389340616,'a:3:{s:9:\"user_data\";s:0:\"\";s:12:\"captcha_word\";s:6:\"DLMRSX\";s:9:\"member_id\";s:2:\"53\";}'),('ffcc0110223994759aa1799951ee3841','192.168.3.116','Mozilla/5.0 (Windows NT 6.1; WOW64; rv:26.0) Gecko/20100101 Firefox/26.0',1389345374,'a:4:{s:9:\"user_data\";s:0:\"\";s:9:\"member_id\";s:1:\"1\";s:11:\"usersession\";s:40:\"051290d251550c5a71da5661f99478fdf6212c42\";s:10:\"product_id\";s:2:\"30\";}');
/*!40000 ALTER TABLE `ci_sessions` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `es_address`
--

LOCK TABLES `es_address` WRITE;
/*!40000 ALTER TABLE `es_address` DISABLE KEYS */;
INSERT INTO `es_address` VALUES (27,'1','32312','3123123','123123','312','12312','123','0','','',''),(50,'1','232311111112323423','hello world12','bar','baz','sadjsa','3231','1','878','2378','sam gavinio'),(51,'2','21212','121','21','dg33','1212','212','0','','',''),(54,'2','21212','121','21','dg33','1212','212','1','2121','12121212','sasasas');
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
INSERT INTO `es_cat` VALUES (1,'PARENT','PARENT','PARENT',1,0,0,'','',''),(2,'Cell Phones & Accessories','','',1,0,1,'','',''),(3,'Cell Phones & Smartphones','','',2,0,1,'','',''),(4,'Smart Watches','','',2,0,1,'','',''),(5,'Cell Phone Accessories','','',2,0,1,'','',''),(6,'Display Phones','','',2,0,1,'','',''),(7,'Phone Cards & SIM Cards','','',2,0,1,'','',''),(8,'Replacement Parts & Tools','','',2,0,1,'','',''),(9,'Accessory Bundles','','',5,0,1,'','',''),(10,'Armbands','','',5,0,1,'','',''),(11,'Audio Docks & Speakers','','',5,0,1,'','',''),(12,'Batteries','','',5,0,1,'','',''),(13,'Cables & Adapters','','',5,0,1,'','',''),(14,'Car Speakerphones','','',5,0,1,'','',''),(15,'Cases, Covers & Skins','','',5,0,1,'','',''),(16,'Chargers & Cradles','','',5,0,1,'','',''),(17,'FM Transmitters','','',5,0,1,'','',''),(18,'Headsets','','',5,0,1,'','',''),(19,'Manuals & Guides','','',5,0,1,'','',''),(20,'Memory Cards','','',5,0,1,'','',''),(21,'Memory Card Readers & Adapters','','',5,0,1,'','',''),(22,'Mounts & Holders','','',5,0,1,'','',''),(23,'Screen Protectors','','',5,0,1,'','',''),(24,'Signal Boosters','','',5,0,1,'','',''),(25,'Straps & Charms','','',5,0,1,'','',''),(26,'Styluses','','',5,0,1,'','',''),(27,'Refills & Top Ups','','',7,0,1,'','',''),(28,'SIM Cards','','',7,0,1,'','',''),(29,'SIM Card Readers','','',7,0,1,'','',''),(30,'Clothing','','',1,0,1,'','',''),(31,'Bag and Shoes','','',1,0,1,'','',''),(32,'Digital','','',1,0,1,'','',''),(33,'Home and Garden','','',1,0,1,'','',''),(34,'Baby','','',1,0,1,'','',''),(35,'Sporting Goods','','',1,0,1,'','',''),(36,'CWomen\'s Apparel','','',30,0,1,'','',''),(37,'Pants','','',36,0,1,'','',''),(38,'Jacket','','',36,0,1,'','',''),(39,'Chiffon','','',36,0,1,'','',''),(40,'Knit wear','','',36,0,1,'','',''),(41,'shirt','','',36,0,1,'','',''),(42,'Harness','','',36,0,1,'','',''),(43,'Skirts','','',36,0,1,'','',''),(44,'Blazer','','',36,0,1,'','',''),(45,'CMen\'s Apparel','','',30,0,1,'','',''),(46,'T-shirts','','',45,0,1,'','',''),(47,'Shorts','','',45,0,1,'','',''),(48,'Shirts','','',45,0,1,'','',''),(49,'Sweaters','','',45,0,1,'','',''),(50,'Short-sleeved','','',45,0,1,'','',''),(51,'Jackets','','',45,0,1,'','',''),(52,'Singlets','','',45,0,1,'','',''),(53,'Coats','','',45,0,1,'','',''),(54,'CUnderwear','','',30,0,1,'','',''),(55,'Pants','','',54,0,1,'','',''),(56,'Jacket','','',54,0,1,'','',''),(57,'Chiffon','','',54,0,1,'','',''),(58,'Knit wear','','',54,0,1,'','',''),(59,'shirt','','',54,0,1,'','',''),(60,'Harness','','',54,0,1,'','',''),(61,'Skirts','','',54,0,1,'','',''),(62,'Blazer','','',54,0,1,'','',''),(63,'CAccessories','','',30,0,1,'','',''),(64,'T-shirts','','',63,0,1,'','',''),(65,'Shorts','','',63,0,1,'','',''),(66,'Shirts','','',63,0,1,'','',''),(67,'Sweaters','','',63,0,1,'','',''),(68,'Short-sleeved','','',63,0,1,'','',''),(69,'Jackets','','',63,0,1,'','',''),(70,'Singlets','','',63,0,1,'','',''),(71,'Coats','','',63,0,1,'','',''),(72,'bWomen\'s Apparel','','',31,0,1,'','',''),(73,'Pants','','',72,0,1,'','',''),(74,'Jacket','','',72,0,1,'','',''),(75,'Chiffon','','',72,0,1,'','',''),(76,'Knit wear','','',72,0,1,'','',''),(77,'shirt','','',72,0,1,'','',''),(78,'Harness','','',72,0,1,'','',''),(79,'Skirts','','',72,0,1,'','',''),(80,'Blazer','','',72,0,1,'','',''),(81,'bMen\'s Apparel','','',31,0,1,'','',''),(82,'T-shirts','','',81,0,1,'','',''),(83,'Shorts','','',81,0,1,'','',''),(84,'Shirts','','',81,0,1,'','',''),(85,'Sweaters','','',81,0,1,'','',''),(86,'Short-sleeved','','',81,0,1,'','',''),(87,'Jackets','','',81,0,1,'','',''),(88,'Singlets','','',81,0,1,'','',''),(89,'Coats','','',81,0,1,'','',''),(90,'bsWomen\'s Apparel','','',32,0,1,'','',''),(91,'bsMen\'s Apparel','','',32,0,1,'','','');
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
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `es_member`
--

LOCK TABLES `es_member` WRITE;
/*!40000 ALTER TABLE `es_member` DISABLE KEYS */;
INSERT INTO `es_member` VALUES (1,'Admin','051290d251550c5a71da5661f99478fdf6212c42','191CC6AD11F4DF69396374AA9F8693991784D1D8*','09152801591',1,'samuel_gavinio55@yahoo.com',0,'M','2014-01-22',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','2014-01-10 17:17:03','192.168.3.116',8,0,0,'sdsd','2323','assets/user/1_Admin','NCR'),(2,'sam','8ed7ab7970e4b3e60a1cf35a0453c4697b163051','AB0FF14279365334BF9BCBD96104400452F48797*','09152801591',1,'samuel_gavinio55@yahoo.com',0,'F','2014-01-16',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','2014-01-10 16:50:28','192.168.3.116',91,0,0,'','dsds','assets/user/2_sam',''),(7,'ashdashdjas',NULL,'54C2F1BDD5257C55A510CD064BF01F466F7CA9D5*','',0,'samgavinio@yahoo.com',0,'0','0000-00-00',0,'2014-01-08 18:22:58','0000-00-00 00:00:00','0000-00-00 00:00:00','',0,0,0,'','','','NCR'),(8,'dasdasdas',NULL,'B1B0227926D32C392B616894B0EA3EEA34F5C848*','',0,'samgavinio@yahoo.com',0,'0','0000-00-00',0,'2014-01-08 18:27:16','0000-00-00 00:00:00','0000-00-00 00:00:00','',0,0,0,'','','','NCR'),(9,'ihdfkjdhfj',NULL,'13A81BFFFB46BC77DD458399BE4F105B8419A86A*','',0,'samgavinio@yahoo.com',0,'0','0000-00-00',0,'2014-01-08 18:30:55','0000-00-00 00:00:00','0000-00-00 00:00:00','',0,0,0,'','','','NCR'),(10,'sksjfkjshfjags',NULL,'CDB28A0BFBA8185877441318B23DD578F766CE39*','',0,'samgavinio@yahoo.com',0,'0','0000-00-00',0,'2014-01-08 18:31:50','0000-00-00 00:00:00','0000-00-00 00:00:00','',0,0,0,'','','','NCR'),(11,'skjdhfkahj',NULL,'B510BCEA7EFE0C7F2BD6FF24E7B85A1B49B791AC*','',0,'samgavinio@yahoo.com',1,'0','0000-00-00',0,'2014-01-08 18:32:44','0000-00-00 00:00:00','0000-00-00 00:00:00','',0,0,0,'','','','NCR'),(12,'samgavinio',NULL,'8A3B16139F4ED22C4119299CBF527AEC49885009*','',0,'samgavinio@yahoo.com',1,'0','0000-00-00',0,'2014-01-08 18:40:58','0000-00-00 00:00:00','0000-00-00 00:00:00','',0,0,0,'','','','NCR'),(13,'samueljzhfjah',NULL,'5B111C0682FB2896343E6B7A50269BE9F041468B*','',0,'samgavinio@yahoo.com',1,'0','0000-00-00',0,'2014-01-08 18:47:52','0000-00-00 00:00:00','0000-00-00 00:00:00','',0,0,0,'','','','NCR'),(14,'samskajdksajd98989',NULL,'E0E16144585392643DC09346E04AB548A8DD1C0A*','09152801591',1,'',0,'0','0000-00-00',0,'2014-01-08 18:51:06','0000-00-00 00:00:00','0000-00-00 00:00:00','',0,0,0,'','','','NCR'),(15,'samuelgavinio33','','68C85237D26D3AC16152B67A4F53E250E1C710BE*','09152801591',0,'samgavinio@yahoo.com',0,'0','0000-00-00',0,'2014-01-08 18:54:22','0000-00-00 00:00:00','2014-01-09 11:14:24','::1',1,0,0,'','','','NCR'),(16,'samsamsaslaksl',NULL,'D414ADB7FF255A665120AC8C44530EC165FD9BB6*','09152801591',0,'samgavinio@yahoo.com',0,'0','0000-00-00',0,'2014-01-08 19:03:49','0000-00-00 00:00:00','0000-00-00 00:00:00','',0,0,0,'','','','NCR'),(17,'root_root',NULL,'2A8BD42B6929B6F4F7E3A854C85EB10AEBDB25A2*','09152801591',0,'samgavinio@yahoo.com',0,'0','0000-00-00',0,'2014-01-08 19:07:25','0000-00-00 00:00:00','0000-00-00 00:00:00','',0,0,0,'','','','NCR'),(18,'samsamsamsam',NULL,'44DC5B9DE82E89864C43B4666C90AB7810C7F5AD*','',0,'samgavinio@yahoo.com',0,'0','0000-00-00',0,'2014-01-09 10:19:20','0000-00-00 00:00:00','0000-00-00 00:00:00','',0,0,0,'','','','NCR'),(19,'',NULL,'003C4BAD85CCF46636C667D5FEF4DCE9D59CAA67*','',1,'samgavinio@yahoo.com',0,'0','0000-00-00',0,'2014-01-09 10:19:36','0000-00-00 00:00:00','0000-00-00 00:00:00','',0,0,0,'','','','NCR'),(20,'',NULL,'003C4BAD85CCF46636C667D5FEF4DCE9D59CAA67*','',1,'samgavinio@yahoo.com',0,'0','0000-00-00',0,'2014-01-09 10:20:04','0000-00-00 00:00:00','0000-00-00 00:00:00','',0,0,0,'','','','NCR'),(21,'',NULL,'003C4BAD85CCF46636C667D5FEF4DCE9D59CAA67*','',1,'samgavinio@yahoo.com',0,'0','0000-00-00',0,'2014-01-09 10:20:25','0000-00-00 00:00:00','0000-00-00 00:00:00','',0,0,0,'','','','NCR'),(22,'samgavini0909077',NULL,'ABA023113B07BDCE7742E982EE4C1B81CECD0F35*','',0,'samgavinio@yahoo.com',0,'0','0000-00-00',0,'2014-01-09 10:21:48','0000-00-00 00:00:00','0000-00-00 00:00:00','',0,0,0,'','','','NCR'),(23,'',NULL,'003C4BAD85CCF46636C667D5FEF4DCE9D59CAA67*','09152801591',1,'',0,'0','0000-00-00',0,'2014-01-09 10:22:21','0000-00-00 00:00:00','0000-00-00 00:00:00','',0,0,0,'','','','NCR'),(24,'jdkfjkdfjkdjf',NULL,'C63541B85A7E9F38CA94AAEEC3A7B39B977614C1*','',0,'samgavinio@yahoo.com',0,'0','0000-00-00',0,'2014-01-09 10:25:43','0000-00-00 00:00:00','0000-00-00 00:00:00','',0,0,0,'','','','NCR'),(25,'samgavinioioioio',NULL,'48F5DE73A98A6238B16E8C522EE4DBD53EB3321F*','09152801591',0,'samgavinio@yahoo.com',0,'0','0000-00-00',0,'2014-01-09 10:26:45','0000-00-00 00:00:00','0000-00-00 00:00:00','',0,0,0,'','','','NCR'),(26,'asdasdasd',NULL,'018FEF993BD87FA5BE95BC0D4104814E00C020FA*','',0,'samgavinio@yahoo.com',0,'0','0000-00-00',0,'2014-01-09 10:29:48','0000-00-00 00:00:00','0000-00-00 00:00:00','',0,0,0,'','','','NCR'),(27,'easyshop2014',NULL,'EFCCD8D8226FF5A290B40B33EDE88CA9AC7C99D8*','',0,'samgavinio@yahoo.com',0,'0','0000-00-00',0,'2014-01-09 10:37:40','0000-00-00 00:00:00','0000-00-00 00:00:00','',0,0,0,'','','','NCR'),(28,'',NULL,'003C4BAD85CCF46636C667D5FEF4DCE9D59CAA67*','',0,'samgavinio@yahoo.com',0,'0','0000-00-00',0,'2014-01-09 10:43:57','0000-00-00 00:00:00','0000-00-00 00:00:00','',0,0,0,'','','','NCR'),(29,'samsamsamsam1212',NULL,'55EA776AFB57D5A74E1A77C9D9EFA50465732346*','',0,'samgavinio@yahoo.com',0,'0','0000-00-00',0,'2014-01-09 10:44:43','0000-00-00 00:00:00','0000-00-00 00:00:00','',0,0,0,'','','','NCR'),(30,'samghghagdjash',NULL,'994A3F62735939CAEAF760179EA9F205D8620199*','',0,'samgavinio@yahoo.com',0,'0','0000-00-00',0,'2014-01-09 10:49:01','0000-00-00 00:00:00','0000-00-00 00:00:00','',0,0,0,'','','','NCR'),(31,'',NULL,'003C4BAD85CCF46636C667D5FEF4DCE9D59CAA67*','09152801591',0,'',0,'0','0000-00-00',0,'2014-01-09 10:49:35','0000-00-00 00:00:00','0000-00-00 00:00:00','',0,0,0,'','','','NCR'),(32,'dfjkdajfkaj',NULL,'029EA5E97750120103587B1560F44BF40FCAF2CD*','',0,'samgavinio@yahoo.com',0,'0','0000-00-00',0,'2014-01-09 10:52:19','0000-00-00 00:00:00','0000-00-00 00:00:00','',0,0,0,'','','','NCR'),(33,'samgavinjsdhfhj',NULL,'15A395F83895E9150B629F6501A4FB2AAF6DE29F*','',0,'samgavinio@yahoo.com',0,'0','0000-00-00',0,'2014-01-09 10:54:44','0000-00-00 00:00:00','0000-00-00 00:00:00','',0,0,0,'','','','NCR'),(34,'',NULL,'003C4BAD85CCF46636C667D5FEF4DCE9D59CAA67*','09152801591',0,'',0,'0','0000-00-00',0,'2014-01-09 10:55:09','0000-00-00 00:00:00','0000-00-00 00:00:00','',0,0,0,'','','','NCR'),(35,'asdjaskldjaksjdlak',NULL,'CC953E320F5D3EE7AA0732CF3011F9C10B651165*','09152801591',0,'samgavinio@yahoo.com',0,'0','0000-00-00',0,'2014-01-09 10:57:58','0000-00-00 00:00:00','0000-00-00 00:00:00','',0,0,0,'','','','NCR'),(36,'adsdasdasd',NULL,'A6C49E89FF3AEED40B36ECCF0B0709079883DBFD*','09152801591',0,'samgavinio@yahoo.com',0,'0','0000-00-00',0,'2014-01-09 11:01:26','0000-00-00 00:00:00','0000-00-00 00:00:00','',0,0,0,'','','','NCR'),(37,'asdajshdjash_j',NULL,'50D2916D607CE10876307861B285149C7D8537E1*','09152801591',0,'samgavinio@yahoo.com',0,'0','0000-00-00',0,'2014-01-09 11:02:19','0000-00-00 00:00:00','0000-00-00 00:00:00','',0,0,0,'','','','NCR'),(38,'asdasdas',NULL,'EF659FB6063F140709121529FE169E3D3B3A7DC4*','09152801591',0,'',0,'0','0000-00-00',0,'2014-01-09 11:07:00','0000-00-00 00:00:00','0000-00-00 00:00:00','',0,0,0,'','','','NCR'),(39,'',NULL,'003C4BAD85CCF46636C667D5FEF4DCE9D59CAA67*','09152801591',0,'samgavinio@yahoo.com',0,'0','0000-00-00',0,'2014-01-09 11:07:06','0000-00-00 00:00:00','0000-00-00 00:00:00','',0,0,0,'','','','NCR'),(40,'',NULL,'003C4BAD85CCF46636C667D5FEF4DCE9D59CAA67*','09152801591',0,'samgavinio@yahoo.com',0,'0','0000-00-00',0,'2014-01-09 11:07:18','0000-00-00 00:00:00','0000-00-00 00:00:00','',0,0,0,'','','','NCR'),(41,'shjaksdjasj',NULL,'F1AEDD9551B3C154A0EEFF0953E777A090202797*','09152801591',0,'samgavinio@yahoo.com',0,'0','0000-00-00',0,'2014-01-09 11:08:20','0000-00-00 00:00:00','0000-00-00 00:00:00','',0,0,0,'','','','Cagayan Valley'),(42,'jkkl090',NULL,'6B1D0D501EE39738B2B37DB681223A95D9B04064*','09152801591',0,'samgavinio@yahoo.com',0,'0','0000-00-00',0,'2014-01-09 11:11:48','0000-00-00 00:00:00','0000-00-00 00:00:00','',0,0,0,'','','','NCR'),(43,'samuelgavini98989',NULL,'F2DA2B5825E404D48340178BDED1AC4B66CBEE49*','',0,'samgavinio@yahoo.com',0,'0','0000-00-00',0,'2014-01-09 12:09:00','0000-00-00 00:00:00','0000-00-00 00:00:00','',0,0,0,'','','','NCR'),(44,'samsamsam1234',NULL,'FABE96B2ED0AFA1E0B00883BD6EA3157B5E61236*','09152801591',1,'',0,'0','0000-00-00',0,'2014-01-09 16:45:16','0000-00-00 00:00:00','0000-00-00 00:00:00','',0,0,0,'','','','NCR'),(45,'username_test','','4A8B8D974A8D639BB4A8C72CBBCA4126E28943C1*','',0,'samgavinio@yahoo.com',1,'0','0000-00-00',0,'2014-01-10 15:22:04','0000-00-00 00:00:00','2014-01-10 15:24:53','192.168.3.116',1,0,0,'','','','NCR'),(46,'username_test2','','064C0838293B03058E4274261AEA338F1FFFFFB8*','',0,'samgavinio@yahoo.com',0,'0','0000-00-00',0,'2014-01-10 15:29:10','0000-00-00 00:00:00','2014-01-10 15:31:27','192.168.3.116',1,0,0,'','','','NCR'),(47,'username_test3',NULL,'AE1FF5BB83F23B64B675DA47864F56224DF8111A*','',0,'samgavinio@yahoo.com',0,'0','0000-00-00',0,'2014-01-10 15:38:52','0000-00-00 00:00:00','0000-00-00 00:00:00','',0,0,0,'','','','NCR'),(48,'username_test4',NULL,'201B6EC34861C9E367B4D3FB14F3592FB3F4116A*','',0,'samgavinio@yahoo.com',0,'0','0000-00-00',0,'2014-01-10 15:42:17','0000-00-00 00:00:00','0000-00-00 00:00:00','',0,0,0,'','','','NCR'),(49,'username_test5',NULL,'335C1B3A2C628DEA1EDFF30B439D1DAA09A8F89B*','',0,'samgavinio@yahoo.com',0,'0','0000-00-00',0,'2014-01-10 15:45:04','0000-00-00 00:00:00','0000-00-00 00:00:00','',0,0,0,'','','','NCR'),(50,'username_test6',NULL,'D2B3537A823780C3FE16A42CA582A1A087BE4D8F*','',0,'samgavinio@yahoo.com',0,'0','0000-00-00',0,'2014-01-10 15:47:38','0000-00-00 00:00:00','0000-00-00 00:00:00','',0,0,0,'','','','NCR'),(51,'username_test7',NULL,'8833CF8793B6A6AF3BF9C3F54661072F0105B4B8*','',0,'samgavinio@yahoo.com',1,'0','0000-00-00',0,'2014-01-10 15:50:55','0000-00-00 00:00:00','0000-00-00 00:00:00','',0,0,0,'','','','NCR'),(52,'username_test8',NULL,'4E40539002C4F45D1A1E253F05578DE99F9396C1*','',0,'samgavinio@yahoo.com',1,'0','0000-00-00',0,'2014-01-10 15:57:30','0000-00-00 00:00:00','0000-00-00 00:00:00','',0,0,0,'','','','NCR'),(53,'username_test10',NULL,'3DD34FF000731D97C640EEBADB23B8052D9B5F53*','',0,'samgavinio@yahoo.com',1,'0','0000-00-00',0,'2014-01-10 15:58:43','0000-00-00 00:00:00','0000-00-00 00:00:00','',0,0,0,'','','','NCR');
/*!40000 ALTER TABLE `es_member` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `es_optional_attrdetail`
--

DROP TABLE IF EXISTS `es_optional_attrdetail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `es_optional_attrdetail` (
  `id_optional_attrdetail` int(11) NOT NULL,
  `head_id` int(11) NOT NULL,
  `value_name` varchar(45) DEFAULT '',
  `value_price` varchar(45) DEFAULT '0',
  `product_img_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_optional_attrdetail`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `es_optional_attrdetail`
--

LOCK TABLES `es_optional_attrdetail` WRITE;
/*!40000 ALTER TABLE `es_optional_attrdetail` DISABLE KEYS */;
/*!40000 ALTER TABLE `es_optional_attrdetail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `es_optional_attrhead`
--

DROP TABLE IF EXISTS `es_optional_attrhead`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `es_optional_attrhead` (
  `id_optional_attrhead` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `field_name` varchar(45) DEFAULT '',
  PRIMARY KEY (`id_optional_attrhead`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `es_optional_attrhead`
--

LOCK TABLES `es_optional_attrhead` WRITE;
/*!40000 ALTER TABLE `es_optional_attrhead` DISABLE KEYS */;
/*!40000 ALTER TABLE `es_optional_attrhead` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `es_product`
--

LOCK TABLES `es_product` WRITE;
/*!40000 ALTER TABLE `es_product` DISABLE KEYS */;
INSERT INTO `es_product` VALUES (3,'Galaxy','987654321','ddddddddddddddddddddddddddddddddddddddddddddddddddddddddasasaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa','This is my decription for my First item<br>','New other (see details)','KEYWORD',9000,1,3,1,0,0,0,0,0,1,'','2013-12-19 11:25:19','2013-12-19 11:25:19',0,0),(4,'Galaxy Car','01029384856','This is my Second item','This is my decription for my Second item<br>','New other (see details)','KEYWORD',9000,1,3,1,0,0,0,0,0,1,'','2013-12-19 11:27:40','2013-12-19 11:27:40',0,0),(5,'iPhone Selling with TV and E-Fan','01029384856','This is my Thirid item','This is my decription for my Thirid item<br>','New','KEYWORD',100000,1,3,1,0,0,0,0,0,1,'','2013-12-19 11:30:27','2013-12-19 11:30:27',0,0),(6,'Lace with zoo','008808080808','ikaw na bahala','judge it :D <br>','Manufacturer refurbished','KEYWORD',500,1,9,1,0,0,0,0,0,1,'','2013-12-19 11:49:40','2013-12-19 11:49:40',0,0),(7,'Title','createSmallSize','Description','createSmallSize<br>','Manufacturer refurbished','KEYWORD',200,1,9,1,0,0,0,0,0,1,'','2013-12-19 15:18:45','2013-12-19 15:18:45',0,0),(8,'PRINTED THICK WINTER PANTS','123445667789876543','Some more description here. ','This is the description describing the product.','New','KEYWORD',2199.99,2,37,1,0,0,0,0,0,1,'','2013-12-17 09:50:57','2013-12-19 15:18:45',0,31),(9,'asasasas','asasasa','asasass','asasas<br>','New','KEYWORD',1233,2,3,1,0,0,0,0,0,1,'','2014-01-02 10:28:25','2014-01-02 10:28:25',0,0),(10,'asdasdas','21212','dasdas','dasdas<br>','New','KEYWORD',3232,1,61,1,0,0,0,0,0,1,'','2014-01-02 10:30:04','2014-01-02 10:30:04',0,0),(11,'dsadsad','sadasd','asdas','dasd<br>','New','KEYWORD',2122,1,58,1,0,0,0,0,0,1,'','2014-01-02 10:31:03','2014-01-02 10:31:03',0,0),(12,'2121','2121','2121','212<br>','New','KEYWORD',2121,1,47,1,0,0,0,0,0,1,'','2014-01-02 10:31:46','2014-01-02 10:31:46',0,0),(13,'21212','2121','1212','2121<br>','New','KEYWORD',21212,1,57,1,0,0,0,0,0,1,'','2014-01-02 10:34:42','2014-01-02 10:34:42',0,0),(14,'asdasd','asdasdsa','asdasd','asdasd<br>','New','KEYWORD',2112,1,48,1,0,0,0,0,0,1,'','2014-01-02 10:35:44','2014-01-02 10:35:44',0,0),(15,'2121','2121','2121','2121<br>','New','KEYWORD',2121,1,60,1,0,0,0,0,0,1,'','2014-01-02 10:39:59','2014-01-02 10:39:59',0,0),(16,'dsdsd','sdfsd','fsdf','sdfsd<br>','New','KEYWORD',2121,1,3,1,0,0,0,0,0,1,'','2014-01-02 10:51:42','2014-01-02 10:51:42',0,0),(17,'sdasdas','asdasd','dasd','sadasd<br>','New other (see details)','KEYWORD',12121,1,13,1,0,0,0,0,0,1,'','2014-01-09 14:44:37','2014-01-09 14:44:37',0,0),(18,'sdasdsad','1212','dsadas121','dasd<br>','New other (see details)','KEYWORD',1,1,50,1,0,0,0,0,0,1,'','2014-01-09 14:46:43','2014-01-09 14:46:43',0,0),(19,'sadasd','1212','dasdsa','dsads<br>','New','KEYWORD',212121,1,58,1,0,0,0,0,0,1,'','2014-01-09 14:47:25','2014-01-09 14:47:25',0,0),(20,'dsasdas','12121','dasdasdasdas','asdas<br>','Used','KEYWORD',212121,1,84,1,0,0,0,0,0,1,'','2014-01-09 14:49:14','2014-01-09 14:49:14',0,0),(21,'asdas','dassd','sadda','sdasd<br>','New other (see details)','KEYWORD',23232,1,3,1,0,0,0,0,0,1,'','2014-01-09 14:52:39','2014-01-09 14:52:39',0,0),(22,'asdasd','2121','asdas','asdas<br>','Manufacturer refurbished','KEYWORD',2121,1,9,1,0,0,0,0,0,1,'','2014-01-09 14:53:53','2014-01-09 14:53:53',0,0),(23,'Test','53423434','sasas','sdadasdasdasd<br><br>','New','KEYWORD',21212,1,35,1,0,0,0,0,0,1,'','2014-01-09 16:52:28','2014-01-09 16:52:28',0,0),(24,'sasa','dsdsd','dsdsd','dsds<br>','New other (see details)','KEYWORD',32323,1,9,1,0,0,0,0,0,1,'','2014-01-09 16:53:37','2014-01-09 16:53:37',0,0),(25,'dasd','2121','12121','a<img src=\"http://i.imgur.com/jb83N3B.jpg\" width=\"736\"><br>','New other (see details)','KEYWORD',1212,1,9,1,0,0,0,0,0,1,'','2014-01-09 17:59:10','2014-01-09 17:59:10',0,0),(26,'23123123','423423','4324234','23432423<br>','New','KEYWORD',212,1,3,1,0,0,0,0,0,1,'','2014-01-09 19:56:09','2014-01-09 19:56:09',0,0),(27,'dasdas','dasdas','dasd','sadsa<br>','New other (see details)','KEYWORD',212,1,3,1,0,0,0,0,0,1,'','2014-01-09 19:58:06','2014-01-09 19:58:06',0,0),(28,'21212','dasd','dsads','a<br>','New','KEYWORD',212,1,3,1,0,0,0,0,0,1,'','2014-01-09 20:00:54','2014-01-09 20:00:54',0,0),(29,'23123123','3232','sadsa','<p>dsad</p>\r\n','New','KEYWORD',2222,1,9,1,0,0,0,0,0,1,'','2014-01-10 17:10:04','2014-01-10 17:10:04',0,0),(30,'12212','12121','2121','<p>212121</p>\r\n','New','KEYWORD',2121,1,3,1,0,0,0,0,0,1,'','2014-01-10 17:18:54','2014-01-10 17:18:54',0,0);
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
) ENGINE=InnoDB AUTO_INCREMENT=202 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `es_product_attr`
--

LOCK TABLES `es_product_attr` WRITE;
/*!40000 ALTER TABLE `es_product_attr` DISABLE KEYS */;
INSERT INTO `es_product_attr` VALUES (29,3,14,'10.0 mp',0),(30,3,7,'Unlocked',0),(31,3,2,'Beige',0),(32,3,2,'Black',0),(33,3,2,'Purple',0),(34,3,8,'Prepaid',0),(35,3,17,'Philippines',0),(36,3,13,'Gps',0),(37,3,4,'Samsung galaxy note ii',0),(38,3,6,'123456789',0),(39,3,9,'Android',0),(40,3,11,'64gb',0),(41,3,12,'Bar',0),(42,3,1,'Casing',100),(43,4,3,'Philips',0),(44,4,14,'0.1 mp',0),(45,4,7,'Cellular one',0),(46,4,2,'Beige',0),(47,4,2,'Black',0),(48,4,2,'Blue',0),(49,4,2,'Brown',0),(50,4,2,'Clear',0),(51,4,2,'Gold',0),(52,4,2,'Green',0),(53,4,2,'Grey',0),(54,4,2,'Orange',0),(55,4,2,'Pink',0),(56,4,2,'Purple',0),(57,4,2,'Red',0),(58,4,2,'Silver',0),(59,4,2,'White',0),(60,4,2,'Yellow',0),(61,4,2,'Multi-color',0),(62,4,8,'Prepaid',0),(63,4,17,'Philippines',0),(64,4,13,'3g data capable',0),(65,4,13,'Near field communication',0),(66,4,13,'Music player',0),(67,4,13,'4g data capable',0),(68,4,13,'Bluetooth enabled',0),(69,4,13,'Gps',0),(70,4,13,'Qwerty keyboard',0),(71,4,13,'Fingerprint sensor',0),(72,4,13,'Global ready',0),(73,4,13,'Internet browser',0),(74,4,4,'Lg optimus elite',0),(75,4,6,'9998888654',0),(76,4,9,'Android',0),(77,4,11,'1gb',0),(78,4,12,'Slider',0),(79,4,1,'Casing',200),(80,4,1,'Lace',159),(81,5,3,'Apple',0),(82,5,15,'Extra battery',0),(83,5,14,'10.0 mp',0),(84,5,7,'Virgin mobile',0),(85,5,2,'Black',0),(86,5,8,'Without contract',0),(87,5,17,'Philippines',0),(88,5,13,'3g data capable',0),(89,5,13,'Near field communication',0),(90,5,13,'Music player',0),(91,5,13,'4g data capable',0),(92,5,13,'Bluetooth enabled',0),(93,5,13,'Gps',0),(94,5,13,'Qwerty keyboard',0),(95,5,13,'Fingerprint sensor',0),(96,5,13,'Global ready',0),(97,5,13,'Internet browser',0),(98,5,4,'Iphone 5s',0),(99,5,6,'00000000000000',0),(100,5,9,'Ios - apple',0),(101,5,11,'64gb',0),(102,5,12,'Bar',0),(103,5,1,'Casing',200),(104,5,1,'Lace',159),(105,5,1,'motolite Charger',2000),(106,6,2,'Red',0),(107,6,17,'Philippines',0),(108,6,6,'090909',0),(109,7,2,'Red',0),(110,7,17,'Philippines',0),(111,7,6,'12345678',0),(112,8,18,'Red',0),(113,8,18,'White',0),(114,8,18,'Gold',1000),(115,8,19,'XS',0),(116,8,19,'S',0),(117,8,19,'L',500),(118,16,3,'Lg',0),(119,16,15,'Armband',0),(120,16,15,'Faceplate or decals',0),(121,16,14,'0.3 mp',0),(122,16,7,'Rogers wireless',0),(123,16,16,'dasdas',0),(124,16,2,'Beige',0),(125,16,8,'Without contract',0),(126,16,17,'Philippines',0),(127,16,13,'3g data capable',0),(128,16,13,'Bluetooth enabled',0),(129,16,4,'Htc one s',0),(130,16,6,'fsdfsd',0),(131,16,9,'Blackberry 3-7',0),(132,16,11,'16gb',0),(133,16,12,'Flip',0),(134,17,2,'Beige',0),(135,17,17,'United states of america (usa)',0),(136,17,6,'dsadsa',0),(137,21,3,'Blackberry',0),(138,21,14,'0.5 mp',0),(139,21,7,'Boost mobile',0),(140,21,16,'dasdas',0),(141,21,2,'Gold',0),(142,21,8,'Without contract',0),(143,21,17,'Philippines',0),(144,21,13,'3g data capable',0),(145,21,4,'Iphone 5c',0),(146,21,6,'dasd',0),(147,21,9,'Android',0),(148,21,11,'128mb',0),(149,21,12,'Slider',0),(150,22,2,'Beige',0),(151,22,17,'Philippines',0),(152,22,6,'asdasdas',0),(153,23,1,'medium size',1000),(154,23,1,'color red',15000),(155,24,2,'Black',0),(156,24,2,'Brown',0),(157,24,2,'Clear',0),(158,24,2,'Gold',0),(159,24,17,'Philippines',0),(160,24,6,'dsdsds',0),(161,24,1,'red color',1000),(162,25,2,'Beige',0),(163,25,2,'Black',0),(164,25,2,'Red',0),(165,25,2,'Silver',0),(166,25,17,'Philippines',0),(167,25,6,'dsadsads',0),(168,25,1,'1212',212),(169,26,3,'Hp',0),(170,26,15,'Armband',0),(171,26,15,'Extra power charger (ac)',0),(172,26,14,'0.3 mp',0),(173,26,7,'Metropcs',0),(174,26,16,'32312312',0),(175,26,2,'Gold',0),(176,26,8,'With contract',0),(177,26,17,'Philippines',0),(178,26,13,'3g data capable',0),(179,26,13,'Gps',0),(180,26,13,'Internet browser',0),(181,26,4,'Htc one v',0),(182,26,6,'3213123',0),(183,26,9,'Maemo',0),(184,26,11,'40mb',0),(185,26,12,'Bar',0),(186,27,3,'Audiovox',0),(187,27,15,'Armband',0),(188,27,15,'Case',0),(189,27,15,'Extra cable(s)',0),(190,27,14,'0.5 mp',0),(191,28,3,'Audiovox',0),(192,28,15,'Armband',0),(193,28,9,'Firefox os',0),(194,29,2,'Beige',0),(195,29,17,'Philippines',0),(196,29,6,'dasd',0),(197,30,3,'Audiovox',0),(198,30,15,'Armband',0),(199,30,15,'Dock or cradle',0),(200,30,14,'1.2 mp',0),(201,30,7,'Qwest',0);
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
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `es_product_image`
--

LOCK TABLES `es_product_image` WRITE;
/*!40000 ALTER TABLE `es_product_image` DISABLE KEYS */;
INSERT INTO `es_product_image` VALUES (2,'./assets/product/3_1_20131219/3_1_201312190.png','image/png',3,1),(3,'./assets/product/4_1_20131219/4_1_201312190.jpg','image/jpeg',4,1),(4,'./assets/product/5_1_20131219/5_1_201312190.jpg','image/jpeg',5,1),(5,'./assets/product/6_1_20131219/6_1_201312190.jpg','image/jpeg',6,1),(6,'./assets/product/7_1_20131219/7_1_201312190.jpg','image/jpeg',7,1),(7,'./assets/product/8_1_20131218/8_1_201312180.jpg','image/jpeg',8,1),(8,'./assets/product/8_1_20131218/8_1_201312181.jpg','image/jpeg',8,0),(9,'./assets/product/8_1_20131218/8_1_201312182.jpg','image/jpeg',8,0),(10,'','image/jpeg',12,0),(11,'./assets/product/13_1_20140102/13_1_201401020.jpg','image/jpeg',13,0),(12,'./assets/product/14_1_20140102/14_1_201401020.jpg','image/jpeg',14,0),(13,'./assets/product/15_1_20140102/15_1_201401020.jpg','image/jpeg',15,0),(14,'./assets/product/16_1_20140102/16_1_201401020.jpg','image/jpeg',16,0),(15,'./assets/product/16_1_20140102/16_1_201401021.jpg','image/jpeg',16,0),(16,'./assets/product/16_1_20140102/16_1_201401022.jpg','image/jpeg',16,1),(17,'./assets/product/16_1_20140102/16_1_201401023.jpg','image/jpeg',16,0),(18,'./assets/product/16_1_20140102/16_1_201401024.jpg','image/jpeg',16,0),(19,'./assets/product/16_1_20140102/16_1_201401025.jpg','image/jpeg',16,0),(20,'./assets/product/16_1_20140102/16_1_201401026.jpg','image/jpeg',16,0),(21,'./assets/product/16_1_20140102/16_1_201401027.jpg','image/jpeg',16,0),(22,'./assets/product/17_1_20140109/17_1_201401090.jpg','image/jpeg',17,1),(23,'./assets/product/18_1_20140109/18_1_201401090.jpg','image/jpeg',18,1),(24,'./assets/product/19_1_20140109/19_1_201401090.jpg','image/jpeg',19,1),(25,'./assets/product/20_1_20140109/20_1_201401090.jpg','image/jpeg',20,1),(26,'./assets/product/21_1_20140109/21_1_201401090.jpg','image/jpeg',21,1),(27,'./assets/product/22_1_20140109/22_1_201401090.jpg','image/jpeg',22,1),(28,'./assets/product/23_1_20140109/23_1_201401090.jpg','image/jpeg',23,1),(29,'./assets/product/23_1_20140109/23_1_201401091.jpg','image/jpeg',23,0),(30,'./assets/product/23_1_20140109/23_1_201401092.jpg','image/jpeg',23,0),(31,'./assets/product/23_1_20140109/23_1_201401093.jpg','image/jpeg',23,0),(32,'./assets/product/24_1_20140109/24_1_201401090.jpg','image/jpeg',24,1),(33,'./assets/product/24_1_20140109/24_1_201401091.jpg','image/jpeg',24,0),(34,'./assets/product/24_1_20140109/24_1_201401092.jpg','image/jpeg',24,0),(35,'./assets/product/25_1_20140109/25_1_201401090.jpg','image/jpeg',25,1),(36,'./assets/product/26_1_20140109/26_1_201401090.png','image/png',26,1),(37,'./assets/product/27_1_20140109/27_1_201401090.png','image/png',27,1),(38,'./assets/product/28_1_20140109/28_1_201401090.jpg','image/jpeg',28,1),(39,'./assets/product/29_1_20140110/29_1_201401100.png','image/png',29,1),(40,'./assets/product/30_1_20140110/30_1_201401100.jpg','image/jpeg',30,1),(41,'./assets/product/30_1_20140110/30_1_201401101.jpg','image/jpeg',30,0);
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
  `is_show` tinyint(1) NOT NULL DEFAULT '1',
  `product_id` int(11) NOT NULL DEFAULT '0',
  `datehidden` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id_review`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `es_product_review`
--

LOCK TABLES `es_product_review` WRITE;
/*!40000 ALTER TABLE `es_product_review` DISABLE KEYS */;
INSERT INTO `es_product_review` VALUES (29,2,'2014-01-08 11:24:33',4,'dasd','asdas',1,8,'0000-00-00 00:00:00'),(30,2,'2014-01-08 11:24:41',4,'dasdas','das',1,8,'0000-00-00 00:00:00'),(31,2,'2014-01-08 11:28:25',4,'sadasd','asdas',1,8,'0000-00-00 00:00:00'),(32,2,'2014-01-08 12:01:38',2,'asdasd','asd',1,8,'0000-00-00 00:00:00'),(33,2,'2014-01-08 12:02:20',0,'asdasd','sadas',1,8,'0000-00-00 00:00:00'),(34,2,'2014-01-08 12:02:26',2,'sdas','dasd',1,8,'0000-00-00 00:00:00'),(35,2,'2014-01-08 12:02:30',3,'asdas','dasd',1,8,'0000-00-00 00:00:00'),(36,2,'2014-01-08 12:02:35',3,'<script>alert(\'xss attack\')</script>','dasd',1,8,'0000-00-00 00:00:00'),(37,2,'2014-01-08 12:02:39',3,'dasdas','dasd',1,8,'0000-00-00 00:00:00'),(38,2,'2014-01-08 12:02:54',3,'dasdas','das',1,8,'0000-00-00 00:00:00'),(39,2,'2014-01-08 12:02:59',5,'asdasd','asdasdsadsad',1,8,'0000-00-00 00:00:00'),(40,2,'2014-01-08 12:03:04',5,'lkljkl','kjljkl',1,8,'0000-00-00 00:00:00'),(41,2,'2014-01-08 12:07:46',4,'dsadasdsa','dsadas',1,8,'0000-00-00 00:00:00'),(42,2,'2014-01-08 12:09:07',5,'<p>test</p>','dasdas',1,8,'0000-00-00 00:00:00'),(43,2,'2014-01-08 12:33:20',0,'<script>alert(\'hi\');</script>','<script>alert(\'hi\');</script>',1,8,'0000-00-00 00:00:00'),(44,2,'2014-01-08 14:31:32',3,'dasdas','dasd',1,6,'0000-00-00 00:00:00'),(45,15,'2014-01-09 11:31:43',5,'samgavinio','samgavinio55',1,16,'0000-00-00 00:00:00'),(46,2,'2014-01-09 15:07:37',5,'Awesom','5 star star star',1,22,'0000-00-00 00:00:00'),(47,2,'2014-01-09 19:58:32',3,'dasdas','dasd',1,27,'0000-00-00 00:00:00'),(48,2,'2014-01-10 11:35:34',5,'sam_109','sam109',1,8,'0000-00-00 00:00:00'),(49,2,'2014-01-10 12:26:34',0,'great great','<script>alert(\'great\');</script>',1,8,'0000-00-00 00:00:00'),(50,2,'2014-01-10 16:51:22',5,'fjisdjfkj','kjkjkjkjjsam',1,22,'0000-00-00 00:00:00');
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `es_school`
--

LOCK TABLES `es_school` WRITE;
/*!40000 ALTER TABLE `es_school` DISABLE KEYS */;
INSERT INTO `es_school` VALUES (1,1,'1222',2001,'1',1),(2,2,'dasaa',2155,'1',1);
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
-- Table structure for table `es_verifcode`
--

DROP TABLE IF EXISTS `es_verifcode`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `es_verifcode` (
  `id_member` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `emailcode` varchar(255) DEFAULT '',
  `email` varchar(45) DEFAULT NULL,
  `mobilecode` varchar(255) DEFAULT '',
  `mobile` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`id_member`),
  UNIQUE KEY `username_UNIQUE` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `es_verifcode`
--

LOCK TABLES `es_verifcode` WRITE;
/*!40000 ALTER TABLE `es_verifcode` DISABLE KEYS */;
INSERT INTO `es_verifcode` VALUES (7,'ashdashdjas','140f6969d5213fd0ece03148e62e461e','samgavinio@yahoo.com','Kzx3CK',''),(8,'dasdasdas','f47d0ad31c4c49061b9e505593e3db98','samgavinio@yahoo.com','YVEEW6',''),(10,'sksjfkjshfjags','5737034557ef5b8c02c0e46513b98f90','samgavinio@yahoo.com','vTEhfN',''),(11,'skjdhfkahj','5dd9db5e033da9c6fb5ba83c7a7ebea9','samgavinio@yahoo.com','DugLnQ',''),(12,'samgavinio','32b30a250abd6331e03a2a1f16466346','samgavinio@yahoo.com','zMEzTe',''),(13,'samueljzhfjah','55a7cf9c71f1c9c495413f934dd1a158','samgavinio@yahoo.com','Z8DcrK',''),(14,'samskajdksajd98989','2dea61eed4bceec564a00115c4d21334','','28eGLz','09152801591'),(15,'samuelgavinio33','357a6fdf7642bf815a88822c447d9dc4','samgavinio@yahoo.com','t6NitG','09152801591'),(16,'samsamsaslaksl','5751ec3e9a4feab575962e78e006250d','samgavinio@yahoo.com','s1Eias','09152801591'),(17,'root_root','cfa0860e83a4c3a763a7e62d825349f7','samgavinio@yahoo.com','2s6cCx','09152801591'),(18,'samsamsamsam','7ef605fc8dba5425d6965fbd4c8fbe1f','samgavinio@yahoo.com','jk1fu9',''),(19,'','ea5d2f1c4608232e07d3aa3d998e5135','samgavinio@yahoo.com','9i2uvN','09152801591'),(22,'samgavini0909077','903ce9225fca3e988c2af215d4e544d3','samgavinio@yahoo.com','SxWMc8',''),(24,'jdkfjkdfjkdjf','82489c9737cc245530c7a6ebef3753ec','samgavinio@yahoo.com','mZdpJy',''),(25,'samgavinioioioio','43feaeeecd7b2fe2ae2e26d917b6477d','samgavinio@yahoo.com','9pLpjL','09152801591'),(26,'asdasdasd','352407221afb776e3143e8a1a0577885','samgavinio@yahoo.com','UbrF7t',''),(27,'easyshop2014','84117275be999ff55a987b9381e01f96','samgavinio@yahoo.com','h3Z3vB',''),(29,'samsamsamsam1212','1c383cd30b7c298ab50293adfecb7b18','samgavinio@yahoo.com','zEULdX',''),(30,'samghghagdjash','a8e864d04c95572d1aece099af852d0a','samgavinio@yahoo.com','N22nyh',''),(32,'dfjkdajfkaj','043c3d7e489c69b48737cc0c92d0f3a2','samgavinio@yahoo.com','EVA7CH',''),(33,'samgavinjsdhfhj','0cb929eae7a499e50248a3a78f7acfc7','samgavinio@yahoo.com','zGwXhK',''),(35,'asdjaskldjaksjdlak','67d16d00201083a2b118dd5128dd6f59','samgavinio@yahoo.com','BH7rCA','09152801591'),(36,'adsdasdasd','98b297950041a42470269d56260243a1','samgavinio@yahoo.com','wmAQzu','09152801591'),(37,'asdajshdjash_j','1efa39bcaec6f3900149160693694536','samgavinio@yahoo.com','sucJ1S','09152801591'),(38,'asdasdas','64223ccf70bbb65a3a4aceac37e21016','','dhaLst','09152801591'),(41,'shjaksdjasj','a8baa56554f96369ab93e4f3bb068c22','samgavinio@yahoo.com','WHzt71','09152801591'),(42,'jkkl090','fb89705ae6d743bf1e848c206e16a1d7','samgavinio@yahoo.com','Rm4R4U','09152801591'),(43,'samuelgavini98989','41f1f19176d383480afa65d325c06ed0','samgavinio@yahoo.com','Y42KQS',''),(44,'samsamsam1234','1728efbda81692282ba642aafd57be3a','','6C8BhU','09152801591'),(45,'username_test','ccb1d45fb76f7c5a0bf619f979c6cf36','samgavinio@yahoo.com','Xhm9Uj',''),(46,'username_test2','f2217062e9a397a1dca429e7d70bc6ca','samgavinio@yahoo.com','RjfzGC',''),(47,'username_test3','07a96b1f61097ccb54be14d6a47439b0','samgavinio@yahoo.com','Wm12G6',''),(48,'username_test4','17e62166fc8586dfa4d1bc0e1742c08b','samgavinio@yahoo.com','eAwJA4',''),(49,'username_test5','6c4b761a28b734fe93831e3fb400ce87','samgavinio@yahoo.com','GNhYmn',''),(50,'username_test6','d7a728a67d909e714c0774e22cb806f2','samgavinio@yahoo.com','tQaTcf',''),(51,'username_test7','6f4922f45568161a8cdf4ad2299f6d23','samgavinio@yahoo.com','7fJ4A2',''),(52,'username_test8','069d3bb002acd8d7dd095917f9efe4cb','samgavinio@yahoo.com','BBRZ5d',''),(53,'username_test10','301ad0e3bd5cb1627a2044908a42fdc2','samgavinio@yahoo.com','Fdjbez',''),(104,'sdlvkj123','2ba596643cbbbc20318224181fa46b28',NULL,'hXy6WY',NULL),(105,'batman123','9be40cee5b0eee1462c82c6964087ff9','','Q2iwVY','09054388942'),(106,'zdlkvjasef123','61b4a64be663682e8cb037d9719ad8cd','janz.stephen@gmail.com','pSkeLN',''),(107,'sjanzrs','c24cd76e1ce41366a4bbe8a49b02a028','janz.stephen@gmail.com','8Hx9FB','09054388942'),(109,'kalbo123','5737c6ec2e0716f3d8a7a5c4e0de0d9a','janz.stephen@gmail.com','wAwjgZ',''),(110,'markmusni','00ec53c4682d36f5c4359f4ae7bd7ba1','janz.stephen@gmail.com','rLLCmC','09054388942'),(111,'abrakadabra','46ba9f2a6976570b0353203ec4474217','','L8UWdQ','09054388942'),(115,'makina','24681928425f5a9133504de568f5f6df','','qYnTfG','09054388942'),(116,'kilikili','18997733ec258a9fcaf239cc55d53363','','tw12n9','09054388942'),(119,'loki123','a8c88a0055f636e4a163a5e3d16adab7','','jZecBe','09054388942'),(120,'sdgawe123','94f6d7e04a4d452035300f18b984988c','','V3LqmS','09054388942'),(121,'halgijs123','e07413354875be01a996dc560274708e','','QHs141','09054388942'),(124,'dlzfbjk132','f64eac11f2cd8f0efa196f8ad173178e','','46Gsjd','09054388942'),(126,'dzlksjd123','8613985ec49eb8f757ae6439e879bb2a','','Va1gBZ','09054388942'),(128,'zxlbkzjdf123','9dcb88e0137649590b755372b040afad','','G9EHaz','09054388942'),(130,'teletubbies','860320be12a1c050cd7731794e231bd3','','d3Ww53','09054388942'),(251,'amazingrace','84d9ee44e457ddef7f2c4f25dc8fa865','janz.stephen@gmail.com','asuBsM','09054388942'),(263,'easyshop','c7e1249ffc03eb9ded908c236bd1996d','janz.stephen@gmail.com','u9Hecq','09054388942'),(264,'easyshop123','ccb0989662211f61edae2e26d58ea92f','janz.stephen@gmail.com','Bdz1Ma','09054388942'),(266,'montana','c0f168ce8900fa56e57789e2a2f2c9d0','janz.stephen@gmail.com','8G3PyG','09054388942'),(267,'montana3','8b5040a8a5baf3e0e67386c2e3a9b903','','E3rEN7','09054388942'),(269,'kolby','192fc044e74dffea144f9ac5dc9f3395','','1DugLn','09054388942'),(270,'karate','9e3cfc48eccf81a0d57663e129aef3cb','','bfzpgk','09054388942'),(271,'stephenjanz','73278a4a86960eeb576a8fd4c9ec6997','janz.stephen@gmail.com','Pb6p11','09054388942'),(272,'mister','ad13a2a07ca4b7642959dc0c4c740ab6','','X2H7kE','09054388942'),(274,'kadabra123','704afe073992cbe4813cae2f7715336f','','37Nhf6','09054388942'),(275,'kilikili123','cfcd208495d565ef66e7dff9f98764da','','mgma7V','09054388942'),(276,'mokong','013a006f03dbc5392effeb8f18fda755','','gYge6m','09054388942'),(277,'magic123','ec5aa0b7846082a2415f0902f0da88f2','','4Bjwv4','09054388942'),(278,'bote123','4e0cb6fb5fb446d1c92ede2ed8780188','','bbRzS7','09054388942'),(279,'juste','872488f88d1b2db54d55bc8bba2fad1b','','7VLi6q','09054388942'),(280,'hulabira','06eb61b839a0cefee4967c67ccb099dc','','zAYL5u','09054388942');
/*!40000 ALTER TABLE `es_verifcode` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=95 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `es_work`
--

LOCK TABLES `es_work` WRITE;
/*!40000 ALTER TABLE `es_work` DISABLE KEYS */;
INSERT INTO `es_work` VALUES (86,1,'iomkomko','87787',2155,1),(88,1,'8u8u8','h8u8',2155,2),(89,2,'dasd','asd',2155,1),(91,2,'ads','dasd',1901,2),(94,2,'asdas','dsad',1901,3);
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
/*!50003 DROP PROCEDURE IF EXISTS `es_sp_Signup_user` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `es_sp_Signup_user`(
	IN i_username VARCHAR(255),
	IN i_password VARCHAR(255),
	IN i_contactno VARCHAR(45),
	IN i_email VARCHAR(255),
	IN i_region VARCHAR(45)
)
BEGIN
	DECLARE v_pass VARCHAR(255);
	

	START TRANSACTION;
	
	SELECT reverse(PASSWORD(concat(md5(i_username),sha1(i_password)))) into v_pass;

	INSERT INTO `es_member` (`username`, `password`, `contactno`, `email`, `region`, `datecreated`)
	VALUES (i_username, v_pass, i_contactno, i_email, i_region, NOW())
	ON DUPLICATE KEY UPDATE username=i_username, `password`=v_pass, contactno=i_contactno, email=i_email, region=i_region;

	COMMIT;

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

-- Dump completed on 2014-01-10 17:20:17
