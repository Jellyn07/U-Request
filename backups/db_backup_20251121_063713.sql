-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: u_request
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `u_request`
--

/*!40000 DROP DATABASE IF EXISTS `u_request`*/;

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `u_request` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `u_request`;

--
-- Table structure for table `activity_logs`
--

DROP TABLE IF EXISTS `activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activity_logs` (
  `logs_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `source` varchar(150) NOT NULL,
  `name` varchar(150) NOT NULL,
  `action` enum('INSERT','UPDATE','DELETE') NOT NULL,
  `description` text DEFAULT NULL,
  `changed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`logs_id`)
) ENGINE=InnoDB AUTO_INCREMENT=85 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_logs`
--

LOCK TABLES `activity_logs` WRITE;
/*!40000 ALTER TABLE `activity_logs` DISABLE KEYS */;
INSERT INTO `activity_logs` VALUES (1,'Administrator','Motorpool Adimen','INSERT','Motorpool Adimen added administrator: Naur Masha','2025-11-14 14:57:41'),(2,'Administrator','Motorpool Adimen','UPDATE','Motorpool Adimen updated administrator: Name from \"Naur Masha\" to \"Yes Masha\"; Contact from \"09294836459\" to \"Motorpool A\"; ','2025-11-14 15:02:04'),(3,'Campus Location','Gsu Adimen','INSERT','Gsu Adimen added campus location: Tagum Unit, Admin Building, BSIT Department Office','2025-11-14 15:08:31'),(4,'Campus Location','Gsu Adimen','UPDATE','Gsu Adimen updated campus location: Exact Location from \"BSIT Department Office\" to \"BSIT Department Office1\"; ','2025-11-14 15:10:23'),(5,'Campus Location','Gsu Adimen','DELETE','Gsu Adimen deleted campus location: Tagum Unit, Admin Building, BSIT Department Office1','2025-11-14 15:12:02'),(6,'Vehicle Request Assignment','Motorpool Adimen','UPDATE','Status changed from \"Pending\" to \"Approved\"; ','2025-11-15 04:17:03'),(7,'Vehicle','Motorpool Adimen','UPDATE','Motorpool Adimen updated vehicle: USeP Bus - Tagum . Changes: Capacity from \"60\" to \"65\"; ','2025-11-15 04:18:25'),(8,'Driver','Motorpool Adimen','UPDATE','Motorpool Adimen updated driver: Name from \"Dry Beer\" to \"Wet Beer\"; ','2025-11-15 04:18:46'),(9,'GSU Personnel','Gsu Adimen','INSERT','Added new GSU Personnel: Blue Finger','2025-11-15 04:20:10'),(10,'Materials','Gsu Adimen','INSERT','Added new material: Paint Yellow, Code: 4, Quantity: 20','2025-11-15 04:20:31'),(11,'Materials','Gsu Adimen','UPDATE','Description changed from \"Paint Yellow\" to \"Paint Yellow Green\"; Status changed from \"Available\" to \"\"; ','2025-11-15 04:20:49'),(12,'Request Status','Gsu Adimen','UPDATE','Tracking ID: TRK-20251024-EA3M9 | Previous Status: \"In Progress\" | New Status: \"Completed\"','2025-11-15 04:21:26'),(13,'Vehicle Request Assignment','Motorpool Adimen','UPDATE','Status changed from \"Pending\" to \"Rejected/Cancelled\"; ','2025-11-16 04:00:31'),(14,'Request Assigned Personnel','Gsu Adimen','INSERT','Assigned Blue Finger to request ID 6','2025-11-16 06:50:54'),(15,'Request Status','Gsu Adimen','UPDATE','Tracking ID: TRK-20251112-K8Q2D | Previous Status: \"In Progress\" | New Status: \"Completed\"','2025-11-16 07:08:42'),(16,'Request Status','System','UPDATE','Tracking ID: TRK-20251112-QUODS | Previous Status: \"In Progress\" | New Status: \"To Inspect\"','2025-11-16 07:09:40'),(17,'Request Status','Gsu Adimen','UPDATE','Tracking ID: TRK-20251112-QUODS | Previous Status: \"To Inspect\" | New Status: \"None\"','2025-11-16 07:20:50'),(18,'Request Status','System','UPDATE','Tracking ID: TRK-20251112-QUODS | Previous Status: \"None\" | New Status: \"To Inspect\"','2025-11-16 07:23:48'),(19,'Request Status','Gsu Adimen','UPDATE','Tracking ID: TRK-20251112-QUODS | Previous Status: \"To Inspect\" | New Status: \"None\"','2025-11-16 07:41:52'),(20,'Request Status','System','UPDATE','Tracking ID: TRK-20251112-QUODS | Previous Status: \"None\" | New Status: \"To Inspect\"','2025-11-16 07:42:09'),(21,'Request Status','Gsu Adimen','UPDATE','Tracking ID: TRK-20251112-QUODS | Previous Status: \"To Inspect\" | New Status: \"In Progress\"','2025-11-16 08:12:28'),(22,'Request Assigned Personnel','Gsu Adimen','INSERT','Assigned Blue Finger to request ID 5','2025-11-16 08:12:28'),(23,'Vehicle Request Assignment','Motorpool Adimen','UPDATE','Status changed from \"Approved\" to \"Completed\"; ','2025-11-17 15:08:09'),(24,'Vehicle Request Assignment','Motorpool Adimen','UPDATE','Status changed from \"Approved\" to \"Completed\"; ','2025-11-17 15:09:28'),(25,'Vehicle','Motorpool Adimen','UPDATE','Motorpool Adimen updated vehicle: USeP Vehicle - Tagum Unit. Changes: Status from \"Available\" to \"Under Maintenance\"; ','2025-11-17 15:11:35'),(26,'Vehicle Request Assignment','Motorpool Adimen','UPDATE','Status changed from \"On Going\" to \"Approved\"; ','2025-11-18 05:35:31'),(27,'Administrator','Motorpool Adimen','UPDATE','Motorpool Adimen updated administrator: ','2025-11-18 05:48:01'),(28,'Administrator','Motorpool Adimen','UPDATE','Motorpool Adimen updated administrator: ','2025-11-18 05:48:26'),(29,'Administrator','Super Admin','UPDATE','Super Admin updated administrator: Contact from \"Motorpool A\" to \"09074836471\"; ','2025-11-18 05:52:26'),(30,'Administrator','Motorpool Adimen','UPDATE','Motorpool Adimen updated administrator: Contact from \"09074836471\" to \"Motorpool A\"; ','2025-11-18 05:53:42'),(31,'Administrator','Super Admin','UPDATE','Super Admin updated administrator: Contact from \"Motorpool A\" to \"09123456789\"; ','2025-11-18 05:54:41'),(32,'Request Status','Gsu Adimen','UPDATE','Tracking ID: TRK-20251112-QUODS | Previous Status: \"In Progress\" | New Status: \"None\"','2025-11-18 07:57:22'),(33,'Request Status','System','UPDATE','Tracking ID: TRK-20251112-QUODS | Previous Status: \"None\" | New Status: \"To Inspect\"','2025-11-18 07:58:18'),(34,'Request Status','Gsu Adimen','UPDATE','Tracking ID: TRK-20251112-QUODS | Previous Status: \"To Inspect\" | New Status: \"None\"','2025-11-18 08:00:58'),(35,'Request Status','System','UPDATE','Tracking ID: TRK-20251112-QUODS | Previous Status: \"None\" | New Status: \"To Inspect\"','2025-11-18 08:02:33'),(36,'Vehicle Request','Jonalyn Gujol','INSERT','Jonalyn Gujol created a new vehicle request with tracking ID TRK-VR20251118-PVZ1H','2025-11-18 10:30:33'),(37,'Vehicle Request','Testing One','INSERT','Testing One created a new vehicle request with tracking ID TRK-VR20251118-EQ0US','2025-11-18 10:42:46'),(38,'Vehicle Request Assignment','Motorpool Adimen','UPDATE','Status changed from \"Pending\" to \"Approved\"; ','2025-11-18 10:54:30'),(39,'Vehicle Request Assignment','Motorpool Adimen','UPDATE','Vehicle changed from USeP Van 2 - Tagum Unit to USeP Bus - Tagum ; Driver changed from Ben Ten to Wet Beer; ','2025-11-18 11:14:37'),(40,'Vehicle Request Assignment','Motorpool Adimen','UPDATE','Vehicle changed from USeP Bus - Tagum  to USeP Van 2 - Tagum Unit; Driver changed from Wet Beer to Ben Ten; ','2025-11-18 11:15:00'),(41,'Vehicle','Motorpool Adimen','UPDATE','Motorpool Adimen updated vehicle: USeP Vehicle - Tagum Unit. Changes: Status from \"Under Maintenance\" to \"Available\"; ','2025-11-18 11:16:23'),(42,'Driver','Super Admin','UPDATE','Super Admin updated driver: Contact from \"09123456781\" to \"09123456783\"; ','2025-11-18 13:55:01'),(43,'Driver','Motorpool Adimen','UPDATE','Motorpool Adimen updated driver: Hire Date changed; ','2025-11-18 13:55:50'),(44,'Driver','Motorpool Adimen','UPDATE','Motorpool Adimen updated driver: Hire Date changed; ','2025-11-18 13:57:40'),(45,'Vehicle Request Assignment','Testing One','UPDATE','Status changed from \"Pending\" to \"Cancelled\"; ','2025-11-19 08:10:54'),(46,'Vehicle Request Assignment','Unknown','UPDATE','Status changed from \"Rejected/Cancelled\" to \"Rejected\"; ','2025-11-19 15:15:26'),(47,'Administrator','Motorpool Adimen','UPDATE','Motorpool Adimen updated administrator: Profile Picture updated; ','2025-11-19 15:31:20'),(48,'Administrator','Motorpool Adimen','UPDATE','Motorpool Adimen updated administrator: Profile Picture updated; ','2025-11-19 15:31:35'),(49,'Administrator','Motorpool Adimen','UPDATE','Motorpool Adimen updated administrator: Profile Picture updated; ','2025-11-19 15:35:24'),(50,'Driver','Motorpool Adimen','UPDATE','Motorpool Adimen updated driver: Name from \"Ben Ten\" to \"Bin Ten\"; ','2025-11-19 16:04:55'),(51,'Request','Kim Luayon','INSERT','New request created. Type: Electrical, Location: SOM - School of Medicine - SB-05','2025-11-20 07:25:21'),(52,'Vehicle Request','Kim Luayon','INSERT','Kim Luayon created a new vehicle request with tracking ID TRK-VR20251120-QK793','2025-11-20 07:28:13'),(53,'Request Status','Gsu Adimen','UPDATE','Tracking ID: TRK-20251120-0BYMR | Previous Status: \"To Inspect\" | New Status: \"In Progress\"','2025-11-20 07:30:23'),(54,'Request Assigned Personnel','Gsu Adimen','INSERT','Assigned Jay Mentos to request ID 70','2025-11-20 07:30:23'),(55,'Request Status','Gsu Adimen','UPDATE','Tracking ID: TRK-20251120-0BYMR | Previous Status: \"In Progress\" | New Status: \"None\"','2025-11-20 07:30:47'),(56,'Materials','Gsu Adimen','UPDATE','Quantity changed from 17 to 16; ','2025-11-20 07:30:47'),(57,'Request Status','Gsu Adimen','UPDATE','Tracking ID: TRK-20251120-0BYMR | Previous Status: \"None\" | New Status: \"Completed\"','2025-11-20 07:31:44'),(58,'Administrator','Super Admin','UPDATE','Super Admin updated administrator: ','2025-11-21 00:27:39'),(59,'Vehicle Request Assignment','Unknown','UPDATE','Status changed from \"Approved\" to \"On Going\"; ','2025-11-21 00:36:16'),(60,'Vehicle Request Assignment','Motorpool Adimen','UPDATE','Status changed from \"Approved\" to \"Completed\"; ','2025-11-21 00:42:51'),(61,'GSU Personnel','Super Admin','UPDATE','Department changed from \"Janitorial\" to \"Landscaping\"; ','2025-11-21 00:51:18'),(62,'GSU Personnel','Super Admin','UPDATE','Department changed from \"Janitorial\" to \"Utility\"; ','2025-11-21 00:51:37'),(63,'Request','Jonalyn Gujol','INSERT','New request created. Type: Carpentry/Masonry, Location: Admin Building - Office of Chancellor','2025-11-21 01:00:31'),(64,'Administrator','Super Admin','UPDATE','Super Admin updated administrator: ','2025-11-21 01:21:52'),(65,'Administrator','Super Admin','UPDATE','Super Admin updated administrator: ','2025-11-21 01:23:31'),(66,'Administrator','Super Admin','UPDATE','Super Admin updated administrator: ','2025-11-21 01:36:09'),(67,'Administrator','Super Admin','UPDATE','Super Admin updated administrator: ','2025-11-21 01:40:43'),(68,'Driver','Super Admin','UPDATE','Super Admin updated driver: Contact from \"09123456780\" to \"09123456781\"; ','2025-11-21 01:47:18'),(69,'Campus Location','Super Admin','INSERT','Super Admin added campus location: Mabini Unit, Gym, Sports Office','2025-11-21 01:48:44'),(70,'Campus Location','Super Admin','UPDATE','Super Admin updated campus location: Exact Location from \"Office of Registrar (OUR)\" to \"Office of Registrar\"; ','2025-11-21 01:49:39'),(71,'Driver','Super Admin','UPDATE','Super Admin updated driver: Name from \"White Bench\" to \"Black Bench\"; ','2025-11-21 01:50:39'),(72,'Vehicle','Super Admin','UPDATE','Super Admin updated vehicle: USeP Bus. Changes: Name from \"USeP Bus - Tagum \" to \"USeP Bus\"; ','2025-11-21 01:51:34'),(73,'Request','Naku Po','INSERT','New request created. Type: Others, Location: Gym - Sports Office','2025-11-21 02:05:39'),(74,'Administrator','Motorpool Adimen','UPDATE','Motorpool Adimen updated administrator: ','2025-11-21 02:08:29'),(75,'Administrator','Gsu Adimen','UPDATE','Gsu Adimen updated administrator: Contact from \"09923456789\" to \"GSU Admin\"; ','2025-11-21 02:14:47'),(76,'Administrator','Super Admin','UPDATE','Super Admin updated administrator: Contact from \"GSU Admin\" to \"09123432121\"; ','2025-11-21 02:15:40'),(77,'Administrator','Super Admin','UPDATE','Super Admin updated administrator: Profile Picture updated; ','2025-11-21 02:39:51'),(78,'Administrator','Super Admin','UPDATE','Super Admin updated administrator: Profile Picture updated; ','2025-11-21 02:47:12'),(79,'Administrator','Super Admin','UPDATE','Super Admin updated administrator: Profile Picture updated; ','2025-11-21 02:53:13'),(80,'Administrator','Super Admin','UPDATE','Super Admin updated administrator: Profile Picture updated; ','2025-11-21 02:54:27'),(81,'Administrator','Super Admin','UPDATE','Super Admin updated administrator: Profile Picture updated; ','2025-11-21 02:56:29'),(82,'Materials','Super Admin','INSERT','Added new material: Laptop, Code: 5, Quantity: 10','2025-11-21 05:21:32'),(83,'Campus Location','Super Admin','INSERT','Super Admin added campus location: Tagum Unit, AFNR Building, GSTET','2025-11-21 05:28:44'),(84,'Vehicle','Super Admin','UPDATE','Super Admin updated vehicle: USeP Van - (Hiace) Tagum Unit. Changes: Name from \"USeP Van - Tagum Unit\" to \"USeP Van - (Hiace) Tagum Unit\"; ','2025-11-21 05:32:17');
/*!40000 ALTER TABLE `activity_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `add_admin_access`
--

