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
INSERT INTO `administrator` VALUES ('2021-00001','gsuadmin@usep.edu.ph','Gsu','Adimen','09123456781',2,'K3hWNDZtTlYwRmpwMHlYdjBvWEFPQT09','/uploads/profile_pics/images (2)_1762329999.png','Active'),('2023-00002','motorpooladmin@usep.edu.ph','Motorpool','Adimen','09123456780',3,'R0kvQnhlQ3MydjNFZHovQlR1V0VOQT09','Plankton-Spongebob-Series-Iconic-Villain-PNG-thumb.png','Active'),('2023-00062','superadmin@usep.edu.ph','Super','Admin','',1,'Y2Y4a1ZDKytnM2FOOVVveC83bUFTdz09','','Active');
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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `administrator_audit`
--

LOCK TABLES `administrator_audit` WRITE;
/*!40000 ALTER TABLE `administrator_audit` DISABLE KEYS */;
INSERT INTO `administrator_audit` VALUES (1,'2023-000111','Testing One','INSERT','Testing One added administrator: Testing One','2025-10-24 15:08:52'),(2,'2023-000111','Testing Two','UPDATE','Testing Two updated administrator: Name from \"Testing One\" to \"Testing Two\"; ','2025-10-24 15:51:08'),(3,'2023-00002','Motorpool Adimen','UPDATE','Motorpool Adimen updated administrator: Access Level changed; ','2025-10-30 12:02:20'),(4,'2021-00001','Gsu Adimen','UPDATE','Gsu Adimen updated administrator: Access Level changed; ','2025-10-30 12:02:27'),(5,'2023-00062','Super Admin','UPDATE','Super Admin updated administrator: Access Level changed; ','2025-10-30 12:02:53'),(6,'2023-000111','Testing Two','UPDATE','Testing Two updated administrator: Access Level changed; ','2025-10-30 12:05:33'),(7,'2023-000111','Testing Two','UPDATE','Testing Two updated administrator: Access Level changed; ','2025-10-30 12:05:39'),(8,'2021-00001','Gsu Adimen','UPDATE','Gsu Adimen updated administrator: Access Level changed; ','2025-10-31 07:08:15'),(9,'2023-00002','Motorpool Adimen','UPDATE','Motorpool Adimen updated administrator: Access Level changed; ','2025-10-31 07:08:26'),(10,'2023-00062','Super Admin','UPDATE','Super Admin updated administrator: Access Level changed; ','2025-10-31 07:08:36'),(11,'2023-000111','Testing Two','DELETE','Testing Two was deleted.','2025-10-31 07:08:41'),(12,'2023-00002','Motorpool Adimen','UPDATE','Motorpool Adimen updated administrator: Access Level changed; ','2025-10-31 07:18:53'),(13,'2023-00002','Motorpool Adimen','UPDATE','Motorpool Adimen updated administrator: Access Level changed; ','2025-10-31 07:19:10'),(14,'2023-00002','Motorpool Adimen','UPDATE','Motorpool Adimen updated administrator: Access Level changed; ','2025-10-31 07:21:42'),(15,'2021-00001','Gsu Adimen','UPDATE','Gsu Adimen updated administrator: ','2025-11-05 08:06:40');
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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `campus_locations`
--

LOCK TABLES `campus_locations` WRITE;
/*!40000 ALTER TABLE `campus_locations` DISABLE KEYS */;
INSERT INTO `campus_locations` VALUES (1,'Tagum Unit','PECC - Physical Education Cultural Center','Clinic','2025-10-23 17:02:44'),(2,'Tagum Unit','PECC - Physical Education Cultural Center','Office of Registrar (OUR)','2025-10-23 17:03:34'),(3,'Tagum Unit','SOM - School of Medicine','Dean\'s Office','2025-10-24 08:04:28'),(7,'Tagum Unit','SOM - School of Medicine','SB-05','2025-10-24 16:05:32'),(8,'Tagum Unit','Admin Building','GSU Office','2025-10-31 14:00:09'),(9,'Tagum Unit','Admin Building','Comlab 3','2025-11-09 07:04:15'),(10,'Tagum Unit','PECC - Physical Education Cultural Center','Registar','2025-11-09 07:04:35'),(11,'Tagum Unit','SOM - School of Medicine','Conference Room','2025-11-09 07:05:00'),(12,'Tagum Unit','tawi','tawi','2025-11-09 07:11:06');
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `campus_locations_audit`
--

