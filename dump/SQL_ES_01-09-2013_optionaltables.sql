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
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-01-09 21:04:10
