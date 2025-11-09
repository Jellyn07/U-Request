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
INSERT INTO `administrator` VALUES ('2021-00001','gsuadmin@usep.edu.ph','Gsu','Adimen','09123456781',2,'K3hWNDZtTlYwRmpwMHlYdjBvWEFPQT09','desktop-wallpaper-sad-funny-cute-plankton-face-plankton.jpg','Active'),('2023-00002','motorpooladmin@usep.edu.ph','Motorpool','Adimen','09123456780',3,'R0kvQnhlQ3MydjNFZHovQlR1V0VOQT09','Plankton-Spongebob-Series-Iconic-Villain-PNG-thumb.png','Active'),('2023-000111','testing1@usep.edu.ph','Testing','Two','09123456121',2,'Y2Y4a1ZDKytnM2FOOVVveC83bUFTdz09',NULL,'Active'),('2023-00062','superadmin@usep.edu.ph','Super','Admin','',1,'Y2Y4a1ZDKytnM2FOOVVveC83bUFTdz09','','Active'),('2023-00063','motorpooladmin1@usep.edu.ph','Motorpool','Addddddd','09923456789',2,'Y2Y4a1ZDKytnM2FOOVVveC83bUFTdz09',NULL,'Active');
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trg_admin_insert` AFTER INSERT ON `administrator` FOR EACH ROW BEGIN
    DECLARE staff_fullname VARCHAR(150);
    SET staff_fullname = CONCAT(NEW.first_name, ' ', NEW.last_name);

    INSERT INTO administrator_audit(staff_id, staff_name, action, description)
    VALUES(NEW.staff_id, staff_fullname, 'INSERT', CONCAT(staff_fullname, ' added administrator: ', NEW.first_name,' ',NEW.last_name));
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trg_admin_update` AFTER UPDATE ON `administrator` FOR EACH ROW BEGIN
    DECLARE staff_fullname VARCHAR(150);
    DECLARE changes TEXT DEFAULT '';

    SET staff_fullname = CONCAT(NEW.first_name, ' ', NEW.last_name);

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

    INSERT INTO administrator_audit(staff_id, staff_name, action, description)
    VALUES(
        NEW.staff_id,
        staff_fullname,
        'UPDATE',
        CONCAT(staff_fullname, ' updated administrator: ', changes)
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trg_admin_delete` AFTER DELETE ON `administrator` FOR EACH ROW BEGIN
    DECLARE staff_fullname VARCHAR(150);
    SET staff_fullname = CONCAT(OLD.first_name, ' ', OLD.last_name);

    INSERT INTO administrator_audit(staff_id, staff_name, action, description)
    VALUES(
        OLD.staff_id,
        staff_fullname,
        'DELETE',
        CONCAT(staff_fullname, ' was deleted.')
    );
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `administrator_audit`
--

DROP TABLE IF EXISTS `administrator_audit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `administrator_audit` (
  `audit_id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_id` varchar(100) DEFAULT NULL,
  `staff_name` varchar(150) DEFAULT NULL,
  `action` enum('INSERT','UPDATE','DELETE') DEFAULT NULL,
  `description` text DEFAULT NULL,
  `changed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`audit_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `administrator_audit`
--

LOCK TABLES `administrator_audit` WRITE;
/*!40000 ALTER TABLE `administrator_audit` DISABLE KEYS */;
INSERT INTO `administrator_audit` VALUES (1,'2023-000111','Testing One','INSERT','Testing One added administrator: Testing One','2025-10-24 15:08:52'),(2,'2023-000111','Testing Two','UPDATE','Testing Two updated administrator: Name from \"Testing One\" to \"Testing Two\"; ','2025-10-24 15:51:08'),(3,'2023-00063','Motorpool Addddddd','INSERT','Motorpool Addddddd added administrator: Motorpool Addddddd','2025-11-03 12:57:08');
/*!40000 ALTER TABLE `administrator_audit` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `campus_locations`
--

LOCK TABLES `campus_locations` WRITE;
/*!40000 ALTER TABLE `campus_locations` DISABLE KEYS */;
INSERT INTO `campus_locations` VALUES (1,'Tagum Unit','PECC - Physical Education Cultural Center','Clinic','2025-10-23 17:02:44'),(2,'Tagum Unit','PECC - Physical Education Cultural Center','Office of Registrar (OUR)','2025-10-23 17:03:34'),(3,'Tagum Unit','SOM - School of Medicine','Dean\'s Office','2025-10-24 08:04:28'),(7,'Tagum Unit','SOM - School of Medicine','SB-05','2025-10-24 16:05:32');
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trg_campus_insert` AFTER INSERT ON `campus_locations` FOR EACH ROW BEGIN
    DECLARE admin_name VARCHAR(150);
    SET admin_name = (SELECT CONCAT(first_name,' ',last_name) FROM administrator WHERE staff_id = @current_staff_id);

    INSERT INTO campus_locations_audit(staff_name, action, description)
    VALUES(
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trg_campus_update` AFTER UPDATE ON `campus_locations` FOR EACH ROW BEGIN
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

    INSERT INTO campus_locations_audit(staff_name, action, description)
    VALUES(
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trg_campus_delete` AFTER DELETE ON `campus_locations` FOR EACH ROW BEGIN
    DECLARE admin_name VARCHAR(150);
    SET admin_name = (SELECT CONCAT(first_name,' ',last_name) FROM administrator WHERE staff_id = @current_staff_id);

    INSERT INTO campus_locations_audit(staff_name, action, description)
    VALUES(
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
-- Table structure for table `campus_locations_audit`
--

DROP TABLE IF EXISTS `campus_locations_audit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `campus_locations_audit` (
  `audit_id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_name` varchar(150) DEFAULT NULL,
  `action` enum('INSERT','UPDATE','DELETE') DEFAULT NULL,
  `description` text DEFAULT NULL,
  `changed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`audit_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `campus_locations_audit`
--

LOCK TABLES `campus_locations_audit` WRITE;
/*!40000 ALTER TABLE `campus_locations_audit` DISABLE KEYS */;
INSERT INTO `campus_locations_audit` VALUES (1,'Gsu Adimen','INSERT','Gsu Adimen added campus location: Tagum Unit, SOM - School of Medicine, SB-01','2025-10-24 16:05:32'),(2,'Gsu Adimen','UPDATE','Gsu Adimen updated campus location: Exact Location from \"SB-01\" to \"SB-02\"; ','2025-10-24 16:10:00'),(3,'Super Admin','UPDATE','Super Admin updated campus location: Exact Location from \"SB-02\" to \"SB-05\"; ','2025-10-30 01:21:06'),(4,'Gsu Adimen','UPDATE','Gsu Adimen updated campus location: Building from \"SOM - School of Medicine\" to \"PECC - Physical Education Cultural Center\"; ','2025-11-07 18:10:41'),(5,'Gsu Adimen','UPDATE','Gsu Adimen updated campus location: Building from \"PECC - Physical Education Cultural Center\" to \"SOM - School of Medicine\"; ','2025-11-07 18:11:11');
/*!40000 ALTER TABLE `campus_locations_audit` ENABLE KEYS */;
UNLOCK TABLES;

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
INSERT INTO `driver` VALUES (2023,'Ben','Teh','09123456781','2020-06-25','me.jpg'),(10001,'Dry','Beer','09123456761','2022-06-21','pexels-ajdin-coric-504250286-27607236.jpg'),(10002,'White','Beach','09123456780','2025-09-30',NULL);
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
    -- Get the name of the staff performing the insert
    SET admin_name = (
        SELECT CONCAT(first_name, ' ', last_name)
        FROM administrator
        WHERE staff_id = @current_staff_id
    );

    -- Insert an audit record, including the new driver_id
    INSERT INTO driver_audit ( staff_name, action, description)
    VALUES (
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

    INSERT INTO driver_audit(staff_name, action, description)
    VALUES(
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
-- Table structure for table `driver_audit`
--

DROP TABLE IF EXISTS `driver_audit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `driver_audit` (
  `audit_id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_name` varchar(150) DEFAULT NULL,
  `action` enum('INSERT','UPDATE','DELETE') DEFAULT NULL,
  `description` text DEFAULT NULL,
  `changed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`audit_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `driver_audit`
--

LOCK TABLES `driver_audit` WRITE;
/*!40000 ALTER TABLE `driver_audit` DISABLE KEYS */;
INSERT INTO `driver_audit` VALUES (1,'Motorpool Adimen','INSERT','Motorpool Adimen added driver: White Sea','2025-10-24 15:21:01'),(2,'Motorpool Adimen','UPDATE','Motorpool Adimen updated driver: Name from \"White Sea\" to \"White Beach\"; ','2025-10-24 15:45:41');
/*!40000 ALTER TABLE `driver_audit` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `feedback`
--

LOCK TABLES `feedback` WRITE;
/*!40000 ALTER TABLE `feedback` DISABLE KEYS */;
INSERT INTO `feedback` VALUES (1,'TRK-20251023-V6MEK','{\"0\":5,\"1\":4,\"2\":4,\"3\":5}','{\"0\":3,\"1\":4,\"2\":3,\"3\":4,\"4\":5,\"5\":4,\"6\":3}','{\"0\":5,\"1\":4,\"2\":3,\"3\":5}',4.07,'','','','Testing','2025-10-29 13:45:25'),(2,'TRK-VR20251028-73G56','{\"0\":2,\"1\":5,\"2\":3,\"3\":4}','{\"0\":5,\"1\":3,\"2\":4,\"3\":5,\"4\":3,\"5\":3,\"6\":5}','{\"0\":2,\"1\":5,\"2\":3,\"3\":3}',3.67,'Testing','Testing','Testing','Testing','2025-11-03 13:13:28');
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
INSERT INTO `gsu_personnel` VALUES (10001,'Jay','Mentos','Janitorial','09183456789','2025-10-01','Mabini Unit','Chris-Pinkham-2017.xl.jpg'),(10002,'White','Beard','Ground Maintenance','09123456880','2020-06-11','Tagum Unit','me.jpg'),(10003,'Orange','Beard','Landscaping','09023456880','2020-06-11','Tagum Unit',NULL),(10004,'Hen','Sea','Building Repair And Maintenance','09123459789','2025-10-28','Tagum Unit',NULL);
/*!40000 ALTER TABLE `gsu_personnel` ENABLE KEYS */;
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER trg_gsu_personnel_insert
AFTER INSERT ON gsu_personnel
FOR EACH ROW
BEGIN
    DECLARE admin_name VARCHAR(150);
    -- Get current admin name from session variable
    SET admin_name = (
        SELECT CONCAT(first_name, ' ', last_name)
        FROM administrator
        WHERE staff_id = @current_staff_id
    );

    -- Insert summary into audit table
    INSERT INTO gsu_personnel_audit (staff_name, action, description)
    VALUES (
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
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER trg_gsu_personnel_update
AFTER UPDATE ON gsu_personnel
FOR EACH ROW
BEGIN
    DECLARE admin_name VARCHAR(150);
    DECLARE changes TEXT DEFAULT '';
    -- Get admin name
    SET admin_name = (
        SELECT CONCAT(first_name, ' ', last_name)
        FROM administrator
        WHERE staff_id = @current_staff_id
    );
    -- Build a human-readable description of changes
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

    -- Only insert if there are changes
    IF changes != '' THEN
        INSERT INTO gsu_personnel_audit (staff_name, action, description)
        VALUES (
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `after_gsu_personnel_delete` AFTER DELETE ON `gsu_personnel` FOR EACH ROW BEGIN
    INSERT INTO gsu_personnel_audit (
        staff_id, firstName, lastName, department, contact, hire_date,
        action_type, action_by
    )
    VALUES (
        OLD.staff_id, OLD.firstName, OLD.lastName, OLD.department, OLD.contact, OLD.hire_date,
        'DELETE', USER()
    );
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `gsu_personnel_audit`
--

DROP TABLE IF EXISTS `gsu_personnel_audit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gsu_personnel_audit` (
  `audit_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `staff_name` varchar(150) NOT NULL,
  `action` enum('INSERT','UPDATE') NOT NULL,
  `description` text DEFAULT NULL,
  `changed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`audit_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gsu_personnel_audit`
--

LOCK TABLES `gsu_personnel_audit` WRITE;
/*!40000 ALTER TABLE `gsu_personnel_audit` DISABLE KEYS */;
INSERT INTO `gsu_personnel_audit` VALUES (1,'Gsu Adimen','UPDATE','First name changed from \"Black\" to \"Orange\"; ','2025-11-08 06:28:39'),(2,'Gsu Adimen','INSERT','Added new GSU Personnel: Hen Sea','2025-11-08 06:32:23');
/*!40000 ALTER TABLE `gsu_personnel_audit` ENABLE KEYS */;
UNLOCK TABLES;

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
INSERT INTO `materials` VALUES (1,'Paint Green',9,'Available'),(2,'Electrical Tape',20,'Available'),(3,'Screw',49,'Available'),(4,'Paint Yellow',10,'Available');
/*!40000 ALTER TABLE `materials` ENABLE KEYS */;
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER trg_materials_insert
AFTER INSERT ON materials
FOR EACH ROW
BEGIN
    DECLARE admin_name VARCHAR(150);

    -- Get current admin name from session variable
    SET admin_name = (
        SELECT CONCAT(first_name, ' ', last_name)
        FROM administrator
        WHERE staff_id = @current_staff_id
    );

    INSERT INTO materials_audit (staff_name, action, description)
    VALUES (
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
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER trg_materials_update
AFTER UPDATE ON materials
FOR EACH ROW
BEGIN
    DECLARE admin_name VARCHAR(150);
    DECLARE changes TEXT DEFAULT '';

    -- Get admin name
    SET admin_name = (
        SELECT CONCAT(first_name, ' ', last_name)
        FROM administrator
        WHERE staff_id = @current_staff_id
    );

    -- Detect changes and describe them
    IF OLD.material_desc != NEW.material_desc THEN
        SET changes = CONCAT(changes, 'Description changed from "', OLD.material_desc, '" to "', NEW.material_desc, '"; ');
    END IF;

    IF OLD.qty != NEW.qty THEN
        SET changes = CONCAT(changes, 'Quantity changed from ', OLD.qty, ' to ', NEW.qty, '; ');
    END IF;

    IF OLD.material_status != NEW.material_status THEN
        SET changes = CONCAT(changes, 'Status changed from "', OLD.material_status, '" to "', NEW.material_status, '"; ');
    END IF;

    -- Insert only if there are changes
    IF changes != '' THEN
        INSERT INTO materials_audit (staff_name, action, description)
        VALUES (
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
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER trg_materials_delete
AFTER DELETE ON materials
FOR EACH ROW
BEGIN
    DECLARE admin_name VARCHAR(150);

    -- Get admin name
    SET admin_name = (
        SELECT CONCAT(first_name, ' ', last_name)
        FROM administrator
        WHERE staff_id = @current_staff_id
    );

    INSERT INTO materials_audit (staff_name, action, description)
    VALUES (
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
-- Table structure for table `materials_audit`
--

DROP TABLE IF EXISTS `materials_audit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `materials_audit` (
  `audit_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `staff_name` varchar(150) NOT NULL,
  `action` enum('INSERT','UPDATE','DELETE') NOT NULL,
  `description` text DEFAULT NULL,
  `changed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`audit_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `materials_audit`
--

LOCK TABLES `materials_audit` WRITE;
/*!40000 ALTER TABLE `materials_audit` DISABLE KEYS */;
INSERT INTO `materials_audit` VALUES (1,'Gsu Adimen','UPDATE','Quantity changed from 17 to 20; ','2025-11-08 06:46:04'),(2,'Gsu Adimen','INSERT','Added new material: Paint Yellow, Code: 4, Quantity: 10','2025-11-08 06:48:56'),(3,'Gsu Adimen','UPDATE','Quantity changed from 12 to 11; ','2025-11-08 07:47:00'),(4,'Gsu Adimen','UPDATE','Quantity changed from 11 to 10; ','2025-11-09 10:10:41'),(5,'Gsu Adimen','UPDATE','Quantity changed from 10 to 9; ','2025-11-09 10:11:00');
/*!40000 ALTER TABLE `materials_audit` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `passengers`
--

LOCK TABLES `passengers` WRITE;
/*!40000 ALTER TABLE `passengers` DISABLE KEYS */;
INSERT INTO `passengers` VALUES (1,'Riley','Reyes'),(2,'Shine','Reyes'),(3,'Gurly','Reyes'),(4,'Gurly','Gourl'),(5,'Gurly','Gay');
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
  CONSTRAINT `fk_request_requester` FOREIGN KEY (`req_id`) REFERENCES `requester` (`req_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `request`
--

LOCK TABLES `request` WRITE;
/*!40000 ALTER TABLE `request` DISABLE KEYS */;
INSERT INTO `request` VALUES (1,'TRK-20251023-V6MEK','Electrical',1,'Fire Outlet Po.','Tagum Unit','PECC - Physical Education Cultural Center - Clinic','2025-10-23','fire.jpg'),(2,'TRK-20251023-RK3UN','Electrical',1,'Fire Outlet','Tagum Unit','PECC - Physical Education Cultural Center - Office of Registrar (OUR)','2025-10-23','fire.jpg'),(3,'TRK-20251024-EA3M9','Others',1,'Testing File Upload 5mb Up','Tagum Unit','SOM - School of Medicine - Dean\'s Office','2025-10-24','IMG_20230426_201836.jpg'),(7,'TRK-20251108-FWDQR','Hauling',2,'Testing','Tagum Unit','PECC - Physical Education Cultural Center - Clinic','2025-11-08','truck.jpg'),(8,'TRK-20251109-LIXVR','Hauling',3,'Testing','Tagum Unit','PECC - Physical Education Cultural Center - Office of Registrar (OUR)','2025-11-09','fire.jpg');
/*!40000 ALTER TABLE `request` ENABLE KEYS */;
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER trg_request_insert
AFTER INSERT ON request
FOR EACH ROW
BEGIN
    DECLARE req_name VARCHAR(150);
    -- Get requester full name
    SET req_name = (
        SELECT CONCAT(firstName, ' ', lastName)
        FROM requester           -- change this to your requester table
        WHERE req_id = @current_req_id
    );

    INSERT INTO request_audit (requester_name, action, description)
    VALUES (
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
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER trg_request_update
AFTER UPDATE ON request
FOR EACH ROW
BEGIN
    DECLARE req_name VARCHAR(150);
    DECLARE changes TEXT DEFAULT '';

    -- Get requester full name
    SET req_name = (
        SELECT CONCAT(first_name, ' ', last_name)
        FROM administrator
        WHERE staff_id = @current_staff_id
    );

    -- Detect changes in location only
    IF OLD.location != NEW.location THEN
        SET changes = CONCAT('Location changed from "', OLD.location, '" to "', NEW.location, '"');
    END IF;

    -- Insert only if there are changes
    IF changes != '' THEN
        INSERT INTO request_audit (requester_name, action, description)
        VALUES (
            req_name,
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
  CONSTRAINT `fk_assigned_personnel` FOREIGN KEY (`staff_id`) REFERENCES `gsu_personnel` (`staff_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_assigned_request` FOREIGN KEY (`request_id`) REFERENCES `request` (`request_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `request_assigned_personnel`
--

LOCK TABLES `request_assigned_personnel` WRITE;
/*!40000 ALTER TABLE `request_assigned_personnel` DISABLE KEYS */;
INSERT INTO `request_assigned_personnel` VALUES (1,1,10001),(2,3,10001),(3,3,10002),(63,7,10004),(70,2,10004);
/*!40000 ALTER TABLE `request_assigned_personnel` ENABLE KEYS */;
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER trg_request_assigned_personnel_insert
AFTER INSERT ON request_assigned_personnel
FOR EACH ROW
BEGIN
    DECLARE personnel_name VARCHAR(150) DEFAULT 'Unknown Personnel';
    DECLARE admin_name VARCHAR(150) DEFAULT 'System';
    DECLARE description_text TEXT;

    -- Get administrator name from session variable if available
    IF @current_staff_id IS NOT NULL THEN
        SELECT CONCAT(first_name, ' ', last_name)
        INTO admin_name
        FROM administrator
        WHERE staff_id = @current_staff_id
        LIMIT 1;
    END IF;

    -- Get personnel full name
    SELECT CONCAT(firstName, ' ', lastName)
    INTO personnel_name
    FROM gsu_personnel
    WHERE staff_id = NEW.staff_id
    LIMIT 1;

    -- Compose the description
    SET description_text = CONCAT('Assigned ', personnel_name, ' to request ID ', NEW.request_id);

    -- Insert a single audit record
    INSERT INTO request_assigned_personnel_audit (staff_name, action, description)
    VALUES (admin_name, 'INSERT', description_text);
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `request_assigned_personnel_audit`
--

DROP TABLE IF EXISTS `request_assigned_personnel_audit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `request_assigned_personnel_audit` (
  `audit_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `staff_name` varchar(150) NOT NULL,
  `action` enum('INSERT') NOT NULL,
  `description` text DEFAULT NULL,
  `changed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`audit_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `request_assigned_personnel_audit`
--

LOCK TABLES `request_assigned_personnel_audit` WRITE;
/*!40000 ALTER TABLE `request_assigned_personnel_audit` DISABLE KEYS */;
INSERT INTO `request_assigned_personnel_audit` VALUES (1,'Gsu Adimen','INSERT','Assigned Hen Sea to request ID 2','2025-11-09 11:09:10');
/*!40000 ALTER TABLE `request_assigned_personnel_audit` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `request_assignment`
--

LOCK TABLES `request_assignment` WRITE;
/*!40000 ALTER TABLE `request_assignment` DISABLE KEYS */;
INSERT INTO `request_assignment` VALUES (1,1,1,'Completed','2025-11-08','High'),(2,2,1,'In Progress',NULL,'Low'),(3,3,1,'In Progress',NULL,'Low'),(7,7,2,'In Progress',NULL,'High'),(8,8,3,'To Inspect',NULL,'Low');
/*!40000 ALTER TABLE `request_assignment` ENABLE KEYS */;
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER trg_request_assignment_status_update
AFTER UPDATE ON request_assignment
FOR EACH ROW
BEGIN
    DECLARE v_admin_name VARCHAR(150) DEFAULT 'System';
    DECLARE v_tracking_id VARCHAR(100) DEFAULT 'N/A';
    DECLARE v_changes TEXT;

    -- ✅ Get current admin name if session variable is set
    IF @current_staff_id IS NOT NULL THEN
        SELECT CONCAT(first_name, ' ', last_name)
        INTO v_admin_name
        FROM administrator
        WHERE staff_id = @current_staff_id
        LIMIT 1;
    END IF;

    -- ✅ Get tracking_id from request table
    SELECT tracking_id
    INTO v_tracking_id
    FROM request
    WHERE request_id = NEW.request_id
    LIMIT 1;

    -- ✅ Only log when status *actually* changes and not null or same
    IF (OLD.req_status IS NULL AND NEW.req_status IS NOT NULL)
        OR (OLD.req_status IS NOT NULL AND NEW.req_status IS NULL)
        OR (OLD.req_status <> NEW.req_status) THEN

        -- ✅ Prevent duplicate inserts for the same request and status in a short timeframe
        IF NOT EXISTS (
            SELECT 1
            FROM status_audit
            WHERE description LIKE CONCAT('%Tracking ID: ', v_tracking_id, '%New Status: "', NEW.req_status, '"%')
              AND TIMESTAMPDIFF(SECOND, changed_at, NOW()) < 3
        ) THEN
            SET v_changes = CONCAT(
                'Tracking ID: ', COALESCE(v_tracking_id, 'N/A'),
                ' | Previous Status: "', COALESCE(OLD.req_status, 'None'),
                '" | New Status: "', COALESCE(NEW.req_status, 'None'), '"'
            );

            INSERT INTO status_audit (staff_name, action, description)
            VALUES (v_admin_name, 'UPDATE', v_changes);
        END IF;
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `request_audit`
--

DROP TABLE IF EXISTS `request_audit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `request_audit` (
  `audit_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `requester_name` varchar(150) NOT NULL,
  `action` enum('INSERT','UPDATE') NOT NULL,
  `description` text DEFAULT NULL,
  `changed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`audit_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `request_audit`
--

LOCK TABLES `request_audit` WRITE;
/*!40000 ALTER TABLE `request_audit` DISABLE KEYS */;
INSERT INTO `request_audit` VALUES (1,'Lay Zhang','INSERT','New request created. Type: Hauling, Location: PECC - Physical Education Cultural Center - Clinic','2025-11-08 07:04:42'),(2,'Testing One','INSERT','New request created. Type: Hauling, Location: PECC - Physical Education Cultural Center - Office of Registrar (OUR)','2025-11-09 13:49:46');
/*!40000 ALTER TABLE `request_audit` ENABLE KEYS */;
UNLOCK TABLES;

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
  UNIQUE KEY `uniq_req_material` (`reqAssignment_id`,`material_code`),
  KEY `fk_materials_needed_assignment` (`reqAssignment_id`),
  KEY `fk_materials_needed_material` (`material_code`),
  CONSTRAINT `fk_materials_needed_assignment` FOREIGN KEY (`reqAssignment_id`) REFERENCES `request_assignment` (`reqAssignment_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_materials_needed_material` FOREIGN KEY (`material_code`) REFERENCES `materials` (`material_code`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `request_materials_needed`
--

LOCK TABLES `request_materials_needed` WRITE;
/*!40000 ALTER TABLE `request_materials_needed` DISABLE KEYS */;
INSERT INTO `request_materials_needed` VALUES (1,1,2,1,'2025-10-23 17:23:55'),(2,3,1,5,'2025-11-07 15:24:57'),(3,3,2,1,'2025-11-07 15:24:57'),(39,2,1,1,'2025-11-08 07:47:00'),(40,7,1,2,'2025-11-09 10:10:41');
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
  PRIMARY KEY (`req_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `requester`
--

LOCK TABLES `requester` WRITE;
/*!40000 ALTER TABLE `requester` DISABLE KEYS */;
INSERT INTO `requester` VALUES (1,'2023-00060','Jonalyn','Gujol','09383737381','MWw5UUJZTnMrd1J5Rm9QWDAwVmhuZz09','jsgujol00060@usep.edu.ph','BSNED',NULL),(2,'2023-000065','Lay','Zhang',NULL,'Y2Y4a1ZDKytnM2FOOVVveC83bUFTdz09','jia@usep.edu.ph',NULL,NULL),(3,'2020-00011','Testing','One',NULL,'Y2Y4a1ZDKytnM2FOOVVveC83bUFTdz09','testing1@usep.edu.ph',NULL,NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `source_of_fund`
--

LOCK TABLES `source_of_fund` WRITE;
/*!40000 ALTER TABLE `source_of_fund` DISABLE KEYS */;
INSERT INTO `source_of_fund` VALUES (4,22,'Donation','Collection','Own Money','Collection'),(5,23,'Donation','Collection','Own Money','Collection'),(6,24,'Donation','Collection','Own Money','Collection'),(11,48,'Donation','Collection','Own Money','Collection');
/*!40000 ALTER TABLE `source_of_fund` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `status_audit`
--

DROP TABLE IF EXISTS `status_audit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `status_audit` (
  `audit_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `staff_name` varchar(150) NOT NULL,
  `action` enum('UPDATE') NOT NULL,
  `description` text DEFAULT NULL,
  `changed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`audit_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `status_audit`
--

LOCK TABLES `status_audit` WRITE;
/*!40000 ALTER TABLE `status_audit` DISABLE KEYS */;
INSERT INTO `status_audit` VALUES (4,'System','UPDATE','Tracking ID: TRK-20251108-FWDQR | Previous Status: \"In Progress\" | New Status: \"To Inspect\"','2025-11-09 10:36:06'),(5,'Gsu Adimen','UPDATE','Tracking ID: TRK-20251108-FWDQR | Previous Status: \"To Inspect\" | New Status: \"In Progress\"','2025-11-09 10:36:14'),(6,'System','UPDATE','Tracking ID: TRK-20251108-FWDQR | Previous Status: \"In Progress\" | New Status: \"To Inspect\"','2025-11-09 10:38:03'),(7,'Gsu Adimen','UPDATE','Tracking ID: TRK-20251108-FWDQR | Previous Status: \"To Inspect\" | New Status: \"In Progress\"','2025-11-09 10:38:21'),(8,'System','UPDATE','Tracking ID: TRK-20251108-FWDQR | Previous Status: \"In Progress\" | New Status: \"Completed\"','2025-11-09 11:01:24'),(9,'System','UPDATE','Tracking ID: TRK-20251108-FWDQR | Previous Status: \"Completed\" | New Status: \"To Inspect\"','2025-11-09 11:02:03'),(10,'Gsu Adimen','UPDATE','Tracking ID: TRK-20251108-FWDQR | Previous Status: \"To Inspect\" | New Status: \"In Progress\"','2025-11-09 11:02:09'),(11,'Gsu Adimen','UPDATE','Tracking ID: TRK-20251108-FWDQR | Previous Status: \"In Progress\" | New Status: \"Completed\"','2025-11-09 11:02:44'),(12,'Gsu Adimen','UPDATE','Tracking ID: TRK-20251108-FWDQR | Previous Status: \"Completed\" | New Status: \"None\"','2025-11-09 11:17:18'),(13,'System','UPDATE','Tracking ID: TRK-20251108-FWDQR | Previous Status: \"None\" | New Status: \"To Inspect\"','2025-11-09 11:18:21'),(14,'Super Admin','UPDATE','Tracking ID: TRK-20251108-FWDQR | Previous Status: \"To Inspect\" | New Status: \"In Progress\"','2025-11-09 12:39:23'),(15,'Gsu Adimen','UPDATE','Tracking ID: TRK-20251109-LIXVR | Previous Status: \"To Inspect\" | New Status: \"In Progress\"','2025-11-09 13:51:09'),(16,'System','UPDATE','Tracking ID: TRK-20251109-LIXVR | Previous Status: \"In Progress\" | New Status: \"To Inspect\"','2025-11-09 13:53:39'),(17,'Gsu Adimen','UPDATE','Tracking ID: TRK-20251109-LIXVR | Previous Status: \"To Inspect\" | New Status: \"In Progress\"','2025-11-09 13:56:34'),(18,'System','UPDATE','Tracking ID: TRK-20251109-LIXVR | Previous Status: \"In Progress\" | New Status: \"To Inspect\"','2025-11-09 13:59:10'),(19,'Gsu Adimen','UPDATE','Tracking ID: TRK-20251109-LIXVR | Previous Status: \"To Inspect\" | New Status: \"None\"','2025-11-09 13:59:19'),(20,'Gsu Adimen','UPDATE','Tracking ID: TRK-20251109-LIXVR | Previous Status: \"None\" | New Status: \"To Inspect\"','2025-11-09 14:00:04');
/*!40000 ALTER TABLE `status_audit` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehicle`
--

LOCK TABLES `vehicle` WRITE;
/*!40000 ALTER TABLE `vehicle` DISABLE KEYS */;
INSERT INTO `vehicle` VALUES (1,'USeP Bus - Tagum ','NVR321',60,'Bus',10001,'usepbus.jpg','Available'),(3,'USeP Van - Tagum Unit','UVN456',10,'Van',10002,'usepvan.jpg','Available'),(4,'USeP Van 2 - Tagum Unit','NVU456',10,'Van',2023,'van1.jpeg','Available'),(5,'USeP Vehicle - Tagum Unit','VHA991',7,'Sedan',10002,'hilux.jpg','Available'),(8,'USeP Truck ','TRK 211',61,'Truck',10001,'truck.jpg','Available');
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

    -- Get the name of the admin performing the action
    SET admin_name = (
        SELECT CONCAT(first_name, ' ', last_name)
        FROM administrator
        WHERE staff_id = @current_staff_id
    );

    -- Log the action
    INSERT INTO vehicle_audit (staff_name, action, description)
    VALUES (
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

    -- Get current admin name from session variable
    SET admin_name = (
        SELECT CONCAT(first_name, ' ', last_name)
        FROM administrator
        WHERE staff_id = @current_staff_id
    );

    -- Detect changes
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

    -- Log the action
    INSERT INTO vehicle_audit (staff_name, action, description)
    VALUES (
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
-- Table structure for table `vehicle_audit`
--

DROP TABLE IF EXISTS `vehicle_audit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vehicle_audit` (
  `audit_id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_name` varchar(150) DEFAULT NULL,
  `action` enum('INSERT','UPDATE','DELETE') DEFAULT NULL,
  `description` text DEFAULT NULL,
  `changed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`audit_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehicle_audit`
--

LOCK TABLES `vehicle_audit` WRITE;
/*!40000 ALTER TABLE `vehicle_audit` DISABLE KEYS */;
INSERT INTO `vehicle_audit` VALUES (1,'Motorpool Adimen','INSERT','Motorpool Adimen added vehicle: USeP Vehicle - Tagum Unit (Plate: VHA991, Type: Sedan)','2025-10-24 18:13:03'),(3,'Motorpool Adimen','UPDATE','Motorpool Adimen updated vehicle: USeP Bus - Tagum Unit. Changes: Name from \"USeP Bus - Tagum \" to \"USeP Bus - Tagum Unit\"; Capacity from \"60\" to \"61\"; ','2025-10-29 07:05:18'),(4,'Motorpool Adimen','UPDATE','Motorpool Adimen updated vehicle: USeP Van 2 - Tagum Unit. Changes: Photo updated; ','2025-10-29 07:08:53'),(5,'Motorpool Adimen','UPDATE','Motorpool Adimen updated vehicle: USeP Van - Tagum Unit. Changes: Driver changed; ','2025-10-29 08:22:17'),(6,'Motorpool Adimen','UPDATE','Motorpool Adimen updated vehicle: USeP Bus - Tagum . Changes: Name from \"USeP Bus - Tagum Unit\" to \"USeP Bus - Tagum \"; Capacity from \"61\" to \"60\"; ','2025-11-04 16:12:17'),(7,'Motorpool Adimen','UPDATE','Motorpool Adimen updated vehicle: USeP Bus - Tagum Unit. Changes: Name from \"USeP Bus - Tagum \" to \"USeP Bus - Tagum Unit\"; ','2025-11-04 16:13:06'),(8,'Motorpool Adimen','UPDATE','Motorpool Adimen updated vehicle: USeP Bus - Tagum . Changes: Name from \"USeP Bus - Tagum Unit\" to \"USeP Bus - Tagum \"; Capacity from \"60\" to \"65\"; ','2025-11-04 16:13:32'),(9,'Motorpool Adimen','UPDATE','Motorpool Adimen updated vehicle: USeP Bus - Tagum . Changes: Capacity from \"65\" to \"60\"; ','2025-11-04 16:13:44'),(10,'Motorpool Adimen','INSERT','Motorpool Adimen added vehicle: USeP Truck  (Plate: TRK 211, Type: Truck)','2025-11-06 06:03:47'),(11,NULL,'DELETE',NULL,'2025-11-06 06:05:06'),(12,'Super Admin','INSERT','Super Admin added vehicle: USeP Truck  (Plate: TRK 211, Type: Truck)','2025-11-06 06:05:38'),(13,NULL,'DELETE',NULL,'2025-11-06 06:06:57'),(14,'Super Admin','INSERT','Super Admin added vehicle: USeP Truck  (Plate: TRK 211, Type: Truck)','2025-11-06 06:07:49'),(15,'Super Admin','UPDATE','Super Admin updated vehicle: USeP Truck . Changes: Capacity from \"60\" to \"61\"; ','2025-11-06 06:08:12');
/*!40000 ALTER TABLE `vehicle_audit` ENABLE KEYS */;
UNLOCK TABLES;

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
  CONSTRAINT `fk_vehicle_request_requester` FOREIGN KEY (`req_id`) REFERENCES `requester` (`req_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehicle_request`
--

LOCK TABLES `vehicle_request` WRITE;
/*!40000 ALTER TABLE `vehicle_request` DISABLE KEYS */;
INSERT INTO `vehicle_request` VALUES (22,2,'2025-10-27 21:42:36','TRK-VR20251027-2OXED','Field Trip To Mabini Unit','Tagum Unit - Mabini Unit','2025-11-07','2025-11-07','05:00:00','17:00:00'),(23,2,'2025-10-28 16:26:18','TRK-VR20251028-73G56','Field Trip To Obrero Campus','Tagum Unit - Obrero Campus','2025-11-14','2025-11-14','05:00:00','17:00:00'),(24,1,'2025-10-28 16:51:46','TRK-VR20251028-4FG3X','Field Trip To Mintal Campus','Tagum Unit - Mintal Campus','2025-11-25','2025-11-25','05:00:00','17:00:00'),(48,1,'2025-11-04 22:37:32','TRK-VR20251104-EOHAV','Field Trip To Obrero Campus','Tagum Unit - Obrero Campus','2025-11-17','2025-11-17','05:00:00','17:00:00');
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

    -- Get requester's name using req_id
    SET requester_name = (
        SELECT CONCAT(firstName, ' ', lastName)
        FROM requester
        WHERE req_id = @current_req_id
    );

    INSERT INTO vehicle_request_audit (requester_name, action, description)
    VALUES (
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
  `approval_token` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`reqAssignment_id`),
  KEY `fk_vra_vehicle_request` (`control_no`),
  KEY `fk_vra_requester` (`req_id`),
  KEY `fk_vra_vehicle` (`vehicle_id`),
  KEY `fk_vra_driver` (`driver_id`),
  CONSTRAINT `fk_vra_driver` FOREIGN KEY (`driver_id`) REFERENCES `driver` (`driver_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_vra_requester` FOREIGN KEY (`req_id`) REFERENCES `requester` (`req_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_vra_vehicle` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicle` (`vehicle_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_vra_vehicle_request` FOREIGN KEY (`control_no`) REFERENCES `vehicle_request` (`control_no`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehicle_request_assignment`
--

LOCK TABLES `vehicle_request_assignment` WRITE;
/*!40000 ALTER TABLE `vehicle_request_assignment` DISABLE KEYS */;
INSERT INTO `vehicle_request_assignment` VALUES (2,22,2,NULL,NULL,'Pending',NULL,'da49e97c9e42f7c7d12e8461a302a27c'),(3,23,2,3,10002,'Completed','Dr. Shirley Villanueva',NULL),(4,24,1,1,10001,'On Going','Dr. Shirley Villanueva',NULL),(25,48,1,NULL,NULL,'Pending',NULL,NULL);
/*!40000 ALTER TABLE `vehicle_request_assignment` ENABLE KEYS */;
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trg_vehicle_request_assignment_update` AFTER UPDATE ON `vehicle_request_assignment` FOR EACH ROW BEGIN
    DECLARE admin_name VARCHAR(150);
    DECLARE vehicle_old VARCHAR(100);
    DECLARE vehicle_new VARCHAR(100);
    DECLARE driver_old VARCHAR(150);
    DECLARE driver_new VARCHAR(150);
    DECLARE changes TEXT DEFAULT '';

    -- Get admin name
    SET admin_name = (
        SELECT CONCAT(first_name, ' ', last_name)
        FROM administrator
        WHERE staff_id = @current_staff_id
    );

    -- Get old/new vehicle names
    SET vehicle_old = (SELECT vehicle_name FROM vehicle WHERE vehicle_id = OLD.vehicle_id);
    SET vehicle_new = (SELECT vehicle_name FROM vehicle WHERE vehicle_id = NEW.vehicle_id);

    -- Get old/new driver names
    SET driver_old = (SELECT CONCAT(firstName, ' ', lastName) FROM driver WHERE driver_id = OLD.driver_id);
    SET driver_new = (SELECT CONCAT(firstName, ' ', lastName) FROM driver WHERE driver_id = NEW.driver_id);

    -- Detect changes and create summary
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

    -- Only insert if there is a change
    IF changes != '' THEN
        INSERT INTO vehicle_request_assignment_audit (staff_name, action, description)
        VALUES (
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
-- Table structure for table `vehicle_request_assignment_audit`
--

DROP TABLE IF EXISTS `vehicle_request_assignment_audit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vehicle_request_assignment_audit` (
  `audit_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `staff_name` varchar(150) NOT NULL,
  `action` enum('INSERT','UPDATE') NOT NULL,
  `description` text DEFAULT NULL,
  `changed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`audit_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehicle_request_assignment_audit`
--

LOCK TABLES `vehicle_request_assignment_audit` WRITE;
/*!40000 ALTER TABLE `vehicle_request_assignment_audit` DISABLE KEYS */;
INSERT INTO `vehicle_request_assignment_audit` VALUES (1,'Motorpool Adimen','UPDATE','Status changed from \"In Progress\" to \"Approved\"; ','2025-10-30 07:47:57'),(2,'Motorpool Adimen','UPDATE','Status changed from \"Pending\" to \"Completed\"; ','2025-11-03 13:02:19'),(3,'Motorpool Adimen','UPDATE','Status changed from \"Completed\" to \"Approved\"; ','2025-11-03 13:06:31'),(4,'Motorpool Adimen','UPDATE','Status changed from \"Approved\" to \"Completed\"; ','2025-11-03 13:10:35'),(5,'Motorpool Adimen','UPDATE','Status changed from \"Approved\" to \"In Progress\"; ','2025-11-04 15:04:02'),(6,'Motorpool Adimen','UPDATE','Status changed from \"In Progress\" to \"On Going\"; ','2025-11-04 15:07:57'),(7,'Motorpool Adimen','UPDATE','Approved by changed from \"Engr. John Dela Cruz\" to \"Dr. Shirley Villanueva\"; ','2025-11-04 15:32:31'),(8,'Motorpool Adimen','UPDATE','Status changed from \"Completed\" to \"In Progress\"; ','2025-11-05 03:11:50'),(9,'Motorpool Adimen','UPDATE','Status changed from \"In Progress\" to \"Rejected/Cancelled\"; ','2025-11-05 03:12:01'),(10,'Motorpool Adimen','UPDATE','Status changed from \"Rejected/Cancelled\" to \"Completed\"; ','2025-11-05 03:12:29'),(11,'Motorpool Adimen','UPDATE','Status changed from \"Completed\" to \"On Going\"; ','2025-11-05 03:30:54'),(12,'Motorpool Adimen','UPDATE','Status changed from \"On Going\" to \"Completed\"; ','2025-11-05 03:31:26'),(13,'Motorpool Adimen','UPDATE','Status changed from \"Completed\" to \"On Going\"; ','2025-11-05 03:32:24'),(14,'Motorpool Adimen','UPDATE','Status changed from \"On Going\" to \"Completed\"; ','2025-11-05 03:32:36');
/*!40000 ALTER TABLE `vehicle_request_assignment_audit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vehicle_request_audit`
--

DROP TABLE IF EXISTS `vehicle_request_audit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vehicle_request_audit` (
  `audit_id` int(11) NOT NULL AUTO_INCREMENT,
  `requester_name` varchar(150) DEFAULT NULL,
  `action` enum('INSERT') DEFAULT NULL,
  `description` text DEFAULT NULL,
  `changed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`audit_id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehicle_request_audit`
--

LOCK TABLES `vehicle_request_audit` WRITE;
/*!40000 ALTER TABLE `vehicle_request_audit` DISABLE KEYS */;
INSERT INTO `vehicle_request_audit` VALUES (1,'Jonalyn Gujol','INSERT','Jonalyn Gujol created a new vehicle request with tracking ID TRK-20251025-XKRH9','2025-10-25 04:40:25'),(2,'Lay Zhang','INSERT','Lay Zhang created a new vehicle request with tracking ID TRK-20251027-5N1GR','2025-10-27 13:38:01'),(3,'Lay Zhang','INSERT','Lay Zhang created a new vehicle request with tracking ID TRK-20251027-H761W','2025-10-27 13:39:45'),(4,'Lay Zhang','INSERT','Lay Zhang created a new vehicle request with tracking ID TRK-20251027-2OXED','2025-10-27 13:42:36'),(5,'Lay Zhang','INSERT','Lay Zhang created a new vehicle request with tracking ID TRK-20251028-73G56','2025-10-28 08:26:18'),(6,'Jonalyn Gujol','INSERT','Jonalyn Gujol created a new vehicle request with tracking ID TRK-VR20251028-4FG3X','2025-10-28 08:51:46'),(7,'Lay Zhang','INSERT','Lay Zhang created a new vehicle request with tracking ID TRK-VR20251103-SBYQH','2025-11-03 13:37:35'),(8,'Lay Zhang','INSERT','Lay Zhang created a new vehicle request with tracking ID TRK-VR20251103-6P2LM','2025-11-03 13:42:47'),(9,'Jonalyn Gujol','INSERT','Jonalyn Gujol created a new vehicle request with tracking ID TRK-VR20251103-NZTW1','2025-11-03 13:43:40'),(10,'Jonalyn Gujol','INSERT','Jonalyn Gujol created a new vehicle request with tracking ID TRK-VR20251103-OR9NM','2025-11-03 13:47:50'),(11,'Jonalyn Gujol','INSERT','Jonalyn Gujol created a new vehicle request with tracking ID TRK-VR20251103-E2DGA','2025-11-03 13:48:29'),(12,NULL,'INSERT',NULL,'2025-11-03 13:50:13'),(13,'Jonalyn Gujol','INSERT','Jonalyn Gujol created a new vehicle request with tracking ID TRK-VR20251103-SL96C','2025-11-03 13:56:58'),(14,'Jonalyn Gujol','INSERT','Jonalyn Gujol created a new vehicle request with tracking ID TRK-VR20251104-V62Z3','2025-11-04 12:54:50'),(15,'Jonalyn Gujol','INSERT','Jonalyn Gujol created a new vehicle request with tracking ID TRK-VR20251104-90AGC','2025-11-04 13:07:15'),(16,'Jonalyn Gujol','INSERT','Jonalyn Gujol created a new vehicle request with tracking ID TRK-VR20251104-A2DYB','2025-11-04 13:29:43'),(17,'Jonalyn Gujol','INSERT','Jonalyn Gujol created a new vehicle request with tracking ID TRK-VR20251104-8GSXP','2025-11-04 13:34:29'),(18,'Jonalyn Gujol','INSERT','Jonalyn Gujol created a new vehicle request with tracking ID TRK-VR20251104-N2HZB','2025-11-04 13:43:16'),(19,'Jonalyn Gujol','INSERT','Jonalyn Gujol created a new vehicle request with tracking ID TRK-VR20251104-ZPHN2','2025-11-04 13:47:07'),(20,'Jonalyn Gujol','INSERT','Jonalyn Gujol created a new vehicle request with tracking ID TRK-VR20251104-SGUK4','2025-11-04 13:56:25'),(21,'Jonalyn Gujol','INSERT','Jonalyn Gujol created a new vehicle request with tracking ID TRK-VR20251104-SV7D8','2025-11-04 13:59:14'),(22,'Jonalyn Gujol','INSERT','Jonalyn Gujol created a new vehicle request with tracking ID TRK-VR20251104-E9M2V','2025-11-04 14:04:16'),(23,'Jonalyn Gujol','INSERT','Jonalyn Gujol created a new vehicle request with tracking ID TRK-VR20251104-1D80P','2025-11-04 14:07:15'),(24,'Jonalyn Gujol','INSERT','Jonalyn Gujol created a new vehicle request with tracking ID TRK-VR20251104-KEV8Z','2025-11-04 14:08:50'),(25,'Jonalyn Gujol','INSERT','Jonalyn Gujol created a new vehicle request with tracking ID TRK-VR20251104-XSC36','2025-11-04 14:10:05'),(26,'Jonalyn Gujol','INSERT','Jonalyn Gujol created a new vehicle request with tracking ID TRK-VR20251104-4EF6V','2025-11-04 14:12:51'),(27,'Jonalyn Gujol','INSERT','Jonalyn Gujol created a new vehicle request with tracking ID TRK-VR20251104-1T5RJ','2025-11-04 14:14:58'),(28,'Jonalyn Gujol','INSERT','Jonalyn Gujol created a new vehicle request with tracking ID TRK-VR20251104-SOHYM','2025-11-04 14:24:04'),(29,'Jonalyn Gujol','INSERT','Jonalyn Gujol created a new vehicle request with tracking ID TRK-VR20251104-7YDC2','2025-11-04 14:31:07'),(30,'Jonalyn Gujol','INSERT','Jonalyn Gujol created a new vehicle request with tracking ID TRK-VR20251104-EOHAV','2025-11-04 14:37:32');
/*!40000 ALTER TABLE `vehicle_request_audit` ENABLE KEYS */;
UNLOCK TABLES;

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
INSERT INTO `vehicle_request_passengers` VALUES (22,3),(22,4),(23,5),(24,4),(48,3);
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
-- Temporary table structure for view `vw_gsu_personnel_audit`
--

DROP TABLE IF EXISTS `vw_gsu_personnel_audit`;