DROP TABLE IF EXISTS `add_admin_access`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `add_admin_access` (
  `staff_id` varchar(100) NOT NULL,
  `is_enabled` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`staff_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `add_admin_access`
--

LOCK TABLES `add_admin_access` WRITE;
/*!40000 ALTER TABLE `add_admin_access` DISABLE KEYS */;
INSERT INTO `add_admin_access` VALUES ('2021-00001',1),('2021-011119',0),('2023-00002',1),('2023-000111',0),('2023-00062',1),('2023-00063',0),('2023-012009',0),('2023-012109',0),('2023-012119',1),('2023-101010',1);
/*!40000 ALTER TABLE `add_admin_access` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_access_level`
--

DROP TABLE IF EXISTS `admin_access_level`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_access_level` (
  `accessLevel_id` int(11) NOT NULL,
  `accessLevel_desc` varchar(150) NOT NULL,
  PRIMARY KEY (`accessLevel_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_access_level`
--

LOCK TABLES `admin_access_level` WRITE;
/*!40000 ALTER TABLE `admin_access_level` DISABLE KEYS */;
INSERT INTO `admin_access_level` VALUES (1,'Super Admin'),(2,'GSU Admin'),(3,'Motorpool Admin');
/*!40000 ALTER TABLE `admin_access_level` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `administrator`
--

DROP TABLE IF EXISTS `administrator`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `administrator` (
  `staff_id` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `contact_no` varchar(11) NOT NULL,
  `accessLevel_id` int(11) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  PRIMARY KEY (`staff_id`),
  UNIQUE KEY `email` (`email`),
  KEY `accessLevel_id` (`accessLevel_id`),
  CONSTRAINT `administrator_ibfk_1` FOREIGN KEY (`accessLevel_id`) REFERENCES `admin_access_level` (`accessLevel_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `administrator`
--

LOCK TABLES `administrator` WRITE;
/*!40000 ALTER TABLE `administrator` DISABLE KEYS */;
INSERT INTO `administrator` VALUES ('2021-00001','gsuadmin@usep.edu.ph','Gsu','Adimen','09123456781',2,'K3hWNDZtTlYwRmpwMHlYdjBvWEFPQT09','desktop-wallpaper-sad-funny-cute-plankton-face-plankton.jpg','Active'),('2021-011119','lalala@usep.edu.ph','Yes','Masha','09123456789',3,'WXV6S21tck92QUVyb3VZZzBnV2dNQT09',NULL,'Active'),('2023-00002','motorpooladmin@usep.edu.ph','Motorpool','Adimen','09123456780',3,'UEhQZ1BVUGtuOVNIODVDOFZDSWRrdTZxSk1JeUxsVSt5Qnh1SVdaaklwdz0=','usepbus.jpg','Active'),('2023-000111','testing1@usep.edu.ph','Testing','Two','09123456121',2,'Y2Y4a1ZDKytnM2FOOVVveC83bUFTdz09',NULL,'Active'),('2023-00062','superadmin@usep.edu.ph','Super','Admin','',1,'UEhQZ1BVUGtuOVNIODVDOFZDSWRrdTZxSk1JeUxsVSt5Qnh1SVdaaklwdz0=','Plankton-Spongebob-Series-Iconic-Villain-PNG-thumb_1763693789.png','Active'),('2023-00063','motorpooladmin1@usep.edu.ph','Motmot','Admin','09123432121',2,'Y2Y4a1ZDKytnM2FOOVVveC83bUFTdz09',NULL,'Active'),('2023-012009','testingggg@usep.edu.ph','Blue','Masha','09174836456',3,'WXV6S21tck92QUVyb3VZZzBnV2dNQT09',NULL,'Active'),('2023-012109','testingggghehehe@usep.edu.ph','Green','Masha','09274836456',2,'WXV6S21tck92QUVyb3VZZzBnV2dNQT09','usepbus.jpg','Active'),('2023-012119','testingggghe@usep.edu.ph','Superrrrr','Masha','09274836456',1,'WXV6S21tck92QUVyb3VZZzBnV2dNQT09',NULL,'Active'),('2023-101010','jmomo00226@usep.edu.ph','Jellyn','Omo','09474836471',1,'WXV6S21tck92QUVyb3VZZzBnV2dNQT09',NULL,'Active');
/*!40000 ALTER TABLE `administrator` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trg_administrator_insert` AFTER INSERT ON `administrator` FOR EACH ROW BEGIN
    DECLARE admin_name VARCHAR(150);
    SET admin_name = (SELECT CONCAT(first_name,' ',last_name) FROM administrator WHERE staff_id = @current_staff_id);

    INSERT INTO activity_logs(source, name, action, description)
    VALUES('Administrator', admin_name, 'INSERT', CONCAT(admin_name, ' added administrator: ', NEW.first_name,' ',NEW.last_name));
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER trg_administrator_update
AFTER UPDATE ON administrator
FOR EACH ROW
BEGIN
    DECLARE changes TEXT DEFAULT '';
    DECLARE admin_name VARCHAR(150);

    
    SET admin_name = (SELECT CONCAT(first_name,' ',last_name) FROM administrator WHERE staff_id = @current_staff_id);

    
    IF OLD.first_name != NEW.first_name OR OLD.last_name != NEW.last_name THEN
        SET changes = CONCAT(changes, 'Name from "', OLD.first_name,' ',OLD.last_name,'" to "', NEW.first_name,' ',NEW.last_name,'"; ');
    END IF;
    IF OLD.email != NEW.email THEN
        SET changes = CONCAT(changes, 'Email from "', OLD.email,'" to "', NEW.email,'"; ');
    END IF;
    IF OLD.contact_no != NEW.contact_no THEN
        SET changes = CONCAT(changes, 'Contact from "', OLD.contact_no,'" to "', NEW.contact_no,'"; ');
    END IF;
    IF OLD.accessLevel_id != NEW.accessLevel_id THEN
        SET changes = CONCAT(changes, 'Access Level changed; ');
    END IF;
    IF OLD.status != NEW.status THEN
        SET changes = CONCAT(changes, 'Status from "', OLD.status,'" to "', NEW.status,'"; ');
    END IF;
    IF OLD.profile_picture != NEW.profile_picture THEN
        SET changes = CONCAT(changes, 'Profile Picture updated; ');
    END IF;

    
    INSERT INTO activity_logs(source, name, action, description)
    VALUES(
        'Administrator',
        admin_name,
        'UPDATE',
        CONCAT(admin_name, ' updated administrator: ', changes)
    );
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `campus_locations`
--

DROP TABLE IF EXISTS `campus_locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `campus_locations` (
  `location_id` int(11) NOT NULL AUTO_INCREMENT,
  `unit` varchar(100) NOT NULL,
  `building` varchar(100) NOT NULL,
  `exact_location` varchar(150) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`location_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `campus_locations`
--

LOCK TABLES `campus_locations` WRITE;
/*!40000 ALTER TABLE `campus_locations` DISABLE KEYS */;
INSERT INTO `campus_locations` VALUES (1,'Tagum Unit','PECC - Physical Education Cultural Center','Clinic','2025-10-23 17:02:44'),(2,'Tagum Unit','PECC - Physical Education Cultural Center','Office of Registrar','2025-10-23 17:03:34'),(3,'Tagum Unit','SOM - School of Medicine','Dean\'s Office','2025-10-24 08:04:28'),(7,'Tagum Unit','SOM - School of Medicine','SB-05','2025-10-24 16:05:32'),(8,'Tagum Unit','SOM - School of Medicine','Conference Room','2025-11-06 08:26:47'),(9,'Tagum Unit','Admin Building','Office of Chancellor','2025-11-11 13:41:18'),(11,'Mabini Unit','Gym','Sports Office','2025-11-21 01:48:44'),(12,'Tagum Unit','AFNR Building','GSTET','2025-11-21 05:28:44');
/*!40000 ALTER TABLE `campus_locations` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trg_campus_location_insert` AFTER INSERT ON `campus_locations` FOR EACH ROW BEGIN
    DECLARE admin_name VARCHAR(150);
    SET admin_name = (SELECT CONCAT(first_name,' ',last_name) FROM administrator WHERE staff_id = @current_staff_id);

    INSERT INTO activity_logs(source, name, action, description)
    VALUES(
		'Campus Location',
        admin_name,
        'INSERT',
        CONCAT(admin_name, ' added campus location: ', NEW.unit, ', ', NEW.building, ', ', NEW.exact_location)
    );
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trg_campus_location_update` AFTER UPDATE ON `campus_locations` FOR EACH ROW BEGIN
    DECLARE admin_name VARCHAR(150);
    DECLARE changes TEXT DEFAULT '';
    SET admin_name = (SELECT CONCAT(first_name,' ',last_name) FROM administrator WHERE staff_id = @current_staff_id);

    IF OLD.unit != NEW.unit THEN
        SET changes = CONCAT(changes, 'Unit from "', OLD.unit,'" to "', NEW.unit,'"; ');
    END IF;
    IF OLD.building != NEW.building THEN
        SET changes = CONCAT(changes, 'Building from "', OLD.building,'" to "', NEW.building,'"; ');
    END IF;
    IF OLD.exact_location != NEW.exact_location THEN
        SET changes = CONCAT(changes, 'Exact Location from "', OLD.exact_location,'" to "', NEW.exact_location,'"; ');
    END IF;

    INSERT INTO activity_logs(source, name, action, description)
    VALUES(
		'Campus Location',
        admin_name,
        'UPDATE',
        CONCAT(admin_name, ' updated campus location: ', changes)
    );
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trg_campus_location_delete` AFTER DELETE ON `campus_locations` FOR EACH ROW BEGIN
    DECLARE admin_name VARCHAR(150);
    SET admin_name = (SELECT CONCAT(first_name,' ',last_name) FROM administrator WHERE staff_id = @current_staff_id);

    INSERT INTO activity_logs(source, name, action, description)
    VALUES(
		'Campus Location',
        admin_name,
        'DELETE',
        CONCAT(admin_name, ' deleted campus location: ', OLD.unit, ', ', OLD.building, ', ', OLD.exact_location)
    );
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `driver`
--

DROP TABLE IF EXISTS `driver`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `driver` (
  `driver_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `contact` varchar(11) DEFAULT NULL,
  `hire_date` date DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`driver_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10003 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `driver`
--

LOCK TABLES `driver` WRITE;
/*!40000 ALTER TABLE `driver` DISABLE KEYS */;
INSERT INTO `driver` VALUES (2023,'Bin','Ten','09123456783','2025-10-28',NULL),(10001,'Wet','Beer','09123456761','2022-06-21',NULL),(10002,'Black','Bench','09123456781','2025-09-30',NULL);
/*!40000 ALTER TABLE `driver` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trg_driver_insert` AFTER INSERT ON `driver` FOR EACH ROW BEGIN
    DECLARE admin_name VARCHAR(150);
    
    SET admin_name = (
        SELECT CONCAT(first_name, ' ', last_name)
        FROM administrator
        WHERE staff_id = @current_staff_id
    );

    
    INSERT INTO activity_logs(source, name, action, description)
    VALUES (
		'Driver',
        admin_name,
        'INSERT',
        CONCAT(admin_name, ' added driver: ', NEW.firstName, ' ', NEW.lastName)
    );
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trg_driver_update` AFTER UPDATE ON `driver` FOR EACH ROW BEGIN
    DECLARE admin_name VARCHAR(150);
    DECLARE changes TEXT DEFAULT '';
    SET admin_name = (SELECT CONCAT(first_name,' ',last_name) FROM administrator WHERE staff_id = @current_staff_id);

    IF OLD.firstName != NEW.firstName OR OLD.lastName != NEW.lastName THEN
        SET changes = CONCAT(changes, 'Name from "', OLD.firstName,' ',OLD.lastName,'" to "', NEW.firstName,' ',NEW.lastName,'"; ');
    END IF;
    IF OLD.contact != NEW.contact THEN
        SET changes = CONCAT(changes, 'Contact from "', OLD.contact,'" to "', NEW.contact,'"; ');
    END IF;
    IF OLD.hire_date != NEW.hire_date THEN
        SET changes = CONCAT(changes, 'Hire Date changed; ');
    END IF;

    INSERT INTO activity_logs(source, name, action, description)
    VALUES(
		'Driver',
        admin_name,
        'UPDATE',
        CONCAT(admin_name, ' updated driver: ', changes)
    );
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trg_driver_delete` AFTER DELETE ON `driver` FOR EACH ROW BEGIN
    DECLARE admin_name VARCHAR(150);
    SET admin_name = (SELECT CONCAT(first_name,' ',last_name) FROM administrator WHERE staff_id = @current_staff_id);

    INSERT INTO driver_audit(staff_name, action, description)
    VALUES(
        admin_name,
        'DELETE',
        CONCAT(admin_name, ' deleted driver: ', OLD.firstName, ' ', OLD.lastName)
    );
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `feedback`
--

DROP TABLE IF EXISTS `feedback`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tracking_id` varchar(100) NOT NULL,
  `ratings_A` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`ratings_A`)),
  `ratings_B` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`ratings_B`)),
  `ratings_C` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`ratings_C`)),
  `overall_rating` decimal(3,2) DEFAULT NULL,
  `suggest_process` text DEFAULT NULL,
  `suggest_frontline` text DEFAULT NULL,
  `suggest_facility` text DEFAULT NULL,
  `suggest_overall` text DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `feedback`
--

LOCK TABLES `feedback` WRITE;
/*!40000 ALTER TABLE `feedback` DISABLE KEYS */;
INSERT INTO `feedback` VALUES (1,'TRK-20251023-V6MEK','{\"0\":5,\"1\":4,\"2\":4,\"3\":5}','{\"0\":3,\"1\":4,\"2\":3,\"3\":4,\"4\":5,\"5\":4,\"6\":3}','{\"0\":5,\"1\":4,\"2\":3,\"3\":5}',4.07,'','','','Testing','2025-10-29 13:45:25'),(2,'TRK-VR20251028-73G56','{\"0\":2,\"1\":5,\"2\":3,\"3\":4}','{\"0\":5,\"1\":3,\"2\":4,\"3\":5,\"4\":3,\"5\":3,\"6\":5}','{\"0\":2,\"1\":5,\"2\":3,\"3\":3}',3.67,'Testing','Testing','Testing','Testing','2025-11-03 13:13:28'),(3,'TRK-20251106-MKV8J','{\"0\":5,\"1\":4,\"2\":3,\"3\":5}','{\"0\":5,\"1\":3,\"2\":4,\"3\":3,\"4\":4,\"5\":4,\"6\":3}','{\"0\":3,\"1\":5,\"2\":4,\"3\":4}',3.93,'Testing','','','','2025-11-06 08:34:24'),(4,'TRK-20251120-0BYMR','{\"0\":3,\"1\":3,\"2\":3,\"3\":3}','{\"0\":3,\"1\":3,\"2\":3,\"3\":3,\"4\":3,\"5\":3,\"6\":3}','{\"0\":3,\"1\":3,\"2\":3,\"3\":3}',3.00,'Yes','','','','2025-11-20 07:42:33');
/*!40000 ALTER TABLE `feedback` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gsu_personnel`
--

DROP TABLE IF EXISTS `gsu_personnel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gsu_personnel` (
  `staff_id` int(10) unsigned NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `department` varchar(50) NOT NULL,
  `contact` varchar(11) NOT NULL,
  `hire_date` date NOT NULL,
  `unit` varchar(100) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`staff_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gsu_personnel`
--

LOCK TABLES `gsu_personnel` WRITE;
/*!40000 ALTER TABLE `gsu_personnel` DISABLE KEYS */;
INSERT INTO `gsu_personnel` VALUES (10001,'Jay','Mentos','Utility','09183456789','2025-10-01','Mabini Unit',NULL),(10002,'White','Fear','Building Repair And Maintenance','09153456789','2022-05-18','Tagum Unit',NULL),(10003,'Blue','Finger','Landscaping','09123456789','2022-10-19','Tagum Unit',NULL);
/*!40000 ALTER TABLE `gsu_personnel` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trg_gsu_personnel_insert` AFTER INSERT ON `gsu_personnel` FOR EACH ROW BEGIN
    DECLARE admin_name VARCHAR(150);
    
    SET admin_name = (
        SELECT CONCAT(first_name, ' ', last_name)
        FROM administrator
        WHERE staff_id = @current_staff_id
    );

    
    INSERT INTO activity_logs(source, name, action, description)
    VALUES (
		'GSU Personnel',
        admin_name,
        'INSERT',
        CONCAT(
            'Added new GSU Personnel: ', NEW.firstName, ' ', NEW.lastName
        )
    );
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trg_gsu_personnel_update` AFTER UPDATE ON `gsu_personnel` FOR EACH ROW BEGIN
    DECLARE admin_name VARCHAR(150);
    DECLARE changes TEXT DEFAULT '';
    
    SET admin_name = (
        SELECT CONCAT(first_name, ' ', last_name)
        FROM administrator
        WHERE staff_id = @current_staff_id
    );
    
    IF OLD.firstName != NEW.firstName THEN
        SET changes = CONCAT(changes, 'First name changed from "', OLD.firstName, '" to "', NEW.firstName, '"; ');
    END IF;

    IF OLD.lastName != NEW.lastName THEN
        SET changes = CONCAT(changes, 'Last name changed from "', OLD.lastName, '" to "', NEW.lastName, '"; ');
    END IF;

    IF OLD.department != NEW.department THEN
        SET changes = CONCAT(changes, 'Department changed from "', OLD.department, '" to "', NEW.department, '"; ');
    END IF;

    IF OLD.contact != NEW.contact THEN
        SET changes = CONCAT(changes, 'Contact changed from "', OLD.contact, '" to "', NEW.contact, '"; ');
    END IF;

    IF OLD.hire_date != NEW.hire_date THEN
        SET changes = CONCAT(changes, 'Hire date changed from "', OLD.hire_date, '" to "', NEW.hire_date, '"; ');
    END IF;

    
    IF changes != '' THEN
        INSERT INTO activity_logs(source, name, action, description)
        VALUES (
			'GSU Personnel',
            admin_name,
            'UPDATE',
            changes
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `materials`
--

DROP TABLE IF EXISTS `materials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `materials` (
  `material_code` int(10) unsigned NOT NULL,
  `material_desc` varchar(50) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 0,
  `material_status` varchar(50) NOT NULL,
  PRIMARY KEY (`material_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `materials`
--

LOCK TABLES `materials` WRITE;
/*!40000 ALTER TABLE `materials` DISABLE KEYS */;
INSERT INTO `materials` VALUES (1,'Paint Green',16,'Available'),(2,'Electrical Tape',20,'Available'),(3,'Screw',50,'Available'),(4,'Paint Yellow Green',20,''),(5,'Laptop',10,'Available');
/*!40000 ALTER TABLE `materials` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trg_materials_insert` AFTER INSERT ON `materials` FOR EACH ROW BEGIN
    DECLARE admin_name VARCHAR(150);

    
    SET admin_name = (
        SELECT CONCAT(first_name, ' ', last_name)
        FROM administrator
        WHERE staff_id = @current_staff_id
    );

    INSERT INTO activity_logs(source, name, action, description)
    VALUES (
		'Materials',
        admin_name,
        'INSERT',
        CONCAT(
            'Added new material: ', NEW.material_desc,
            ', Code: ', NEW.material_code,
            ', Quantity: ', NEW.qty
        )
    );
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trg_materials_update` AFTER UPDATE ON `materials` FOR EACH ROW BEGIN
    DECLARE admin_name VARCHAR(150);
    DECLARE changes TEXT DEFAULT '';

    
    SET admin_name = (
        SELECT CONCAT(first_name, ' ', last_name)
        FROM administrator
        WHERE staff_id = @current_staff_id
    );

    
    IF OLD.material_desc != NEW.material_desc THEN
        SET changes = CONCAT(changes, 'Description changed from "', OLD.material_desc, '" to "', NEW.material_desc, '"; ');
    END IF;

    IF OLD.qty != NEW.qty THEN
        SET changes = CONCAT(changes, 'Quantity changed from ', OLD.qty, ' to ', NEW.qty, '; ');
    END IF;

    IF OLD.material_status != NEW.material_status THEN
        SET changes = CONCAT(changes, 'Status changed from "', OLD.material_status, '" to "', NEW.material_status, '"; ');
    END IF;

    
    IF changes != '' THEN
        INSERT INTO activity_logs(source, name, action, description)
        VALUES (
			'Materials',
            admin_name,
            'UPDATE',
            changes
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trg_materials_delete` AFTER DELETE ON `materials` FOR EACH ROW BEGIN
    DECLARE admin_name VARCHAR(150);

    
    SET admin_name = (
        SELECT CONCAT(first_name, ' ', last_name)
        FROM administrator
        WHERE staff_id = @current_staff_id
    );

    INSERT INTO activity_logs(source, name, action, description)
    VALUES (
		'Materials',
        admin_name,
        'DELETE',
        CONCAT(
            'Deleted material: ', OLD.material_desc,
            ', Code: ', OLD.material_code,
            ', Quantity: ', OLD.qty
        )
    );
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `passengers`
--

DROP TABLE IF EXISTS `passengers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `passengers` (
  `passenger_id` int(11) NOT NULL AUTO_INCREMENT,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  PRIMARY KEY (`passenger_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `passengers`
--

LOCK TABLES `passengers` WRITE;
/*!40000 ALTER TABLE `passengers` DISABLE KEYS */;
INSERT INTO `passengers` VALUES (1,'Riley','Reyes'),(2,'Shine','Reyes'),(3,'Gurly','Reyes'),(4,'Gurly','Gourl'),(5,'Gurly','Gay'),(6,'Jina','Gourl'),(7,'Shine','Gourl'),(8,'Kim','Luayon');
/*!40000 ALTER TABLE `passengers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `request`
--

DROP TABLE IF EXISTS `request`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `request` (
  `request_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tracking_id` varchar(100) DEFAULT NULL,
  `request_Type` varchar(70) DEFAULT NULL,
  `req_id` int(10) unsigned DEFAULT NULL,
  `request_desc` varchar(500) DEFAULT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `location` varchar(250) DEFAULT NULL,
  `request_date` date DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`request_id`),
  KEY `fk_request_requester` (`req_id`),
  KEY `idx_tracking_id` (`tracking_id`),
  CONSTRAINT `fk_request_requester` FOREIGN KEY (`req_id`) REFERENCES `requester` (`req_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `request`
--

LOCK TABLES `request` WRITE;
/*!40000 ALTER TABLE `request` DISABLE KEYS */;
INSERT INTO `request` VALUES (1,'TRK-20251023-V6MEK','Electrical',1,'Fire Outlet Po.','Tagum Unit','PECC - Physical Education Cultural Center - Clinic','2025-10-23','fire.jpg'),(2,'TRK-20251023-RK3UN','Electrical',1,'Fire Outlet','Tagum Unit','PECC - Physical Education Cultural Center - Office of Registrar (OUR)','2025-10-23','fire.jpg'),(3,'TRK-20251024-EA3M9','Others',1,'Testing File Upload 5mb Up','Tagum Unit','SOM - School of Medicine - Dean\'s Office','2025-10-24','IMG_20230426_201836.jpg'),(4,'TRK-20251106-MKV8J','Welding',1,'Testing','Tagum Unit','PECC - Physical Education Cultural Center - Clinic','2025-11-06','GULF.jpg'),(5,'TRK-20251112-QUODS','Plumbing',1,'Try Sa Co','Tagum Unit','Admin Building - Office of Chancellor','2025-11-12','1000008302.jpg'),(6,'TRK-20251112-K8Q2D','Carpentry/Masonry',6,'Testing','Tagum Unit','PECC - Physical Education Cultural Center - Office of Registrar (OUR)','2025-11-12','geogebra-export-4.png'),(7,'TRK-20251112-1V2DE','Landscaping',1,'Checking','Tagum Unit','SOM - School of Medicine - Dean\'s Office','2025-11-12','1000008087.jpg'),(70,'TRK-20251120-0BYMR','Electrical',7,'Socket Not Working','Tagum Unit','SOM - School of Medicine - SB-05','2025-11-20','truck.jpg'),(71,'TRK-20251121-7RYEG','Carpentry/Masonry',1,'Ewdwdwdwdwdw','Tagum Unit','Admin Building - Office of Chancellor','2025-11-21','ERD SE HATDOG (5).png'),(72,'TRK-20251121-QGE1P','Others',8,'Kjsi8yehhwhhjjijdbsijhsuaikwjaj@9#+$;2(@9#+@;@)29#(#;(@(*8#+1;;#7\"8#!!#8#8$+#;($($$+#(($8$8\"+(#+\"+$+$+$++$+$+$+$++++77$7$7$7$77#-#7#+$+2++$+(#+$!#($$+$;$+#;(*(#)#)$!$!$++\"+\"+--++++-------+7876--------+((++(92928!48_+3($($($!($!$8#!#8$!(#($!#8$!#8+\'+$8$!_(_!$(_!$($','Mabini Unit','Gym - Sports Office','2025-11-21','1000002058.jpg');
/*!40000 ALTER TABLE `request` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trg_request_insert` AFTER INSERT ON `request` FOR EACH ROW BEGIN
    DECLARE req_name VARCHAR(150);
    
    SET req_name = (
        SELECT CONCAT(firstName, ' ', lastName)
        FROM requester           
        WHERE req_id = @current_req_id
    );

    INSERT INTO activity_logs(source, name, action, description)
    VALUES (
		'Request',
        req_name,
        'INSERT',
        CONCAT(
            'New request created. Type: ', NEW.request_Type,
            ', Location: ', NEW.location
        )
    );
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `request_assigned_personnel`
--

DROP TABLE IF EXISTS `request_assigned_personnel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `request_assigned_personnel` (
  `assigned_id` int(11) NOT NULL AUTO_INCREMENT,
  `request_id` int(10) unsigned DEFAULT NULL,
  `staff_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`assigned_id`),
  KEY `fk_assigned_request` (`request_id`),
  KEY `fk_assigned_personnel` (`staff_id`),
  KEY `idx_staff_id` (`staff_id`),
  CONSTRAINT `fk_assigned_personnel` FOREIGN KEY (`staff_id`) REFERENCES `gsu_personnel` (`staff_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_assigned_request` FOREIGN KEY (`request_id`) REFERENCES `request` (`request_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `request_assigned_personnel`
--

LOCK TABLES `request_assigned_personnel` WRITE;
/*!40000 ALTER TABLE `request_assigned_personnel` DISABLE KEYS */;
INSERT INTO `request_assigned_personnel` VALUES (1,1,10001),(2,3,10001),(3,3,10002),(8,6,10003),(19,5,10003),(22,70,10001);
/*!40000 ALTER TABLE `request_assigned_personnel` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trg_request_assigned_personnel_insert` AFTER INSERT ON `request_assigned_personnel` FOR EACH ROW BEGIN
    DECLARE personnel_name VARCHAR(150) DEFAULT 'Unknown Personnel';
    DECLARE admin_name VARCHAR(150) DEFAULT 'System';
    DECLARE description_text TEXT;

    
    IF @current_staff_id IS NOT NULL THEN
        SELECT CONCAT(first_name, ' ', last_name)
        INTO admin_name
        FROM administrator
        WHERE staff_id = @current_staff_id
        LIMIT 1;
    END IF;

    
    SELECT CONCAT(firstName, ' ', lastName)
    INTO personnel_name
    FROM gsu_personnel
    WHERE staff_id = NEW.staff_id
    LIMIT 1;

    
    SET description_text = CONCAT('Assigned ', personnel_name, ' to request ID ', NEW.request_id);

    
    INSERT INTO activity_logs(source, name, action, description)
    VALUES ('Request Assigned Personnel', admin_name, 'INSERT', description_text);
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `request_assignment`
--

DROP TABLE IF EXISTS `request_assignment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `request_assignment` (
  `reqAssignment_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `request_id` int(10) unsigned DEFAULT NULL,
  `req_id` int(10) unsigned DEFAULT NULL,
  `req_status` varchar(25) DEFAULT NULL,
  `date_finished` date DEFAULT NULL,
  `priority_status` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`reqAssignment_id`),
  KEY `fk_assignment_request` (`request_id`),
  KEY `fk_assignment_requester` (`req_id`),
  CONSTRAINT `fk_assignment_request` FOREIGN KEY (`request_id`) REFERENCES `request` (`request_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_assignment_requester` FOREIGN KEY (`req_id`) REFERENCES `requester` (`req_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `request_assignment`
--

LOCK TABLES `request_assignment` WRITE;
/*!40000 ALTER TABLE `request_assignment` DISABLE KEYS */;
INSERT INTO `request_assignment` VALUES (1,1,1,'Completed','2025-10-29','High'),(2,2,1,'To Inspect',NULL,NULL),(3,3,1,'Completed','2025-11-15','Low'),(4,4,1,'Completed','2025-11-06','Low'),(5,5,1,'To Inspect',NULL,'High'),(6,6,6,'Completed','2025-11-16','Low'),(7,7,1,'To Inspect',NULL,NULL),(8,70,7,'Completed','2025-11-20','Low'),(9,71,1,'To Inspect',NULL,NULL),(10,72,8,'To Inspect',NULL,NULL);
/*!40000 ALTER TABLE `request_assignment` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trg_request_assignment_status_update` AFTER UPDATE ON `request_assignment` FOR EACH ROW BEGIN
    DECLARE v_admin_name VARCHAR(150) DEFAULT 'System';
    DECLARE v_tracking_id VARCHAR(100) DEFAULT 'N/A';
    DECLARE v_changes TEXT;

    
    IF @current_staff_id IS NOT NULL THEN
        SELECT CONCAT(first_name, ' ', last_name)
        INTO v_admin_name
        FROM administrator
        WHERE staff_id = @current_staff_id
        LIMIT 1;
    END IF;
    
    SELECT tracking_id
    INTO v_tracking_id
    FROM request
    WHERE request_id = NEW.request_id
    LIMIT 1;
    
    IF (OLD.req_status IS NULL AND NEW.req_status IS NOT NULL)
        OR (OLD.req_status IS NOT NULL AND NEW.req_status IS NULL)
        OR (OLD.req_status <> NEW.req_status) THEN

        
        IF NOT EXISTS (
            SELECT 1
            FROM activity_logs
            WHERE description LIKE CONCAT('%Tracking ID: ', v_tracking_id, '%New Status: "', NEW.req_status, '"%')
              AND TIMESTAMPDIFF(SECOND, changed_at, NOW()) < 3
        ) THEN
            SET v_changes = CONCAT(
                'Tracking ID: ', COALESCE(v_tracking_id, 'N/A'),
                ' | Previous Status: "', COALESCE(OLD.req_status, 'None'),
                '" | New Status: "', COALESCE(NEW.req_status, 'None'), '"'
            );
            INSERT INTO activity_logs(source, name, action, description)
            VALUES ('Request Status', v_admin_name, 'UPDATE', v_changes);
        END IF;
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `request_materials_needed`
--

DROP TABLE IF EXISTS `request_materials_needed`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `request_materials_needed` (
  `request_material_id` int(11) NOT NULL AUTO_INCREMENT,
  `reqAssignment_id` int(10) unsigned DEFAULT NULL,
  `material_code` int(10) unsigned DEFAULT NULL,
  `quantity_needed` int(11) DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`request_material_id`),
  KEY `fk_materials_needed_assignment` (`reqAssignment_id`),
  KEY `fk_materials_needed_material` (`material_code`),
  CONSTRAINT `fk_materials_needed_assignment` FOREIGN KEY (`reqAssignment_id`) REFERENCES `request_assignment` (`reqAssignment_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_materials_needed_material` FOREIGN KEY (`material_code`) REFERENCES `materials` (`material_code`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `request_materials_needed`
--

LOCK TABLES `request_materials_needed` WRITE;
/*!40000 ALTER TABLE `request_materials_needed` DISABLE KEYS */;
INSERT INTO `request_materials_needed` VALUES (1,1,2,1,'2025-10-23 17:23:55'),(2,3,1,3,'2025-11-11 13:28:01'),(3,8,1,1,'2025-11-20 07:30:47');
/*!40000 ALTER TABLE `request_materials_needed` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `requester`
--

DROP TABLE IF EXISTS `requester`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `requester` (
  `req_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `requester_id` varchar(50) DEFAULT NULL,
  `firstName` varchar(50) DEFAULT NULL,
  `lastName` varchar(50) DEFAULT NULL,
  `contact` varchar(11) DEFAULT NULL,
  `pass` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `officeOrDept` varchar(250) DEFAULT NULL,
  `profile_pic` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`req_id`),
  KEY `idx_requester_id` (`requester_id`),
  KEY `idx_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `requester`
--

LOCK TABLES `requester` WRITE;
/*!40000 ALTER TABLE `requester` DISABLE KEYS */;
INSERT INTO `requester` VALUES (1,'2023-00060','Jonalyn','Gujol','09383737386','c2d0YVhCSk8xdlRubUtHOFpRRlJra3VROUcyYUp3NG5saTJpelNSN24wTT0=','jsgujol00060@usep.edu.ph','BSABE','/uploads/profile_pics/backup_1763686854.png'),(2,'2023-000065','Layz','Zhang',NULL,'Y2Y4a1ZDKytnM2FOOVVveC83bUFTdz09','jia@usep.edu.ph','BSABE',NULL),(3,'2020-00011','Testing','One','09383737388','Y2Y4a1ZDKytnM2FOOVVveC83bUFTdz09','testing1@usep.edu.ph','BTVTED',NULL),(4,'2020-00014','Testing','Lang',NULL,'UTJxSmRFc01lMk9LYTFwMHJCSW50QT09','testing@usep.edu.ph',NULL,NULL),(5,'2000-0000','Bfell','Adobas',NULL,'ZG96WitESXhlcEVvQ0graGtjTCtTZz09','baadobas00470@usep.edu.ph','BSED',NULL),(6,'2023-00140','Kimmy','Canja',NULL,'UTJxSmRFc01lMk9LYTFwMHJCSW50QT09','kocanja00140@usep.edu.ph','BEED',NULL),(7,'2023-00313','Kim','Luayon','09164451958','bExLL2drTFZJRmNIZVYyeEdOcXF5QT09','kmluayon00313@usep.edu.ph',NULL,NULL),(8,'20000-28178','Naku','Po',NULL,'U3pIYjhVQXBGenNrTDV4UE1IWkpmZz09','emelang@usep.edu.ph',NULL,NULL);
/*!40000 ALTER TABLE `requester` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `source_of_fund`
--

DROP TABLE IF EXISTS `source_of_fund`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `source_of_fund` (
  `fund_id` int(11) NOT NULL AUTO_INCREMENT,
  `control_no` int(11) NOT NULL,
  `source_of_fuel` varchar(150) NOT NULL,
  `source_of_oil` varchar(150) NOT NULL,
  `source_of_repair_maintenance` varchar(150) NOT NULL,
  `source_of_driver_assistant_per_diem` varchar(150) NOT NULL,
  PRIMARY KEY (`fund_id`),
  KEY `control_no` (`control_no`),
  CONSTRAINT `source_of_fund_ibfk_1` FOREIGN KEY (`control_no`) REFERENCES `vehicle_request` (`control_no`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `source_of_fund`
--

LOCK TABLES `source_of_fund` WRITE;
/*!40000 ALTER TABLE `source_of_fund` DISABLE KEYS */;
INSERT INTO `source_of_fund` VALUES (4,22,'Donation','Collection','Own Money','Collection'),(5,23,'Donation','Collection','Own Money','Collection'),(6,24,'Donation','Collection','Own Money','Collection'),(11,48,'Donation','Collection','Own Money','Collection'),(12,49,'Donation','Collection','Own Money','Collection'),(13,50,'Donation','Collection','Own Money','Collection'),(14,51,'Donation','Collection','Own Money','Collection'),(15,52,'Donation','Collection','Own Money','Collection');
/*!40000 ALTER TABLE `source_of_fund` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vehicle`
--

DROP TABLE IF EXISTS `vehicle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vehicle` (
  `vehicle_id` int(11) NOT NULL AUTO_INCREMENT,
  `vehicle_name` varchar(100) NOT NULL,
  `plate_no` varchar(20) NOT NULL,
  `capacity` int(11) NOT NULL,
  `vehicle_type` varchar(50) NOT NULL,
  `driver_id` int(10) unsigned DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `status` varchar(25) NOT NULL DEFAULT 'Available',
  PRIMARY KEY (`vehicle_id`),
  KEY `driver_id` (`driver_id`),
  CONSTRAINT `vehicle_ibfk_1` FOREIGN KEY (`driver_id`) REFERENCES `driver` (`driver_id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehicle`
--

LOCK TABLES `vehicle` WRITE;
/*!40000 ALTER TABLE `vehicle` DISABLE KEYS */;
INSERT INTO `vehicle` VALUES (1,'USeP Bus','NVR321',65,'Bus',10001,'usepbus.jpg','Available'),(3,'USeP Van - (Hiace) Tagum Unit','UVN456',10,'Van',10002,'usepvan.jpg','Available'),(4,'USeP Van 2 - Tagum Unit','NVU456',10,'Van',2023,'van1.jpeg','Available'),(5,'USeP Vehicle - Tagum Unit','VHA991',7,'Sedan',10002,'hilux.jpg','Available');
/*!40000 ALTER TABLE `vehicle` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trg_vehicle_insert` AFTER INSERT ON `vehicle` FOR EACH ROW BEGIN
    DECLARE admin_name VARCHAR(150);

    SET admin_name = (
        SELECT CONCAT(first_name, ' ', last_name)
        FROM administrator
        WHERE staff_id = @current_staff_id
    );

    INSERT INTO activity_logs(source, name, action, description)
    VALUES (
		'Vehicle',
        admin_name,
        'INSERT',
        CONCAT(admin_name, ' added vehicle: ', NEW.vehicle_name, 
               ' (Plate: ', NEW.plate_no, ', Type: ', NEW.vehicle_type, ')')
    );
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trg_vehicle_update` AFTER UPDATE ON `vehicle` FOR EACH ROW BEGIN
    DECLARE admin_name VARCHAR(150);
    DECLARE changes TEXT DEFAULT '';

    SET admin_name = (
        SELECT CONCAT(first_name, ' ', last_name)
        FROM administrator
        WHERE staff_id = @current_staff_id
    );

    
    IF OLD.vehicle_name != NEW.vehicle_name THEN
        SET changes = CONCAT(changes, 'Name from "', OLD.vehicle_name, '" to "', NEW.vehicle_name, '"; ');
    END IF;

    IF OLD.plate_no != NEW.plate_no THEN
        SET changes = CONCAT(changes, 'Plate No from "', OLD.plate_no, '" to "', NEW.plate_no, '"; ');
    END IF;

    IF OLD.capacity != NEW.capacity THEN
        SET changes = CONCAT(changes, 'Capacity from "', OLD.capacity, '" to "', NEW.capacity, '"; ');
    END IF;

    IF OLD.vehicle_type != NEW.vehicle_type THEN
        SET changes = CONCAT(changes, 'Type from "', OLD.vehicle_type, '" to "', NEW.vehicle_type, '"; ');
    END IF;

    IF OLD.driver_id != NEW.driver_id THEN
        SET changes = CONCAT(changes, 'Driver changed; ');
    END IF;

    IF OLD.photo != NEW.photo THEN
        SET changes = CONCAT(changes, 'Photo updated; ');
    END IF;

    IF OLD.status != NEW.status THEN
        SET changes = CONCAT(changes, 'Status from "', OLD.status, '" to "', NEW.status, '"; ');
    END IF;

    INSERT INTO activity_logs(source, name, action, description)
    VALUES (
		'Vehicle',
        admin_name,
        'UPDATE',
        CONCAT(admin_name, ' updated vehicle: ', NEW.vehicle_name, '. Changes: ', changes)
    );
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trg_vehicle_delete` AFTER DELETE ON `vehicle` FOR EACH ROW BEGIN
    DECLARE admin_name VARCHAR(150);

    SET admin_name = (
        SELECT CONCAT(first_name, ' ', last_name)
        FROM administrator
        WHERE staff_id = @current_staff_id
    );

    INSERT INTO vehicle_audit (staff_name, action, description)
    VALUES (
        admin_name,
        'DELETE',
        CONCAT(admin_name, ' deleted vehicle: ', OLD.vehicle_name, 
               ' (Plate: ', OLD.plate_no, ', Type: ', OLD.vehicle_type, ')')
    );
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `vehicle_request`
--

DROP TABLE IF EXISTS `vehicle_request`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vehicle_request` (
  `control_no` int(11) NOT NULL AUTO_INCREMENT,
  `req_id` int(10) unsigned DEFAULT NULL,
  `date_request` datetime DEFAULT NULL,
  `tracking_id` varchar(50) DEFAULT NULL,
  `trip_purpose` varchar(100) DEFAULT NULL,
  `travel_destination` varchar(255) DEFAULT NULL,
  `travel_date` date DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `departure_time` time DEFAULT NULL,
  `return_time` time DEFAULT NULL,
  PRIMARY KEY (`control_no`),
  KEY `fk_vehicle_request_requester` (`req_id`),
  KEY `idx_tracking_id` (`tracking_id`),
  CONSTRAINT `fk_vehicle_request_requester` FOREIGN KEY (`req_id`) REFERENCES `requester` (`req_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehicle_request`
--

LOCK TABLES `vehicle_request` WRITE;
/*!40000 ALTER TABLE `vehicle_request` DISABLE KEYS */;
INSERT INTO `vehicle_request` VALUES (22,2,'2025-10-27 21:42:36','TRK-VR20251027-2OXED','Field Trip To Mabini Unit','Tagum Unit - Mabini Unit','2025-11-07','2025-11-07','05:00:00','17:00:00'),(23,2,'2025-10-28 16:26:18','TRK-VR20251028-73G56','Field Trip To Obrero Campus','Tagum Unit - Obrero Campus','2025-11-14','2025-11-14','05:00:00','17:00:00'),(24,1,'2025-10-28 16:51:46','TRK-VR20251028-4FG3X','Field Trip To Mintal Campus','Tagum Unit - Mintal Campus','2025-11-25','2025-11-25','05:00:00','17:00:00'),(48,1,'2025-11-04 22:37:32','TRK-VR20251104-EOHAV','Field Trip To Obrero Campus','Tagum Unit - Obrero Campus','2025-11-17','2025-11-17','05:00:00','17:00:00'),(49,3,'2025-11-14 15:05:01','TRK-VR20251114-J0OUZ','Field Trip To Obrero Campus','Tagum Unit - Obrero Campus','2025-11-20','2025-11-20','05:00:00','17:00:00'),(50,3,'2025-11-14 15:10:00','TRK-VR20251114-VZP34','Field Trip To Obrero Campus','Tagum Unit - Obrero Campus','2025-11-20','2025-11-20','05:00:00','17:00:00'),(51,1,'2025-11-18 18:30:33','TRK-VR20251118-PVZ1H','Field Trip To Obrero Campus','Tagum Unit - Obrero Campus','2025-11-21','2025-11-21','06:30:00','18:00:00'),(52,3,'2025-11-18 18:42:46','TRK-VR20251118-EQ0US','Field Trip To Obrero Campus','Tagum Unit - Obrero Campus','2025-11-21','2025-11-21','06:00:00','18:00:00');
/*!40000 ALTER TABLE `vehicle_request` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trg_vehicle_request_insert` AFTER INSERT ON `vehicle_request` FOR EACH ROW BEGIN
    DECLARE requester_name VARCHAR(150);

    
    SET requester_name = (
        SELECT CONCAT(firstName, ' ', lastName)
        FROM requester
        WHERE req_id = @current_req_id
    );

    INSERT INTO activity_logs(source, name, action, description)
    VALUES (
		'Vehicle Request',
        requester_name,
        'INSERT',
        CONCAT(requester_name, ' created a new vehicle request with tracking ID ', NEW.tracking_id)
    );
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `vehicle_request_assignment`
--

DROP TABLE IF EXISTS `vehicle_request_assignment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vehicle_request_assignment` (
  `reqAssignment_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `control_no` int(11) NOT NULL,
  `req_id` int(10) unsigned DEFAULT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `driver_id` int(10) unsigned DEFAULT NULL,
  `req_status` varchar(25) NOT NULL,
  `approved_by` varchar(100) DEFAULT NULL,
  `reason` text DEFAULT NULL,
  PRIMARY KEY (`reqAssignment_id`),
  KEY `fk_vra_vehicle_request` (`control_no`),
  KEY `fk_vra_requester` (`req_id`),
  KEY `fk_vra_vehicle` (`vehicle_id`),
  KEY `fk_vra_driver` (`driver_id`),
  CONSTRAINT `fk_vra_driver` FOREIGN KEY (`driver_id`) REFERENCES `driver` (`driver_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_vra_requester` FOREIGN KEY (`req_id`) REFERENCES `requester` (`req_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_vra_vehicle` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicle` (`vehicle_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_vra_vehicle_request` FOREIGN KEY (`control_no`) REFERENCES `vehicle_request` (`control_no`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehicle_request_assignment`
--

LOCK TABLES `vehicle_request_assignment` WRITE;
/*!40000 ALTER TABLE `vehicle_request_assignment` DISABLE KEYS */;
INSERT INTO `vehicle_request_assignment` VALUES (2,22,2,5,10002,'Completed','Dr. Shirley Villanueva','da49e97c9e42f7c7d12e8461a302a27c'),(3,23,2,3,10002,'Completed','Dr. Shirley Villanueva',NULL),(4,24,1,1,10001,'Approved','Dr. Shirley Villanueva',NULL),(25,48,1,1,10001,'Completed','Dr. Shirley Villanueva',NULL),(26,49,3,NULL,NULL,'Rejected',NULL,'Conflict schedule of other trip.'),(27,50,3,1,10001,'Completed','Dr. Shirley Villanueva',NULL),(28,51,1,4,2023,'On Going','Dr. Shirley Villanueva',NULL),(29,52,3,NULL,NULL,'Cancelled',NULL,'Reason');
/*!40000 ALTER TABLE `vehicle_request_assignment` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER trg_vehicle_request_assignment_update
AFTER UPDATE ON vehicle_request_assignment
FOR EACH ROW
BEGIN
    DECLARE actor_name VARCHAR(150);
    DECLARE vehicle_old VARCHAR(100);
    DECLARE vehicle_new VARCHAR(100);
    DECLARE driver_old VARCHAR(150);
    DECLARE driver_new VARCHAR(150);
    DECLARE changes TEXT DEFAULT '';

    
    IF @current_staff_id IS NOT NULL THEN
        SET actor_name = (
            SELECT CONCAT(first_name, ' ', last_name)
            FROM administrator
            WHERE staff_id = @current_staff_id
        );
    ELSEIF @current_req_id IS NOT NULL THEN
        SET actor_name = (
            SELECT CONCAT(firstName, ' ', lastName)
            FROM requester
            WHERE req_id = @current_req_id
        );
    ELSE
        SET actor_name = 'Unknown';
    END IF;

    
    SET vehicle_old = (SELECT vehicle_name FROM vehicle WHERE vehicle_id = OLD.vehicle_id);
    SET vehicle_new = (SELECT vehicle_name FROM vehicle WHERE vehicle_id = NEW.vehicle_id);

    
    SET driver_old = (SELECT CONCAT(firstName, ' ', lastName) FROM driver WHERE driver_id = OLD.driver_id);
    SET driver_new = (SELECT CONCAT(firstName, ' ', lastName) FROM driver WHERE driver_id = NEW.driver_id);

    
    IF OLD.vehicle_id != NEW.vehicle_id THEN
        SET changes = CONCAT(changes, 'Vehicle changed from ', IFNULL(vehicle_old,'N/A'), ' to ', IFNULL(vehicle_new,'N/A'), '; ');
    END IF;

    IF OLD.driver_id != NEW.driver_id THEN
        SET changes = CONCAT(changes, 'Driver changed from ', IFNULL(driver_old,'N/A'), ' to ', IFNULL(driver_new,'N/A'), '; ');
    END IF;

    IF OLD.req_status != NEW.req_status THEN
        SET changes = CONCAT(changes, 'Status changed from "', OLD.req_status, '" to "', NEW.req_status, '"; ');
    END IF;

    IF OLD.approved_by != NEW.approved_by THEN
        SET changes = CONCAT(changes, 'Approved by changed from "', OLD.approved_by, '" to "', NEW.approved_by, '"; ');
    END IF;

    
    IF changes != '' THEN
        INSERT INTO activity_logs(source, name, action, description)
        VALUES (
            'Vehicle Request Assignment',
            actor_name,
            'UPDATE',
            changes
        );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `vehicle_request_passengers`
--

DROP TABLE IF EXISTS `vehicle_request_passengers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vehicle_request_passengers` (
  `control_no` int(11) NOT NULL,
  `passenger_id` int(11) NOT NULL,
  PRIMARY KEY (`control_no`,`passenger_id`),
  KEY `fk_vehicle_request_passengers_personnel` (`passenger_id`),
  CONSTRAINT `fk_vehicle_request_passengers_personnel` FOREIGN KEY (`passenger_id`) REFERENCES `passengers` (`passenger_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_vehicle_request_passengers_request` FOREIGN KEY (`control_no`) REFERENCES `vehicle_request` (`control_no`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehicle_request_passengers`
--

LOCK TABLES `vehicle_request_passengers` WRITE;
/*!40000 ALTER TABLE `vehicle_request_passengers` DISABLE KEYS */;
INSERT INTO `vehicle_request_passengers` VALUES (22,3),(22,4),(23,5),(24,4),(48,3),(49,1),(49,3),(50,3),(51,2),(51,6),(52,2),(52,7);
/*!40000 ALTER TABLE `vehicle_request_passengers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `vw_administrator`
--

DROP TABLE IF EXISTS `vw_administrator`;
/*!50001 DROP VIEW IF EXISTS `vw_administrator`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vw_administrator` AS SELECT
 1 AS `staff_id`,
  1 AS `email`,
  1 AS `full_name`,
  1 AS `first_name`,
  1 AS `last_name`,
  1 AS `contact_no`,
  1 AS `profile_picture`,
  1 AS `accessLevel_desc`,
  1 AS `accessLevel_id`,
  1 AS `status` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vw_feedback`
--

DROP TABLE IF EXISTS `vw_feedback`;
/*!50001 DROP VIEW IF EXISTS `vw_feedback`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vw_feedback` AS SELECT
 1 AS `tracking_id`,
  1 AS `ratings_A`,
  1 AS `ratings_B`,
  1 AS `ratings_C`,
  1 AS `overall_rating`,
  1 AS `suggest_process`,
  1 AS `suggest_frontline`,
  1 AS `suggest_facility`,
  1 AS `suggest_overall`,
  1 AS `submitted_at` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vw_gsu_personnel`
--

DROP TABLE IF EXISTS `vw_gsu_personnel`;
/*!50001 DROP VIEW IF EXISTS `vw_gsu_personnel`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vw_gsu_personnel` AS SELECT
 1 AS `staff_id`,
  1 AS `full_name`,
  1 AS `department`,
  1 AS `contact`,
  1 AS `hire_date`,
  1 AS `unit` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vw_materials`
--

DROP TABLE IF EXISTS `vw_materials`;
/*!50001 DROP VIEW IF EXISTS `vw_materials`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vw_materials` AS SELECT
 1 AS `material_code`,
  1 AS `material_desc`,
  1 AS `qty`,
  1 AS `material_status` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vw_requesters`
--

DROP TABLE IF EXISTS `vw_requesters`;
/*!50001 DROP VIEW IF EXISTS `vw_requesters`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vw_requesters` AS SELECT
 1 AS `requester_id`,
  1 AS `firstName`,
  1 AS `lastName`,
  1 AS `contact`,
  1 AS `email`,
  1 AS `officeOrDept`,
  1 AS `profile_pic` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vw_requests`
--

DROP TABLE IF EXISTS `vw_requests`;
/*!50001 DROP VIEW IF EXISTS `vw_requests`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vw_requests` AS SELECT
 1 AS `request_id`,
  1 AS `Name`,
  1 AS `request_Type`,
  1 AS `location`,
  1 AS `request_date`,
  1 AS `req_status` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vw_rqtrack`
--

DROP TABLE IF EXISTS `vw_rqtrack`;
/*!50001 DROP VIEW IF EXISTS `vw_rqtrack`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vw_rqtrack` AS SELECT
 1 AS `tracking_id`,
  1 AS `request_Type`,
  1 AS `request_desc`,
  1 AS `location`,
  1 AS `req_status`,
  1 AS `date_finished`,
  1 AS `req_id`,
  1 AS `request_date` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vw_work_history`
--

DROP TABLE IF EXISTS `vw_work_history`;
/*!50001 DROP VIEW IF EXISTS `vw_work_history`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vw_work_history` AS SELECT
 1 AS `staff_id`,
  1 AS `request_id`,
  1 AS `request_Type`,
  1 AS `date_finished`,
  1 AS `req_status` */;
SET character_set_client = @saved_cs_client;

--
-- Dumping events for database 'u_request'
--

--
-- Dumping routines for database 'u_request'
--
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
/*!50003 DROP FUNCTION IF EXISTS `fnCheckEmailAndPass` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` FUNCTION `fnCheckEmailAndPass`(`p_email` VARCHAR(100), `p_pass` VARCHAR(255)) RETURNS tinyint(1)
    READS SQL DATA
    DETERMINISTIC
BEGIN
    DECLARE exists_acc TINYINT(1) DEFAULT 0;

    
    SELECT 1 INTO exists_acc
    FROM requester
    WHERE email = p_email AND pass = p_pass
    LIMIT 1;

    RETURN exists_acc;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
/*!50003 DROP FUNCTION IF EXISTS `fnGetRequesterContact` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` FUNCTION `fnGetRequesterContact`(`p_req_id` INT) RETURNS varchar(11) CHARSET utf8mb4 COLLATE utf8mb4_general_ci
    READS SQL DATA
    DETERMINISTIC
BEGIN
    DECLARE v_contact VARCHAR(11);
    SELECT contact
    INTO v_contact
    FROM requester
    WHERE req_id = p_req_id
    LIMIT 1;
    RETURN v_contact;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
/*!50003 DROP FUNCTION IF EXISTS `fnGetRequesterIdByEmail` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` FUNCTION `fnGetRequesterIdByEmail`(`p_email` VARCHAR(100)) RETURNS int(11)
    READS SQL DATA
    DETERMINISTIC
BEGIN
    DECLARE requesterId INT;

    
    SELECT req_id 
    INTO requesterId
    FROM requester
    WHERE email = p_email
    LIMIT 1;

    RETURN requesterId;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `spAddAdministrator` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `spAddAdministrator`(IN `p_staff_id` VARCHAR(100), IN `p_email` VARCHAR(150), IN `p_first_name` VARCHAR(50), IN `p_last_name` VARCHAR(50), IN `p_contact_no` VARCHAR(11), IN `p_accessLevel_id` INT, IN `p_password` VARCHAR(255), IN `p_profile_picture` VARCHAR(255))
BEGIN
    INSERT INTO administrator (
        staff_id, email, first_name, last_name, contact_no,
        accessLevel_id, password, profile_picture
    ) VALUES (
        p_staff_id, p_email, p_first_name, p_last_name, p_contact_no,
        p_accessLevel_id, p_password, p_profile_picture
    );
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `spAddDriver` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `spAddDriver`(IN `p_firstName` VARCHAR(50), IN `p_lastName` VARCHAR(50), IN `p_contact` VARCHAR(11), IN `p_hire_date` DATE, IN `p_profile_picture` VARCHAR(255))
BEGIN
    INSERT INTO driver (firstName, lastName, contact, hire_date, profile_picture)
    VALUES (p_firstName, p_lastName, p_contact, p_hire_date, p_profile_picture);
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `spAddGsuPersonnel` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `spAddGsuPersonnel`(IN `p_staff_id` INT, IN `p_first_name` VARCHAR(50), IN `p_last_name` VARCHAR(50), IN `p_department` VARCHAR(50), IN `p_contact` VARCHAR(11), IN `p_hire_date` DATE, IN `p_unit` VARCHAR(100), IN `p_profile_picture` VARCHAR(255))
BEGIN
    INSERT INTO gsu_personnel (
        staff_id,
        firstName,
        lastName,
        department,
        contact,
        hire_date,
        unit,
        profile_picture
    )
    VALUES (
        p_staff_id,
        p_first_name,
        p_last_name,
        p_department,
        p_contact,
        p_hire_date,
        p_unit,
        p_profile_picture
    );
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `spAddMaterial` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `spAddMaterial`(IN `p_material_code` INT UNSIGNED, IN `p_material_desc` VARCHAR(50), IN `p_qty` INT, IN `p_material_status` VARCHAR(50))
BEGIN
    INSERT INTO materials (
        material_code, material_desc, qty, material_status
    ) VALUES (
        p_material_code, p_material_desc, p_qty, p_material_status
    );
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `spAddPassenger` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `spAddPassenger`(IN `p_firstName` VARCHAR(50), IN `p_lastName` VARCHAR(50))
BEGIN
    INSERT INTO passengers (
        firstName, lastName
    ) VALUES (
        p_firstName, p_lastName
    );
    
    SELECT LAST_INSERT_ID() AS passenger_id;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `spAddRequest` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `spAddRequest`(IN `p_tracking_id` VARCHAR(100), IN `p_request_Type` VARCHAR(70), IN `p_req_id` INT UNSIGNED, IN `p_request_desc` VARCHAR(500), IN `p_unit` VARCHAR(50), IN `p_location` VARCHAR(250), IN `p_request_date` DATE, IN `p_image_path` VARCHAR(255))
BEGIN
    INSERT INTO request (
        tracking_id,
        request_Type,
        req_id,
        request_desc,
        unit,
        location,
        request_date,
        image_path
    ) VALUES (
        p_tracking_id,
        p_request_Type,
        p_req_id,
        p_request_desc,
        p_unit,
        p_location,
        p_request_date,
        p_image_path
    );

    
    SELECT LAST_INSERT_ID() AS request_id;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `spAddRequestAssignment` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `spAddRequestAssignment`(IN `p_request_id` INT UNSIGNED, IN `p_req_id` INT UNSIGNED, IN `p_req_status` VARCHAR(25), IN `p_date_finished` DATE)
BEGIN
    INSERT INTO request_assignment (
        request_id,
        req_id,
        req_status,
        date_finished
    ) VALUES (
        p_request_id,
        p_req_id,
        p_req_status,
        p_date_finished
    );
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `spAddRequester` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `spAddRequester`(IN `p_requester_id` VARCHAR(50), IN `p_firstName` VARCHAR(50), IN `p_lastName` VARCHAR(50), IN `p_pass` VARCHAR(50), IN `p_email` VARCHAR(100))
BEGIN
    INSERT INTO requester (
        requester_id,
        firstName,
        lastName,
        pass,
        email
    ) VALUES (
        p_requester_id,
        p_firstName,
        p_lastName,
        p_pass,
        p_email
    );
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `spAddSourceOfFund` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `spAddSourceOfFund`(IN `p_control_no` INT, IN `p_source_of_fuel` VARCHAR(150), IN `p_source_of_oil` VARCHAR(150), IN `p_source_of_repair_maintenance` VARCHAR(150), IN `p_source_of_driver_assistant_per_diem` VARCHAR(150))
BEGIN
    INSERT INTO source_of_fund (
        control_no,
        source_of_fuel,
        source_of_oil,
        source_of_repair_maintenance,
        source_of_driver_assistant_per_diem
    ) VALUES (
        p_control_no,
        p_source_of_fuel,
        p_source_of_oil,
        p_source_of_repair_maintenance,
        p_source_of_driver_assistant_per_diem
    );
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `spAddVehicleRequest` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `spAddVehicleRequest`(IN `p_req_id` INT, IN `p_tracking_id` VARCHAR(50), IN `p_trip_purpose` VARCHAR(100), IN `p_travel_destination` VARCHAR(255), IN `p_travel_date` DATE, IN `p_return_date` DATE, IN `p_departure_time` TIME, IN `p_return_time` TIME)
BEGIN
    DECLARE new_control_no INT;

    INSERT INTO vehicle_request (
        req_id,
        date_request,
        tracking_id,
        trip_purpose,
        travel_destination,
        travel_date,
        return_date,
        departure_time,
        return_time
    )
    VALUES (
        p_req_id,
        NOW(),
        p_tracking_id,
        p_trip_purpose,
        p_travel_destination,
        p_travel_date,
        p_return_date,
        p_departure_time,
        p_return_time
    );

    SET new_control_no = LAST_INSERT_ID();

    SELECT new_control_no AS control_no;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `spAddVehicleRequestAssignment` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `spAddVehicleRequestAssignment`(IN `p_control_no` INT, IN `p_req_id` INT, IN `p_vehicle_id` INT, IN `p_driver_id` INT, IN `p_req_status` VARCHAR(25), IN `p_approved_by` VARCHAR(100))
BEGIN
    INSERT INTO vehicle_request_assignment (
        control_no, req_id, vehicle_id, driver_id, req_status, approved_by
    )
    VALUES (
        p_control_no, p_req_id, p_vehicle_id, p_driver_id, p_req_status, p_approved_by
    );
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `spAssignPersonnel` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `spAssignPersonnel`(IN `p_request_id` INT, IN `p_staff_id` INT)
BEGIN
    INSERT INTO request_assigned_personnel (
        request_id,
        staff_id
    ) VALUES (
        p_request_id,
        p_staff_id
    );
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `spCheckCurrentPassword` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `spCheckCurrentPassword`(IN `userEmail` VARCHAR(100), IN `inputPassword` VARCHAR(255), OUT `isValid` BOOLEAN)
BEGIN
    DECLARE dbPassword VARCHAR(255);

    
    SELECT pass 
    INTO dbPassword
    FROM requester
    WHERE email = userEmail;

    
    IF dbPassword IS NOT NULL AND dbPassword = inputPassword THEN
        SET isValid = TRUE;
    ELSE
        SET isValid = FALSE;
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `spDeleteGsuPersonnel` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `spDeleteGsuPersonnel`(IN `p_staff_id` INT)
BEGIN
    DELETE FROM gsu_personnel
    WHERE staff_id = p_staff_id;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `spGetAdminByEmail` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `spGetAdminByEmail`(IN `input_email` VARCHAR(150))
BEGIN
    DECLARE account_status VARCHAR(20);

    
    SELECT status INTO account_status
    FROM administrator
    WHERE email = input_email
    LIMIT 1;

    IF account_status IS NULL THEN
        
        SELECT NULL AS staff_id,
               NULL AS email,
               NULL AS password,
               NULL AS first_name,
               NULL AS last_name,
               NULL AS accessLevel_id,
               NULL AS profile_picture,
               'Email not found.' AS error_message,
               'error' AS result,
               NULL AS status;
    ELSEIF account_status = 'Inactive' THEN
        
        SELECT NULL AS staff_id,
               NULL AS email,
               NULL AS password,
               NULL AS first_name,
               NULL AS last_name,
               NULL AS accessLevel_id,
               NULL AS profile_picture,
               'This account is deactivated and cannot log in.' AS error_message,
               'error' AS result,
               account_status AS status;
    ELSE
        
        SELECT 
            staff_id,
            email,
            password,
            first_name,
            last_name,
            accessLevel_id,
            profile_picture,
            status,
            NULL AS error_message,
            'success' AS result
        FROM administrator
        WHERE email = input_email;
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `spUpdateAssignPersonnel` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `spUpdateAssignPersonnel`(IN `p_request_id` INT, IN `p_staff_id` INT)
BEGIN
    
    UPDATE request_assigned_personnel
    SET staff_id = p_staff_id
    WHERE request_id = p_request_id;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `spUpdateDriver` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `spUpdateDriver`(IN `p_driver_id` INT(10), IN `p_firstName` VARCHAR(50), IN `p_lastName` VARCHAR(50), IN `p_contact` VARCHAR(11), IN `p_hire_date` DATE, IN `p_profile_picture` VARCHAR(255))
BEGIN
    UPDATE driver
    SET 
        firstName = p_firstName,
        lastName = p_lastName,
        contact = p_contact,
        hire_date = p_hire_date,
        profile_picture = p_profile_picture
    WHERE driver_id = p_driver_id;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `spUpdateGsuPersonnel` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `spUpdateGsuPersonnel`(IN `p_staff_id` INT, IN `p_first_name` VARCHAR(50), IN `p_last_name` VARCHAR(50), IN `p_department` VARCHAR(50), IN `p_contact` VARCHAR(11), IN `p_hire_date` DATE, IN `p_unit` VARCHAR(100), IN `p_profile_picture` VARCHAR(255))
BEGIN
    
    IF EXISTS (
        SELECT 1 
        FROM gsu_personnel 
        WHERE contact = p_contact 
        AND staff_id <> p_staff_id
    ) THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Contact number already exists!';
    ELSE
        
        UPDATE gsu_personnel
        SET 
            firstName       = p_first_name,
            lastName        = p_last_name,
            department      = p_department,
            contact         = p_contact,
            hire_date       = p_hire_date,
            unit            = p_unit,
            profile_picture = p_profile_picture
        WHERE staff_id = p_staff_id;
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `spUpdateRequestPriorityStatus` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `spUpdateRequestPriorityStatus`(IN `p_request_id` INT, IN `p_priority` VARCHAR(50))
BEGIN
    
    UPDATE request_assignment
    SET priority_status = p_priority
    WHERE request_id = p_request_id;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `spUpdateRequestStatus` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `spUpdateRequestStatus`(IN `p_request_id` INT, IN `p_status` VARCHAR(25))
BEGIN
    
    IF p_status = 'Completed' THEN
        UPDATE request_assignment
        SET req_status = p_status,
            date_finished = NOW()
        WHERE request_id = p_request_id;
    ELSE
        UPDATE request_assignment
        SET req_status = p_status,
            date_finished = NULL
        WHERE request_id = p_request_id;
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `spUpdateVehicleRequestAssignment` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `spUpdateVehicleRequestAssignment`(IN `p_reqAssignment_id` INT, IN `p_vehicle_id` INT(11), IN `P_driver_id` INT(10), IN `p_req_status` VARCHAR(25), IN `p_approved_by` VARCHAR(100))
BEGIN
    UPDATE vehicle_request_assignment
    SET 
		vehicle_id = p_vehicle_id,
        driver_id = P_driver_id,
        req_status = p_req_status,
        approved_by = p_approved_by
    WHERE 
        reqAssignment_id = p_reqAssignment_id;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `spUpdateVehicleRequestAssignmentVD` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `spUpdateVehicleRequestAssignmentVD`(IN `p_reqAssignment_id` INT, IN `p_vehicle_id` INT(11), IN `P_driver_id` INT(10))
BEGIN
    UPDATE vehicle_request_assignment
    SET 
        vehicle_id = p_vehicle_id,
        driver_id = P_driver_id
    WHERE 
        reqAssignment_id = p_reqAssignment_id;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `spVehicleRequestPassengers` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `spVehicleRequestPassengers`(IN `p_control_no` INT, IN `p_passenger_id` INT)
BEGIN
    
    INSERT INTO vehicle_request_passengers (
        control_no,
        passenger_id
    ) VALUES (
        p_control_no,
        p_passenger_id
    );
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Current Database: `u_request`
--

USE `u_request`;

--
-- Final view structure for view `vw_administrator`
--

/*!50001 DROP VIEW IF EXISTS `vw_administrator`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_administrator` AS select `a`.`staff_id` AS `staff_id`,`a`.`email` AS `email`,concat(`a`.`first_name`,' ',`a`.`last_name`) AS `full_name`,`a`.`first_name` AS `first_name`,`a`.`last_name` AS `last_name`,`a`.`contact_no` AS `contact_no`,`a`.`profile_picture` AS `profile_picture`,`acl`.`accessLevel_desc` AS `accessLevel_desc`,`a`.`accessLevel_id` AS `accessLevel_id`,`a`.`status` AS `status` from (`administrator` `a` join `admin_access_level` `acl` on(`acl`.`accessLevel_id` = `a`.`accessLevel_id`)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_feedback`
--

/*!50001 DROP VIEW IF EXISTS `vw_feedback`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_feedback` AS select `feedback`.`tracking_id` AS `tracking_id`,`feedback`.`ratings_A` AS `ratings_A`,`feedback`.`ratings_B` AS `ratings_B`,`feedback`.`ratings_C` AS `ratings_C`,`feedback`.`overall_rating` AS `overall_rating`,`feedback`.`suggest_process` AS `suggest_process`,`feedback`.`suggest_frontline` AS `suggest_frontline`,`feedback`.`suggest_facility` AS `suggest_facility`,`feedback`.`suggest_overall` AS `suggest_overall`,`feedback`.`submitted_at` AS `submitted_at` from `feedback` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_gsu_personnel`
--

/*!50001 DROP VIEW IF EXISTS `vw_gsu_personnel`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_gsu_personnel` AS select `gp`.`staff_id` AS `staff_id`,concat(`gp`.`firstName`,' ',`gp`.`lastName`) AS `full_name`,`gp`.`department` AS `department`,`gp`.`contact` AS `contact`,`gp`.`hire_date` AS `hire_date`,`gp`.`unit` AS `unit` from `gsu_personnel` `gp` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_materials`
--

/*!50001 DROP VIEW IF EXISTS `vw_materials`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_materials` AS select `m`.`material_code` AS `material_code`,`m`.`material_desc` AS `material_desc`,`m`.`qty` AS `qty`,`m`.`material_status` AS `material_status` from `materials` `m` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_requesters`
--

/*!50001 DROP VIEW IF EXISTS `vw_requesters`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_requesters` AS select `r`.`requester_id` AS `requester_id`,`r`.`firstName` AS `firstName`,`r`.`lastName` AS `lastName`,`r`.`contact` AS `contact`,`r`.`email` AS `email`,`r`.`officeOrDept` AS `officeOrDept`,`r`.`profile_pic` AS `profile_pic` from `requester` `r` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_requests`
--

/*!50001 DROP VIEW IF EXISTS `vw_requests`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_requests` AS select `r`.`request_id` AS `request_id`,concat(`req`.`firstName`,' ',`req`.`lastName`) AS `Name`,`r`.`request_Type` AS `request_Type`,`r`.`location` AS `location`,`r`.`request_date` AS `request_date`,`ra`.`req_status` AS `req_status` from ((`request` `r` join `requester` `req` on(`r`.`req_id` = `req`.`req_id`)) join `request_assignment` `ra` on(`r`.`request_id` = `ra`.`request_id`)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_rqtrack`
--

/*!50001 DROP VIEW IF EXISTS `vw_rqtrack`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_rqtrack` AS select `r`.`tracking_id` AS `tracking_id`,`r`.`request_Type` AS `request_Type`,`r`.`request_desc` AS `request_desc`,`r`.`location` AS `location`,`ra`.`req_status` AS `req_status`,`ra`.`date_finished` AS `date_finished`,`r`.`req_id` AS `req_id`,`r`.`request_date` AS `request_date` from ((`request` `r` join `request_assignment` `ra` on(`r`.`request_id` = `ra`.`request_id`)) join `requester` `rq` on(`r`.`req_id` = `rq`.`req_id`)) group by `r`.`req_id`,`r`.`tracking_id` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_work_history`
--

/*!50001 DROP VIEW IF EXISTS `vw_work_history`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_work_history` AS select `rap`.`staff_id` AS `staff_id`,`r`.`request_id` AS `request_id`,`r`.`request_Type` AS `request_Type`,`ra`.`date_finished` AS `date_finished`,`ra`.`req_status` AS `req_status` from ((`request_assigned_personnel` `rap` join `request_assignment` `ra` on(`rap`.`request_id` = `ra`.`request_id`)) join `request` `r` on(`ra`.`request_id` = `r`.`request_id`)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-11-21 13:37:13