LOCK TABLES `campus_locations_audit` WRITE;
/*!40000 ALTER TABLE `campus_locations_audit` DISABLE KEYS */;
INSERT INTO `campus_locations_audit` VALUES (1,'Gsu Adimen','INSERT','Gsu Adimen added campus location: Tagum Unit, SOM - School of Medicine, SB-01','2025-10-24 16:05:32'),(2,'Gsu Adimen','UPDATE','Gsu Adimen updated campus location: Exact Location from \"SB-01\" to \"SB-02\"; ','2025-10-24 16:10:00'),(3,'Super Admin','UPDATE','Super Admin updated campus location: Exact Location from \"SB-02\" to \"SB-05\"; ','2025-10-30 01:21:06'),(4,'Gsu Adimen','INSERT','Gsu Adimen added campus location: Tagum Unit, Admin Building, GSU Office','2025-10-31 14:00:09'),(5,'Gsu Adimen','UPDATE','Gsu Adimen updated campus location: Building from \"SOM - School of Medicine\" to \"SOM\"; ','2025-10-31 14:00:36'),(6,'Gsu Adimen','UPDATE','Gsu Adimen updated campus location: Building from \"SOM\" to \"SOM - School of Medicine\"; ','2025-10-31 14:33:10'),(7,'Super Admin','INSERT','Super Admin added campus location: Tagum Unit, Admin Building, Comlab 3','2025-11-09 07:04:15'),(8,'Super Admin','INSERT','Super Admin added campus location: Tagum Unit, PECC - Physical Education Cultural Center, Registar','2025-11-09 07:04:35'),(9,'Super Admin','INSERT','Super Admin added campus location: Tagum Unit, SOM - School of Medicine, Conference Room','2025-11-09 07:05:00'),(10,'Super Admin','INSERT','Super Admin added campus location: Tagum Unit, tawi, tawi','2025-11-09 07:11:06');
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
INSERT INTO `feedback` VALUES (1,'TRK-20251023-V6MEK','{\"0\":5,\"1\":4,\"2\":4,\"3\":5}','{\"0\":3,\"1\":4,\"2\":3,\"3\":4,\"4\":5,\"5\":4,\"6\":3}','{\"0\":5,\"1\":4,\"2\":3,\"3\":5}',4.07,'','','','Testing','2025-10-29 13:45:25'),(2,'TRK-20251030-OMPF7','{\"0\":5,\"1\":4,\"2\":4,\"3\":5}','{\"0\":4,\"1\":3,\"2\":3,\"3\":3,\"4\":5,\"5\":4,\"6\":3}','{\"0\":4,\"1\":5,\"2\":2,\"3\":3}',3.80,'test','testttt','testtttt','123\r\n','2025-11-05 08:05:34');
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
INSERT INTO `gsu_personnel` VALUES (2025,'Test','Staff','Utility','09992037678','2025-10-31','Tagum Unit',NULL),(10001,'Jay','Mentos','Janitorial','09183456789','2025-10-01','Mabini Unit','Chris-Pinkham-2017.xl.jpg');
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `after_gsu_personnel_insert` AFTER INSERT ON `gsu_personnel` FOR EACH ROW BEGIN
    INSERT INTO gsu_personnel_audit (
        staff_id, firstName, lastName, department, contact, hire_date,
        action_type, action_by
    )
    VALUES (
        NEW.staff_id, NEW.firstName, NEW.lastName, NEW.department, NEW.contact, NEW.hire_date,
        'INSERT', USER()
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `after_gsu_personnel_update` AFTER UPDATE ON `gsu_personnel` FOR EACH ROW BEGIN
    INSERT INTO gsu_personnel_audit (
        staff_id, firstName, lastName, department, contact, hire_date,
        action_type, action_by
    )
    VALUES (
        NEW.staff_id, NEW.firstName, NEW.lastName, NEW.department, NEW.contact, NEW.hire_date,
        'UPDATE', USER()
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
  `audit_id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_id` int(10) unsigned NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `department` varchar(50) NOT NULL,
  `contact` varchar(11) NOT NULL,
  `hire_date` date NOT NULL,
  `action_type` varchar(20) NOT NULL,
  `action_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `action_by` varchar(50) NOT NULL,
  PRIMARY KEY (`audit_id`),
  KEY `staff_id` (`staff_id`),
  CONSTRAINT `gsu_personnel_audit_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `gsu_personnel` (`staff_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gsu_personnel_audit`
--

LOCK TABLES `gsu_personnel_audit` WRITE;
/*!40000 ALTER TABLE `gsu_personnel_audit` DISABLE KEYS */;
INSERT INTO `gsu_personnel_audit` VALUES (1,10001,'Jay','Mentos','Janitorial','09183456789','2025-10-01','INSERT','2025-10-23 17:01:19','root@localhost'),(2,2025,'Test','Staff','Utility','09992037678','2025-10-31','INSERT','2025-11-01 08:17:35','root@localhost');
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
INSERT INTO `materials` VALUES (1,'Paint Green',20,'Available'),(2,'Electrical Tape',19,'Available'),(3,'Screw',50,'Available');
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `after_materials_insert` AFTER INSERT ON `materials` FOR EACH ROW BEGIN
    INSERT INTO materials_audit (
        material_code, material_desc, qty, material_status,
        action_type, action_by
    )
    VALUES (
        NEW.material_code, NEW.material_desc, NEW.qty, NEW.material_status,
        'INSERT', USER()
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `after_materials_update` AFTER UPDATE ON `materials` FOR EACH ROW BEGIN
    INSERT INTO materials_audit (
        material_code, material_desc, qty, material_status,
        action_type, action_by
    )
    VALUES (
        NEW.material_code, NEW.material_desc, NEW.qty, NEW.material_status,
        'UPDATE', USER()
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `after_materials_delete` AFTER DELETE ON `materials` FOR EACH ROW BEGIN
    INSERT INTO materials_audit (
        material_code, material_desc, qty, material_status,
        action_type, action_by
    )
    VALUES (
        OLD.material_code, OLD.material_desc, OLD.qty, OLD.material_status,
        'DELETE', USER()
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
  `audit_id` int(11) NOT NULL AUTO_INCREMENT,
  `material_code` int(10) unsigned DEFAULT NULL,
  `material_desc` varchar(50) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `material_status` varchar(50) DEFAULT NULL,
  `action_type` varchar(20) DEFAULT NULL,
  `action_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `action_by` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`audit_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `materials_audit`
--

LOCK TABLES `materials_audit` WRITE;
/*!40000 ALTER TABLE `materials_audit` DISABLE KEYS */;
INSERT INTO `materials_audit` VALUES (1,2,'Electrical Tape',20,'Available','INSERT','2025-10-23 16:58:40','root@localhost'),(2,3,'Screw',50,'Available','INSERT','2025-10-23 16:59:25','root@localhost'),(3,1,'Paint Green',20,'Available','UPDATE','2025-10-23 16:59:38','root@localhost'),(4,2,'Electrical Tape',19,'Available','UPDATE','2025-10-23 17:23:55','root@localhost');
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `passengers`
--

LOCK TABLES `passengers` WRITE;
/*!40000 ALTER TABLE `passengers` DISABLE KEYS */;
INSERT INTO `passengers` VALUES (1,'Riley','Reyes'),(2,'Shine','Reyes'),(3,'Gurly','Reyes'),(4,'Gurly','Gourl'),(5,'Gurly','Gay'),(6,'Jellyn','Omo');
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `request`
--

LOCK TABLES `request` WRITE;
/*!40000 ALTER TABLE `request` DISABLE KEYS */;
INSERT INTO `request` VALUES (1,'TRK-20251023-V6MEK','Electrical',1,'Fire Outlet Po.','Tagum Unit','PECC - Physical Education Cultural Center - Clinic','2025-10-23','fire.jpg'),(2,'TRK-20251023-RK3UN','Electrical',1,'Fire Outlet','Tagum Unit','PECC - Physical Education Cultural Center - Office of Registrar (OUR)','2025-10-23','fire.jpg'),(3,'TRK-20251024-EA3M9','Others',1,'Testing File Upload 5mb Up','Tagum Unit','SOM - School of Medicine - Dean\'s Office','2025-10-24','IMG_20230426_201836.jpg'),(4,'TRK-20251030-OMPF7','Landscaping',4,'And Floor Na Broken','Tagum Unit','SOM - School of Medicine - Dean\'s Office','2025-10-30','3d-robot-hand-background-ai-technology-side-view.jpg'),(5,'TRK-20251030-BMEYN','Welding',4,'Na Fall Ang Uban Bakal From The Ceiling Its So Kulba','Tagum Unit','PECC - Physical Education Cultural Center - Clinic','2025-10-30','Captura-de-pantalla-2025-08-29-081720.png'),(6,'TRK-20251105-N2I4Y','Carpentry/Masonry',4,'123','Tagum Unit','SOM - School of Medicine - Dean\'s Office','2025-11-05','WIN_20250809_16_43_51_Pro.jpg');
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `after_request_insert` AFTER INSERT ON `request` FOR EACH ROW BEGIN
    INSERT INTO request_audit (action_type, request_id, request_type, description)
    VALUES ('INSERT', NEW.request_id, NEW.request_Type, CONCAT('New request added at ', NEW.location));
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `after_request_update` AFTER UPDATE ON `request` FOR EACH ROW BEGIN
    INSERT INTO request_audit (action_type, request_id, request_type, description)
    VALUES ('UPDATE', NEW.request_id, NEW.request_Type, CONCAT('Request updated at ', NEW.location));
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `after_request_delete` AFTER DELETE ON `request` FOR EACH ROW BEGIN
    INSERT INTO request_audit (action_type, request_id, request_type, description)
    VALUES ('DELETE', OLD.request_id, OLD.request_Type, CONCAT('Request removed from ', OLD.location));
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `request_assigned_personnel`
--

LOCK TABLES `request_assigned_personnel` WRITE;
/*!40000 ALTER TABLE `request_assigned_personnel` DISABLE KEYS */;
INSERT INTO `request_assigned_personnel` VALUES (1,1,10001),(2,4,2025),(3,5,2025),(4,6,2025);
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trg_insert_request_assigned_personnel` AFTER INSERT ON `request_assigned_personnel` FOR EACH ROW BEGIN
    DECLARE fname VARCHAR(100);
    DECLARE lname VARCHAR(100);

    -- Get the personnel's name based on staff_id
    SELECT firstName, lastName
    INTO fname, lname
    FROM gsu_personnel
    WHERE staff_id = NEW.staff_id;

    -- Insert into audit table with full name
    INSERT INTO request_assigned_personnel_audit (action_type, request_id, staff_id, description)
    VALUES (
        'INSERT',
        NEW.request_id,
        NEW.staff_id,
        CONCAT('Assigned ', fname, ' ', lname, ' to request ID ', NEW.request_id)
    );
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
  `audit_id` int(11) NOT NULL AUTO_INCREMENT,
  `action_type` enum('INSERT') DEFAULT 'INSERT',
  `action_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `request_id` int(10) unsigned DEFAULT NULL,
  `staff_id` int(10) unsigned DEFAULT NULL,
  `description` text DEFAULT NULL,
  PRIMARY KEY (`audit_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `request_assigned_personnel_audit`
--

LOCK TABLES `request_assigned_personnel_audit` WRITE;
/*!40000 ALTER TABLE `request_assigned_personnel_audit` DISABLE KEYS */;
INSERT INTO `request_assigned_personnel_audit` VALUES (1,'INSERT','2025-10-23 17:23:55',1,10001,'Assigned Jay Mentos to request ID 1'),(2,'INSERT','2025-11-01 08:18:17',4,2025,'Assigned Test Staff to request ID 4'),(3,'INSERT','2025-11-11 07:14:46',5,2025,'Assigned Test Staff to request ID 5'),(4,'INSERT','2025-11-11 07:38:32',6,2025,'Assigned Test Staff to request ID 6');
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `request_assignment`
--

LOCK TABLES `request_assignment` WRITE;
/*!40000 ALTER TABLE `request_assignment` DISABLE KEYS */;
INSERT INTO `request_assignment` VALUES (1,1,1,'Completed','2025-10-29','High'),(2,2,1,'To Inspect',NULL,NULL),(3,3,1,'To Inspect',NULL,NULL),(4,4,4,'Completed','2025-11-05','Low'),(5,5,4,'Completed','2025-11-11','Low'),(6,6,4,'In Progress',NULL,'Low');
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `after_status_update` AFTER UPDATE ON `request_assignment` FOR EACH ROW BEGIN
    -- Only log if the status actually changed
    IF OLD.req_status != NEW.req_status THEN
        INSERT INTO status_audit (request_id, reqAssignment_id, old_status, new_status, remarks)
        VALUES (
            NEW.request_id,
            NEW.reqAssignment_id,
            OLD.req_status,
            NEW.req_status,
            CONCAT('Status changed from ', OLD.req_status, ' to ', NEW.req_status)
        );
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
  `audit_id` int(11) NOT NULL AUTO_INCREMENT,
  `action_type` enum('INSERT','UPDATE','DELETE') DEFAULT NULL,
  `action_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `request_id` int(10) unsigned DEFAULT NULL,
  `request_type` varchar(70) DEFAULT NULL,
  `description` text DEFAULT NULL,
  PRIMARY KEY (`audit_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `request_audit`
--

LOCK TABLES `request_audit` WRITE;
/*!40000 ALTER TABLE `request_audit` DISABLE KEYS */;
INSERT INTO `request_audit` VALUES (1,'INSERT','2025-10-23 17:09:51',1,'Electrical','New request added at PECC - Physical Education Cultural Center - Clinic'),(2,'INSERT','2025-10-23 17:23:13',2,'Electrical','New request added at PECC - Physical Education Cultural Center - Office of Registrar (OUR)'),(3,'INSERT','2025-10-24 08:05:44',3,'Others','New request added at SOM - School of Medicine - Dean\'s Office'),(4,'INSERT','2025-10-30 12:00:03',4,'Landscaping','New request added at SOM - School of Medicine - Dean\'s Office'),(5,'INSERT','2025-10-30 12:26:08',5,'Welding','New request added at PECC - Physical Education Cultural Center - Clinic'),(6,'INSERT','2025-11-05 07:30:50',6,'Carpentry/Masonry','New request added at SOM - School of Medicine - Dean\'s Office');
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
  KEY `fk_materials_needed_assignment` (`reqAssignment_id`),
  KEY `fk_materials_needed_material` (`material_code`),
  CONSTRAINT `fk_materials_needed_assignment` FOREIGN KEY (`reqAssignment_id`) REFERENCES `request_assignment` (`reqAssignment_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_materials_needed_material` FOREIGN KEY (`material_code`) REFERENCES `materials` (`material_code`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `request_materials_needed`
--

LOCK TABLES `request_materials_needed` WRITE;
/*!40000 ALTER TABLE `request_materials_needed` DISABLE KEYS */;
INSERT INTO `request_materials_needed` VALUES (1,1,2,1,'2025-10-23 17:23:55');
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `requester`
--

LOCK TABLES `requester` WRITE;
/*!40000 ALTER TABLE `requester` DISABLE KEYS */;
INSERT INTO `requester` VALUES (1,'2023-00060','Jonalyn','Gujol','09383737381','Y2Y4a1ZDKytnM2FOOVVveC83bUFTdz09','jsgujol00060@usep.edu.ph','BSNED',NULL),(2,'2023-000065','Lay','Zhang',NULL,'Y2Y4a1ZDKytnM2FOOVVveC83bUFTdz09','jia@usep.edu.ph',NULL,NULL),(3,'2020-00011','Testing','One',NULL,'Y2Y4a1ZDKytnM2FOOVVveC83bUFTdz09','testing1@usep.edu.ph',NULL,NULL),(4,'2023-00226','Jellyn','Omo',NULL,'K2MxNHRqaENseTZqSytDU1p4OGltZz09','jmomo00226@usep.edu.ph',NULL,NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `source_of_fund`
--

LOCK TABLES `source_of_fund` WRITE;
/*!40000 ALTER TABLE `source_of_fund` DISABLE KEYS */;
INSERT INTO `source_of_fund` VALUES (4,22,'Donation','Collection','Own Money','Collection'),(5,23,'Donation','Collection','Own Money','Collection'),(6,24,'Donation','Collection','Own Money','Collection');
/*!40000 ALTER TABLE `source_of_fund` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `status_audit`
--

DROP TABLE IF EXISTS `status_audit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `status_audit` (
  `audit_id` int(11) NOT NULL AUTO_INCREMENT,
  `action_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `request_id` int(10) unsigned DEFAULT NULL,
  `reqAssignment_id` int(11) DEFAULT NULL,
  `old_status` varchar(50) DEFAULT NULL,
  `new_status` varchar(50) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  PRIMARY KEY (`audit_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `status_audit`
--

LOCK TABLES `status_audit` WRITE;
/*!40000 ALTER TABLE `status_audit` DISABLE KEYS */;
INSERT INTO `status_audit` VALUES (1,'2025-10-23 17:23:55',1,1,'To Inspect','In Progress','Status changed from To Inspect to In Progress'),(2,'2025-10-29 13:44:21',1,1,'In Progress','Completed','Status changed from In Progress to Completed'),(3,'2025-11-01 08:18:17',4,4,'To Inspect','In Progress','Status changed from To Inspect to In Progress'),(4,'2025-11-05 08:04:36',4,4,'In Progress','Completed','Status changed from In Progress to Completed'),(5,'2025-11-11 07:14:46',5,5,'To Inspect','In Progress','Status changed from To Inspect to In Progress'),(6,'2025-11-11 07:33:04',5,5,'In Progress','Completed','Status changed from In Progress to Completed');
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehicle`
--

LOCK TABLES `vehicle` WRITE;
/*!40000 ALTER TABLE `vehicle` DISABLE KEYS */;
INSERT INTO `vehicle` VALUES (5,'USeP Vehicle - Tagum Unit','VHA991',7,'Sedan',10002,'hilux.jpg','Available'),(6,'USeP Lamborgini','2013ASD',6,'SUV',2023,'car.jpg','Available'),(7,'try','tryy',3,'Van',10002,'Follow @itsfinancialeducation for more..jpg','Available'),(8,'asdfa','adfad',3,'Van',10001,'123913849_983022248870970_108979147330395643_n.jpg','Available'),(9,'dasdasd','sadsad',3,'SUV',10001,'1759221627908~2.jpg','Available'),(10,'qwerty','asdas',2,'Van',2023,'image.png','Available');
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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehicle_audit`
--

LOCK TABLES `vehicle_audit` WRITE;
/*!40000 ALTER TABLE `vehicle_audit` DISABLE KEYS */;
INSERT INTO `vehicle_audit` VALUES (1,'Motorpool Adimen','INSERT','Motorpool Adimen added vehicle: USeP Vehicle - Tagum Unit (Plate: VHA991, Type: Sedan)','2025-10-24 18:13:03'),(3,'Motorpool Adimen','UPDATE','Motorpool Adimen updated vehicle: USeP Bus - Tagum Unit. Changes: Name from \"USeP Bus - Tagum \" to \"USeP Bus - Tagum Unit\"; Capacity from \"60\" to \"61\"; ','2025-10-29 07:05:18'),(4,'Motorpool Adimen','UPDATE','Motorpool Adimen updated vehicle: USeP Van 2 - Tagum Unit. Changes: Photo updated; ','2025-10-29 07:08:53'),(5,'Motorpool Adimen','UPDATE','Motorpool Adimen updated vehicle: USeP Van - Tagum Unit. Changes: Driver changed; ','2025-10-29 08:22:17'),(6,'Motorpool Adimen','INSERT','Motorpool Adimen added vehicle: USeP Lamborgini (Plate: 2013ASD, Type: SUV)','2025-11-04 13:54:04'),(7,NULL,'DELETE',NULL,'2025-11-04 14:30:49'),(8,'Motorpool Adimen','INSERT','Motorpool Adimen added vehicle: try (Plate: tryy, Type: Van)','2025-11-04 14:31:41'),(9,NULL,'DELETE',NULL,'2025-11-04 14:39:34'),(10,NULL,'DELETE',NULL,'2025-11-04 14:39:37'),(11,'Motorpool Adimen','INSERT','Motorpool Adimen added vehicle: asdfa (Plate: adfad, Type: Van)','2025-11-04 15:19:52'),(12,'Motorpool Adimen','INSERT','Motorpool Adimen added vehicle: dasdasd (Plate: sadsad, Type: SUV)','2025-11-04 15:20:18'),(13,'Motorpool Adimen','INSERT','Motorpool Adimen added vehicle: asdadsad (Plate: asdas, Type: Van)','2025-11-04 15:29:45'),(14,'Motorpool Adimen','UPDATE','Motorpool Adimen updated vehicle: qwerty. Changes: Name from \"asdadsad\" to \"qwerty\"; ','2025-11-04 15:30:05');
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
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehicle_request`
--

LOCK TABLES `vehicle_request` WRITE;
/*!40000 ALTER TABLE `vehicle_request` DISABLE KEYS */;
INSERT INTO `vehicle_request` VALUES (22,2,'2025-10-27 21:42:36','TRK-20251027-2OXED','Field Trip To Mabini Unit','Tagum Unit - Mabini Unit','2025-11-07','2025-11-07','05:00:00','17:00:00'),(23,2,'2025-10-28 16:26:18','TRK-20251028-73G56','Field Trip To Obrero Campus','Tagum Unit - Obrero Campus','2025-11-14','2025-11-14','05:00:00','17:00:00'),(24,1,'2025-10-28 16:51:46','TRK-VR20251028-4FG3X','Field Trip To Mintal Campus','Tagum Unit - Mintal Campus','2025-11-25','2025-11-25','05:00:00','17:00:00'),(25,4,'2025-10-30 20:53:42','TRK-VR20251030-K7P38','Training','Manila City','2025-11-26','2025-11-30','20:55:00','23:53:00'),(26,4,'2025-10-30 20:56:37','TRK-VR20251030-F397N','Training','Manila City','2025-11-26','2025-11-30','20:55:00','23:53:00'),(27,4,'2025-10-30 20:56:41','TRK-VR20251030-2UYVJ','Training','Manila City','2025-11-26','2025-11-30','20:55:00','23:53:00'),(28,4,'2025-10-30 20:59:14','TRK-VR20251030-31BTU','Training','Manila City','2025-11-26','2025-11-30','08:00:00','20:02:00'),(29,4,'2025-10-30 21:05:01','TRK-VR20251030-RFZHB','Training','Manila City','2025-11-26','2025-11-30','21:07:00','14:03:00'),(30,4,'2025-10-30 21:07:28','TRK-VR20251030-3WRXG','Training','Manila City','2025-11-26','2025-11-30','21:07:00','21:07:00'),(31,4,'2025-10-30 21:07:35','TRK-VR20251030-50XLC','Training','Manila City','2025-11-26','2025-11-30','21:07:00','21:07:00'),(32,4,'2025-10-30 21:07:54','TRK-VR20251030-DA0ZV','Training','Manila City','2025-11-26','2025-11-30','21:07:00','21:07:00');
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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehicle_request_assignment`
--

LOCK TABLES `vehicle_request_assignment` WRITE;
/*!40000 ALTER TABLE `vehicle_request_assignment` DISABLE KEYS */;
INSERT INTO `vehicle_request_assignment` VALUES (2,22,2,NULL,NULL,'Approved','Dr. Shirley Villanueva','da49e97c9e42f7c7d12e8461a302a27c'),(3,23,2,5,10002,'Approved','Dr. Shirley Villanueva',NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehicle_request_assignment_audit`
--

LOCK TABLES `vehicle_request_assignment_audit` WRITE;
/*!40000 ALTER TABLE `vehicle_request_assignment_audit` DISABLE KEYS */;
INSERT INTO `vehicle_request_assignment_audit` VALUES (1,'Motorpool Adimen','UPDATE','Status changed from \"In Progress\" to \"Approved\"; ','2025-10-30 07:47:57'),(2,'Motorpool Adimen','UPDATE','Status changed from \"Pending\" to \"Approved\"; ','2025-11-05 09:05:31'),(3,'Gsu Adimen','UPDATE','Status changed from \"Pending\" to \"Approved\"; ','2025-11-09 09:46:28');
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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehicle_request_audit`
--

LOCK TABLES `vehicle_request_audit` WRITE;
/*!40000 ALTER TABLE `vehicle_request_audit` DISABLE KEYS */;
INSERT INTO `vehicle_request_audit` VALUES (1,'Jonalyn Gujol','INSERT','Jonalyn Gujol created a new vehicle request with tracking ID TRK-20251025-XKRH9','2025-10-25 04:40:25'),(2,'Lay Zhang','INSERT','Lay Zhang created a new vehicle request with tracking ID TRK-20251027-5N1GR','2025-10-27 13:38:01'),(3,'Lay Zhang','INSERT','Lay Zhang created a new vehicle request with tracking ID TRK-20251027-H761W','2025-10-27 13:39:45'),(4,'Lay Zhang','INSERT','Lay Zhang created a new vehicle request with tracking ID TRK-20251027-2OXED','2025-10-27 13:42:36'),(5,'Lay Zhang','INSERT','Lay Zhang created a new vehicle request with tracking ID TRK-20251028-73G56','2025-10-28 08:26:18'),(6,'Jonalyn Gujol','INSERT','Jonalyn Gujol created a new vehicle request with tracking ID TRK-VR20251028-4FG3X','2025-10-28 08:51:46'),(7,'Jellyn Omo','INSERT','Jellyn Omo created a new vehicle request with tracking ID TRK-VR20251030-K7P38','2025-10-30 12:53:42'),(8,'Jellyn Omo','INSERT','Jellyn Omo created a new vehicle request with tracking ID TRK-VR20251030-F397N','2025-10-30 12:56:37'),(9,'Jellyn Omo','INSERT','Jellyn Omo created a new vehicle request with tracking ID TRK-VR20251030-2UYVJ','2025-10-30 12:56:41'),(10,'Jellyn Omo','INSERT','Jellyn Omo created a new vehicle request with tracking ID TRK-VR20251030-31BTU','2025-10-30 12:59:14'),(11,'Jellyn Omo','INSERT','Jellyn Omo created a new vehicle request with tracking ID TRK-VR20251030-RFZHB','2025-10-30 13:05:01'),(12,'Jellyn Omo','INSERT','Jellyn Omo created a new vehicle request with tracking ID TRK-VR20251030-3WRXG','2025-10-30 13:07:28'),(13,'Jellyn Omo','INSERT','Jellyn Omo created a new vehicle request with tracking ID TRK-VR20251030-50XLC','2025-10-30 13:07:35'),(14,'Jellyn Omo','INSERT','Jellyn Omo created a new vehicle request with tracking ID TRK-VR20251030-DA0ZV','2025-10-30 13:07:54');
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
INSERT INTO `vehicle_request_passengers` VALUES (22,3),(22,4),(23,5),(24,4);
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
/*!50001 DROP VIEW IF EXISTS `vw_gsu_personnel_audit`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vw_gsu_personnel_audit` AS SELECT
 1 AS `audit_id`,
  1 AS `staff_id`,
  1 AS `full_name`,
  1 AS `department`,
  1 AS `contact`,
  1 AS `hire_date`,
  1 AS `action_type`,
  1 AS `action_date`,
  1 AS `action_by` */;
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
-- Temporary table structure for view `vw_materials_audit`
--

DROP TABLE IF EXISTS `vw_materials_audit`;
/*!50001 DROP VIEW IF EXISTS `vw_materials_audit`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vw_materials_audit` AS SELECT
 1 AS `audit_id`,
  1 AS `material_code`,
  1 AS `material_desc`,
  1 AS `qty`,
  1 AS `material_status`,
  1 AS `action_type`,
  1 AS `action_date`,
  1 AS `action_by` */;
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
  1 AS `req_id` */;
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

    -- Check if a requester exists with the given email and password
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

    -- Get the requester ID for the given email
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
    -- Return the new passenger_id
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

    -- Return the newly inserted request ID
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
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `spAddVehicleRequest` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `spAddVehicleRequest`(
    IN p_req_id INT,
    IN p_tracking_id VARCHAR(50),
    IN p_trip_purpose VARCHAR(100),
    IN p_travel_destination VARCHAR(255),
    IN p_travel_date DATE,
    IN p_return_date DATE,
    IN p_departure_time TIME,
    IN p_return_time TIME
)
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

    -- Get the user's actual password from the database
    SELECT pass 
    INTO dbPassword
    FROM requester
    WHERE email = userEmail;

    -- Compare input password with stored password (plain text comparison)
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
    SELECT 
        staff_id,
        email,
        password,
        first_name,
        last_name,
        accessLevel_id,
        profile_picture
    FROM administrator
    WHERE email = input_email;
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
    -- Update the staff assigned to a specific request
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
    -- ✅ Check for duplicate contact (excluding current record)
    IF EXISTS (
        SELECT 1 
        FROM gsu_personnel 
        WHERE contact = p_contact 
        AND staff_id <> p_staff_id
    ) THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Contact number already exists!';
    ELSE
        -- ✅ Update personnel record
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
    -- Update the priority status of a specific request
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
    -- Update request status and set date_finished if completed
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
    -- Assign a passenger to a vehicle request
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
-- Final view structure for view `vw_gsu_personnel_audit`
--

/*!50001 DROP VIEW IF EXISTS `vw_gsu_personnel_audit`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_gsu_personnel_audit` AS select `pa`.`audit_id` AS `audit_id`,`pa`.`staff_id` AS `staff_id`,concat(`pa`.`firstName`,' ',`pa`.`lastName`) AS `full_name`,`pa`.`department` AS `department`,`pa`.`contact` AS `contact`,`pa`.`hire_date` AS `hire_date`,`pa`.`action_type` AS `action_type`,`pa`.`action_date` AS `action_date`,`pa`.`action_by` AS `action_by` from `gsu_personnel_audit` `pa` */;
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
-- Final view structure for view `vw_materials_audit`
--

/*!50001 DROP VIEW IF EXISTS `vw_materials_audit`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_materials_audit` AS select `ma`.`audit_id` AS `audit_id`,`ma`.`material_code` AS `material_code`,`ma`.`material_desc` AS `material_desc`,`ma`.`qty` AS `qty`,`ma`.`material_status` AS `material_status`,`ma`.`action_type` AS `action_type`,`ma`.`action_date` AS `action_date`,`ma`.`action_by` AS `action_by` from `materials_audit` `ma` order by `ma`.`action_date` desc */;
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
/*!50001 VIEW `vw_rqtrack` AS select `r`.`tracking_id` AS `tracking_id`,`r`.`request_Type` AS `request_Type`,`r`.`request_desc` AS `request_desc`,`r`.`location` AS `location`,`ra`.`req_status` AS `req_status`,`ra`.`date_finished` AS `date_finished`,`r`.`req_id` AS `req_id` from ((`request` `r` join `request_assignment` `ra` on(`r`.`request_id` = `ra`.`request_id`)) join `requester` `rq` on(`r`.`req_id` = `rq`.`req_id`)) group by `r`.`req_id`,`r`.`tracking_id` */;
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

-- Dump completed on 2025-11-11 15:44:31
