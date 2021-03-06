-- MySQL dump 10.16  Distrib 10.1.32-MariaDB, for Win32 (AMD64)
--
-- Host: localhost    Database: starter_registration
-- ------------------------------------------------------
-- Server version	10.1.32-MariaDB

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
-- Table structure for table `city`
--

DROP TABLE IF EXISTS `city`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `city` (
  `id_city` int(11) NOT NULL COMMENT 'City Identification',
  `str_city` varchar(60) NOT NULL COMMENT 'City name',
  PRIMARY KEY (`id_city`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Options of cities that can be selected';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customer` (
  `id_customer` int(20) NOT NULL AUTO_INCREMENT COMMENT 'Customer Identification',
  `str_firstname` varchar(30) NOT NULL COMMENT 'First Name',
  `str_lastname` varchar(30) NOT NULL COMMENT 'Last Name',
  `str_telephone` varchar(30) NOT NULL COMMENT 'Telephone',
  `str_address` varchar(100) NOT NULL COMMENT 'Address',
  `num_house` int(11) NOT NULL COMMENT 'House number',
  `str_zip` varchar(15) NOT NULL COMMENT 'Zip code',
  `id_city` int(11) NOT NULL COMMENT 'Identification of the city',
  `str_account` varchar(45) NOT NULL COMMENT 'Account owner',
  `str_iban` varchar(31) NOT NULL COMMENT 'IBAN number',
  PRIMARY KEY (`id_customer`),
  KEY `city_id` (`id_city`),
  CONSTRAINT `city_id` FOREIGN KEY (`id_city`) REFERENCES `city` (`id_city`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1 COMMENT='Customer information table';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `customer_payment`
--

DROP TABLE IF EXISTS `customer_payment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customer_payment` (
  `customer_id` int(20) NOT NULL COMMENT 'Customer Identification',
  `id_payment` varchar(96) NOT NULL COMMENT 'Payment Identification',
  `dt_payment` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp of the payment done',
  PRIMARY KEY (`customer_id`,`id_payment`),
  CONSTRAINT `customer_id` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id_customer`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Information table about which customer did the payments';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `log_customer`
--

DROP TABLE IF EXISTS `log_customer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_customer` (
  `id_customer` int(20) NOT NULL COMMENT 'Customer Identification',
  `dt_activation` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp of customer activation',
  `dt_deactivation` datetime DEFAULT NULL COMMENT 'Timestamp of customer deactivation',
  PRIMARY KEY (`id_customer`),
  CONSTRAINT `id_customer` FOREIGN KEY (`id_customer`) REFERENCES `customer` (`id_customer`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Log the information about the insert and desactivation of a customer';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-12-09 12:34:17
