-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 04, 2025 at 07:02 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `utrms_db`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `spAddGsuPersonnel` (IN `p_staff_id` INT UNSIGNED, IN `p_firstName` VARCHAR(50), IN `p_lastName` VARCHAR(50), IN `p_department` VARCHAR(50), IN `p_contact` INT(11), IN `p_hire_date` DATE, IN `p_unit` VARCHAR(100))   BEGIN
    INSERT INTO GSU_PERSONNEL (
        staff_id, firstName, lastName, department, contact, hire_date, unit
    ) VALUES (
        p_staff_id, p_firstName, p_lastName, p_department, p_contact, p_hire_date, p_unit
    );
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `spAddMaterial` (IN `p_material_code` INT UNSIGNED, IN `p_material_desc` VARCHAR(50), IN `p_qty` INT, IN `p_material_status` VARCHAR(50))   BEGIN
    INSERT INTO MATERIALS (material_code, material_desc, qty, material_status)
    VALUES (p_material_code, p_material_desc, p_qty, p_material_status);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `spAddRequest` (IN `p_tracking_id` VARCHAR(100), IN `p_request_Type` VARCHAR(70), IN `p_req_id` INT UNSIGNED, IN `p_request_desc` VARCHAR(500), IN `p_unit` VARCHAR(50), IN `p_location` VARCHAR(250), IN `p_request_date` DATE, IN `p_image_path` VARCHAR(255))   BEGIN
    INSERT INTO REQUEST(
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
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `spAddRequestAssignment` (IN `p_request_id` INT UNSIGNED, IN `p_req_id` INT UNSIGNED, IN `p_req_status` VARCHAR(25), IN `p_date_finished` DATE)   BEGIN
    DECLARE new_id INT UNSIGNED;

    -- Get the next available ID (assuming manual increment)
    SELECT IFNULL(MAX(reqAssignment_id), 0) + 1 INTO new_id FROM REQUEST_ASSIGNMENT;

    -- Insert into the table
    INSERT INTO REQUEST_ASSIGNMENT (reqAssignment_id, request_id, req_id, req_status, date_finished)
    VALUES (new_id, p_request_id, p_req_id, p_req_status, p_date_finished);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `spAddRequester` (IN `p_requester_id` VARCHAR(50), IN `p_firstName` VARCHAR(50), IN `p_lastName` VARCHAR(50), IN `p_pass` VARCHAR(50), IN `p_email` VARCHAR(100))   BEGIN
    INSERT INTO REQUESTER(
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
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `spAssignPersonnel` (IN `p_request_id` INT, IN `p_staff_id` INT)   BEGIN
    INSERT INTO REQUEST_ASSIGNED_PERSONNEL (request_id, staff_id)
    VALUES (p_request_id, p_staff_id);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `spCheckCurrentPassword` (IN `userEmail` VARCHAR(100), IN `inputPassword` VARCHAR(255), OUT `isValid` BOOLEAN)   BEGIN
    DECLARE dbPassword VARCHAR(255);

    -- Get the user's actual password from the database
    SELECT pass INTO dbPassword
    FROM requester
    WHERE email = userEmail;

    -- Compare input password with stored password (plain text comparison)
    IF dbPassword IS NOT NULL AND dbPassword = inputPassword THEN
        SET isValid = TRUE;
    ELSE
        SET isValid = FALSE;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `spDeleteGsuPersonnel` (IN `p_staff_id` INT)   BEGIN
    DELETE FROM gsu_personnel WHERE staff_id = p_staff_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `spGetAdminPassword` (IN `input_username` VARCHAR(100), OUT `output_pass` TEXT)   BEGIN
    SELECT `pass` INTO output_pass
    FROM `admin`
    WHERE `username` = input_username
    LIMIT 1;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `spGetRequestTrackingByReqIDGrouped` (IN `p_req_id` INT)   BEGIN
    SELECT
        r.tracking_id AS tracking_id,
        r.request_Type AS nature_request,
        r.location AS location,
        ra.req_status AS req_status,
        ra.date_finished AS date_finished,
        r.req_id
    FROM
        REQUEST r
    JOIN
        REQUEST_ASSIGNMENT ra ON r.req_id = ra.req_id
    WHERE
        r.req_id = p_req_id
    GROUP BY
        ra.req_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `spGetUserProfile` (IN `userEmail` VARCHAR(250))   BEGIN
    SELECT requester_id, firstName, lastName, middleInitial, email, officeOrDept
    FROM requester
    WHERE email = userEmail;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `spGetUserProfileWithOutput` (IN `userEmail` VARCHAR(100), OUT `out_id` VARCHAR(50), OUT `out_fname` VARCHAR(50), OUT `out_lname` VARCHAR(50), OUT `out_mname` VARCHAR(5), OUT `out_email` VARCHAR(100), OUT `out_dept` VARCHAR(250), OUT `out_pic` VARCHAR(255))   BEGIN
    SELECT requester_id, firstName, lastName, middleInitial, email, officeOrDept, profile_pic
    INTO out_id, out_fname, out_lname, out_mname, out_email, out_dept, out_pic
    FROM requester
    WHERE email = userEmail;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `spUpdateGsuPersonnel` (IN `p_staff_id` INT UNSIGNED, IN `p_firstName` VARCHAR(50), IN `p_lastName` VARCHAR(50), IN `p_department` VARCHAR(50), IN `p_contact` INT(11), IN `p_hire_date` DATE, IN `p_unit` VARCHAR(100))   BEGIN
    UPDATE GSU_PERSONNEL
    SET
        firstName = p_firstName,
        lastName = p_lastName,
        department = p_department,
        contact = p_contact,
        hire_date = p_hire_date,
        unit = p_unit
    WHERE staff_id = p_staff_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `spUpdateRequestPriorityStatus` (IN `p_request_id` INT, IN `p_priority` VARCHAR(50))   BEGIN
    UPDATE REQUEST_ASSIGNMENT
    SET priority_status = p_priority
    WHERE request_id = p_request_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `spUpdateRequestStatus` (IN `p_request_id` INT, IN `p_status` VARCHAR(25))   BEGIN
    UPDATE REQUEST_ASSIGNMENT
    SET req_status = p_status
    WHERE request_id = p_request_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `spUpdateUserProfile` (IN `userEmail` VARCHAR(100), IN `newMiddleInitial` VARCHAR(5), IN `newDept` VARCHAR(250), IN `newPassword` VARCHAR(50))   BEGIN
    UPDATE requester
    SET 
        middleInitial = newMiddleInitial,
        officeOrDept = newDept,
        pass = newPassword
    WHERE email = userEmail;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `spUpdateUserProfileWithPic` (IN `userEmail` VARCHAR(100), IN `newMiddleInitial` VARCHAR(5), IN `newDept` VARCHAR(250), IN `newPassword` VARCHAR(255), IN `newProfilePic` VARCHAR(255))   BEGIN
    IF newPassword IS NOT NULL THEN
        -- Update all fields including password
        UPDATE requester
        SET 
            middleInitial = newMiddleInitial,
            officeOrDept = newDept,
            pass = newPassword,
            profile_pic = CASE 
                WHEN newProfilePic IS NOT NULL THEN newProfilePic 
                ELSE profile_pic 
            END
        WHERE email = userEmail;
    ELSE
        -- Update fields except password
        UPDATE requester
        SET 
            middleInitial = newMiddleInitial,
            officeOrDept = newDept,
            profile_pic = CASE 
                WHEN newProfilePic IS NOT NULL THEN newProfilePic 
                ELSE profile_pic 
            END
        WHERE email = userEmail;
    END IF;
END$$

--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `fnCheckEmailAndPass` (`p_email` VARCHAR(100), `p_pass` VARCHAR(255)) RETURNS TINYINT(1) DETERMINISTIC READS SQL DATA BEGIN
    DECLARE exists_acc BOOLEAN DEFAULT FALSE;

    SELECT TRUE INTO exists_acc
    FROM REQUESTER
    WHERE email = p_email AND pass = p_pass
    LIMIT 1;

    RETURN exists_acc;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `fnGetRequesterIdByEmail` (`p_email` VARCHAR(100)) RETURNS INT(11) DETERMINISTIC READS SQL DATA BEGIN
    DECLARE requesterId INT;

    SELECT req_id INTO requesterId
    FROM REQUESTER
    WHERE email = p_email
    LIMIT 1;

    RETURN requesterId;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `pass` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `pass`) VALUES
(1, 'admin_gsu', 'bEJwbUhqb1RFczZ1T21aRDBFam5CZz09');

-- --------------------------------------------------------

--
-- Table structure for table `gsu_personnel`
--

CREATE TABLE `gsu_personnel` (
  `staff_id` int(10) UNSIGNED NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `department` varchar(50) DEFAULT NULL,
  `contact` int(11) NOT NULL,
  `hire_date` date DEFAULT NULL,
  `unit` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gsu_personnel`
--

INSERT INTO `gsu_personnel` (`staff_id`, `firstName`, `lastName`, `department`, `contact`, `hire_date`, `unit`) VALUES
(10001, 'Nora', 'Madriaga', 'Janitorial', 987838733, '2023-06-06', 'Mabini Unit'),
(10004, 'Malberta', 'Deligencia', 'Landscaping', 2147483647, '2023-07-07', 'Tagum Unit'),
(10005, 'Cruz', 'Dela', 'Building Repair And Maintenance', 1147483647, '2023-11-19', 'Mabini Unit'),
(10006, 'Roland', 'Nacario', 'Building Repair And Maintenance', 992967447, '2023-11-19', 'Tagum Unit'),
(10007, 'Hile', 'Escote', 'Ground Maintenance', 2147483110, '2023-11-04', 'Mabini Unit'),
(10008, 'Antonio', 'Cabanal', 'Landscaping', 2147483647, '2023-11-04', 'Tagum Unit'),
(10009, 'Rufino', 'Canas', 'Utility', 2111483647, '2022-12-07', 'Tagum Unit'),
(10010, 'Rosalie', 'Fabel', 'Janitorial', 2147483647, '2022-09-20', 'Tagum Unit'),
(10017, 'Grab', 'Bee', 'Utility', 987827661, '2017-02-28', 'Tagum Unit'),
(100011, 'Gar', 'Field', 'Janitorial', 978654312, '2022-02-16', 'Mabini Unit');

--
-- Triggers `gsu_personnel`
--
DELIMITER $$
CREATE TRIGGER `after_gsu_personnel_delete` AFTER DELETE ON `gsu_personnel` FOR EACH ROW BEGIN
    INSERT INTO gsu_personnel_audit (
        staff_id, firstName, lastName, department, contact, hire_date,
        action_type, action_by
    )
    VALUES (
        OLD.staff_id, OLD.firstName, OLD.lastName, OLD.department, OLD.contact, OLD.hire_date,
        'DELETE', USER()
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_gsu_personnel_insert` AFTER INSERT ON `gsu_personnel` FOR EACH ROW BEGIN
    INSERT INTO gsu_personnel_audit (
        staff_id, firstName, lastName, department, contact, hire_date,
        action_type, action_by
    )
    VALUES (
        NEW.staff_id, NEW.firstName, NEW.lastName, NEW.department, NEW.contact, NEW.hire_date,
        'INSERT', USER()
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_gsu_personnel_update` AFTER UPDATE ON `gsu_personnel` FOR EACH ROW BEGIN
    INSERT INTO gsu_personnel_audit (
        staff_id, firstName, lastName, department, contact, hire_date,
        action_type, action_by
    )
    VALUES (
        NEW.staff_id, NEW.firstName, NEW.lastName, NEW.department, NEW.contact, NEW.hire_date,
        'UPDATE', USER()
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `gsu_personnel_audit`
--

CREATE TABLE `gsu_personnel_audit` (
  `audit_id` int(11) NOT NULL,
  `staff_id` int(10) UNSIGNED DEFAULT NULL,
  `firstName` varchar(50) DEFAULT NULL,
  `lastName` varchar(50) DEFAULT NULL,
  `department` varchar(50) DEFAULT NULL,
  `contact` int(11) DEFAULT NULL,
  `hire_date` date DEFAULT NULL,
  `action_type` varchar(20) DEFAULT NULL,
  `action_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `action_by` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gsu_personnel_audit`
--

INSERT INTO `gsu_personnel_audit` (`audit_id`, `staff_id`, `firstName`, `lastName`, `department`, `contact`, `hire_date`, `action_type`, `action_date`, `action_by`) VALUES
(1, 100011, 'Gar', 'Field', 'Janitorial', 978654312, '2022-02-16', 'UPDATE', '2025-05-13 01:43:04', 'root@localhost'),
(2, 10013, 'Jay', 'Fer', 'Janitorial', 2147483647, '2021-06-22', 'DELETE', '2025-05-13 01:46:50', 'root@localhost'),
(3, 10002, 'Ginapearl', 'Arar', 'Janitorial', 2147483647, '2023-03-15', 'DELETE', '2025-05-13 05:11:13', 'root@localhost'),
(4, 100013, 'jona', 'aadobas', 'Utility', 1234566, '2025-05-17', 'INSERT', '2025-05-13 05:14:02', 'root@localhost'),
(5, 10005, 'Cruz', 'Dela', 'Building Repair And Maintenance', 2147483647, '2023-11-19', 'UPDATE', '2025-05-13 06:12:01', 'root@localhost'),
(6, 100013, 'jona', 'aadobas', 'Utility', 1234566, '2025-05-17', 'DELETE', '2025-05-13 06:12:23', 'root@localhost'),
(7, 121212, 'Acasia', 'Hindu', 'Utility', 2147483647, '2025-04-28', 'INSERT', '2025-05-13 06:22:37', 'root@localhost'),
(8, 100291, 'Ginger', 'Tuna', 'Ground Maintenance', 2147483647, '2019-05-06', 'INSERT', '2025-05-13 06:36:43', 'root@localhost'),
(9, 121212, 'Acasia', 'Hindu', 'Utility', 2147483647, '2025-04-28', 'DELETE', '2025-05-13 06:38:24', 'root@localhost'),
(10, 10017, 'Grab', 'Bee', 'Utility', 987827661, '2017-02-28', 'INSERT', '2025-05-13 06:39:08', 'root@localhost'),
(11, 10001, 'Nora', 'Madriaga', 'Utility', 987838733, '2023-06-06', 'UPDATE', '2025-05-22 02:16:07', 'root@localhost'),
(12, 10001, 'Nora', 'Madriaga', 'Building Repair And Maintenance', 987838733, '2023-06-06', 'UPDATE', '2025-05-22 02:16:56', 'root@localhost'),
(13, 10001, 'Nora', 'Madriaga', 'Janitorial', 987838733, '2023-06-06', 'UPDATE', '2025-05-22 02:18:54', 'root@localhost'),
(14, 10012, 'Mingkuy', 'Ming', 'Ground Maintenance', 2147483647, '2021-05-11', 'DELETE', '2025-05-22 02:20:11', 'root@localhost'),
(15, 10003, 'Wendel', 'Vallente', 'Utility', 2147483647, '2023-03-11', 'DELETE', '2025-05-22 02:20:55', 'root@localhost'),
(16, 10004, 'Malberta', 'Deligencia', 'Landscaping', 2147483647, '2023-07-07', 'UPDATE', '2025-05-22 15:10:01', 'root@localhost'),
(17, 10004, 'Malberta', 'Deligencia', 'Landscaping', 2147483647, '2023-07-07', 'UPDATE', '2025-05-22 15:10:38', 'root@localhost'),
(18, 10100, 'Joy', 'Joy', 'Landscaping', 988872468, '2025-04-29', 'INSERT', '2025-05-22 23:53:17', 'root@localhost'),
(19, 10100, 'Joy', 'Joy', 'Landscaping', 988872468, '2025-04-29', 'DELETE', '2025-05-22 23:53:28', 'root@localhost'),
(20, 100291, 'Ginger', 'Tuna', 'Ground Maintenance', 2147483647, '2019-05-06', 'DELETE', '2025-05-23 00:39:16', 'root@localhost'),
(21, 10006, 'Roland', 'Nacario', 'Building Repair And Maintenance', 992967447, '2023-11-19', 'UPDATE', '2025-05-23 00:39:49', 'root@localhost'),
(22, 10004, 'Malberta', 'Deligencia', 'Landscaping', 2147483647, '2023-07-07', 'UPDATE', '2025-05-23 00:40:17', 'root@localhost'),
(23, 10005, 'Cruz', 'Dela', 'Building Repair And Maintenance', 1147483647, '2023-11-19', 'UPDATE', '2025-05-23 00:42:44', 'root@localhost'),
(24, 10007, 'Hile', 'Escote', 'Ground Maintenance', 2147483110, '2023-11-04', 'UPDATE', '2025-05-23 00:43:05', 'root@localhost'),
(25, 10009, 'Rufino', 'Canas', 'Utility', 2111483647, '2022-12-07', 'UPDATE', '2025-05-23 00:43:23', 'root@localhost'),
(26, 10004, 'Malberta', 'Deligencia', 'Landscaping', 2147483647, '2023-07-07', 'UPDATE', '2025-05-23 00:47:28', 'root@localhost');

-- --------------------------------------------------------

--
-- Table structure for table `materials`
--

CREATE TABLE `materials` (
  `material_code` int(10) UNSIGNED NOT NULL,
  `material_desc` varchar(50) NOT NULL,
  `qty` int(11) DEFAULT NULL,
  `material_status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `materials`
--

INSERT INTO `materials` (`material_code`, `material_desc`, `qty`, `material_status`) VALUES
(101, 'Folder', 30, NULL),
(123, 'Cement', 10, NULL),
(321, 'Ballpen', 97, NULL),
(322, 'Pipe', 20, NULL),
(1211, 'Mouse', 45, NULL),
(2131, 'Glass', 20, NULL),
(9090, 'Electrical Tape', 10, NULL),
(9091, 'Screw', 10, NULL),
(10101, 'Paint Green', 5, NULL);

--
-- Triggers `materials`
--
DELIMITER $$
CREATE TRIGGER `after_materials_delete` AFTER DELETE ON `materials` FOR EACH ROW BEGIN
    INSERT INTO materials_audit (
        material_code, material_desc, qty, material_status,
        action_type, action_by
    )
    VALUES (
        OLD.material_code, OLD.material_desc, OLD.qty, OLD.material_status,
        'DELETE', USER()
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_materials_insert` AFTER INSERT ON `materials` FOR EACH ROW BEGIN
    INSERT INTO materials_audit (
        material_code, material_desc, qty, material_status,
        action_type, action_by
    )
    VALUES (
        NEW.material_code, NEW.material_desc, NEW.qty, NEW.material_status,
        'INSERT', USER()
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_materials_update` AFTER UPDATE ON `materials` FOR EACH ROW BEGIN
    INSERT INTO materials_audit (
        material_code, material_desc, qty, material_status,
        action_type, action_by
    )
    VALUES (
        NEW.material_code, NEW.material_desc, NEW.qty, NEW.material_status,
        'UPDATE', USER()
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `materials_audit`
--

CREATE TABLE `materials_audit` (
  `audit_id` int(11) NOT NULL,
  `material_code` int(10) UNSIGNED DEFAULT NULL,
  `material_desc` varchar(50) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `material_status` varchar(50) DEFAULT NULL,
  `action_type` varchar(20) DEFAULT NULL,
  `action_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `action_by` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `materials_audit`
--

INSERT INTO `materials_audit` (`audit_id`, `material_code`, `material_desc`, `qty`, `material_status`, `action_type`, `action_date`, `action_by`) VALUES
(1, 10, 'Brain', 10, NULL, 'DELETE', '2025-05-13 01:43:38', 'root@localhost'),
(2, 101, 'Folder', 20, NULL, 'UPDATE', '2025-05-13 01:49:10', 'root@localhost'),
(3, 2131, 'Glass', 20, NULL, 'INSERT', '2025-05-13 01:50:05', 'root@localhost'),
(4, 77, 'Hatdog', 1020, NULL, 'UPDATE', '2025-05-13 05:52:23', 'root@localhost'),
(5, 77, 'Hatdog', 1020, NULL, 'DELETE', '2025-05-13 14:00:00', 'root@localhost'),
(6, 123, 'CHORIZO', 14, NULL, 'UPDATE', '2025-05-17 02:36:44', 'root@localhost'),
(7, 123, 'CHORIZO', 11, NULL, 'UPDATE', '2025-05-17 02:36:44', 'root@localhost'),
(8, 321, 'Ballpen', 90, NULL, 'UPDATE', '2025-05-17 02:37:30', 'root@localhost'),
(9, 123, 'CHORIZO', 14, NULL, 'UPDATE', '2025-05-17 04:07:29', 'root@localhost'),
(10, 1211, 'Mouse', 49, NULL, 'UPDATE', '2025-05-17 04:07:29', 'root@localhost'),
(11, 1211, 'Mouse', 50, NULL, 'UPDATE', '2025-05-17 04:12:13', 'root@localhost'),
(12, 322, 'Robot', 19, NULL, 'UPDATE', '2025-05-17 04:12:13', 'root@localhost'),
(13, 322, 'Robot', 20, NULL, 'UPDATE', '2025-05-17 04:23:14', 'root@localhost'),
(14, 101, 'Folder', 19, NULL, 'UPDATE', '2025-05-17 04:23:14', 'root@localhost'),
(15, 101, 'Folder', 20, NULL, 'UPDATE', '2025-05-17 04:27:29', 'root@localhost'),
(16, 10101, 'Paint Green', 4, NULL, 'UPDATE', '2025-05-17 04:27:29', 'root@localhost'),
(17, 123, 'CHORIZO', 11, NULL, 'UPDATE', '2025-05-17 04:28:50', 'root@localhost'),
(18, 10101, 'Paint Green', 5, NULL, 'UPDATE', '2025-05-17 05:05:09', 'root@localhost'),
(19, 123, 'CHORIZO', 10, NULL, 'UPDATE', '2025-05-17 05:05:09', 'root@localhost'),
(20, 123, 'CHORIZO', 11, NULL, 'UPDATE', '2025-05-17 05:41:48', 'root@localhost'),
(21, 101, 'Folder', 10, NULL, 'UPDATE', '2025-05-17 05:41:48', 'root@localhost'),
(22, 101, 'Folder', 20, NULL, 'UPDATE', '2025-05-17 05:44:08', 'root@localhost'),
(23, 321, 'Ballpen', 80, NULL, 'UPDATE', '2025-05-17 05:44:08', 'root@localhost'),
(24, 101, 'Folder', 30, NULL, 'UPDATE', '2025-05-18 12:30:47', 'root@localhost'),
(25, 321, 'Ballpen', 90, NULL, 'UPDATE', '2025-05-18 12:30:47', 'root@localhost'),
(26, 321, 'Ballpen', 80, NULL, 'UPDATE', '2025-05-18 12:30:47', 'root@localhost'),
(27, 101, 'Folder', 40, NULL, 'UPDATE', '2025-05-18 12:52:15', 'root@localhost'),
(28, 321, 'Ballpen', 90, NULL, 'UPDATE', '2025-05-18 12:52:15', 'root@localhost'),
(29, 101, 'Folder', 30, NULL, 'UPDATE', '2025-05-18 12:52:15', 'root@localhost'),
(30, 101, 'Folder', 40, NULL, 'UPDATE', '2025-05-18 12:58:01', 'root@localhost'),
(31, 321, 'Ballpen', 100, NULL, 'UPDATE', '2025-05-18 12:58:01', 'root@localhost'),
(32, 321, 'Ballpen', 97, NULL, 'UPDATE', '2025-05-18 12:58:01', 'root@localhost'),
(33, 123, 'CHORIZO', 14, NULL, 'UPDATE', '2025-05-21 10:22:48', 'root@localhost'),
(34, 123, 'CHORIZO', 13, NULL, 'UPDATE', '2025-05-21 10:22:48', 'root@localhost'),
(35, 123, 'CHORIZO', 10, NULL, 'UPDATE', '2025-05-21 10:26:27', 'root@localhost'),
(36, 123, 'CHORIZO', 0, NULL, 'UPDATE', '2025-05-21 10:28:43', 'root@localhost'),
(38, 9091, 'Screw', 10, NULL, 'UPDATE', '2025-05-21 10:36:52', 'root@localhost'),
(39, 101, 'Folder', 30, NULL, 'UPDATE', '2025-05-21 10:36:52', 'root@localhost'),
(40, 9090, 'Laptop', 9, NULL, 'UPDATE', '2025-05-21 10:43:53', 'root@localhost'),
(41, 123, 'CHORIZO', 10, NULL, 'UPDATE', '2025-05-21 10:47:50', 'root@localhost'),
(42, 1211, 'Mouse', 45, NULL, 'UPDATE', '2025-05-22 01:18:29', 'root@localhost'),
(43, 123, 'Cement', 10, NULL, 'UPDATE', '2025-05-22 15:15:55', 'root@localhost'),
(44, 322, 'Pipe', 20, NULL, 'UPDATE', '2025-05-22 15:16:48', 'root@localhost'),
(45, 9090, 'Electrical Tape', 9, NULL, 'UPDATE', '2025-05-22 15:19:34', 'root@localhost'),
(46, 9090, 'Electrical Tape', 10, NULL, 'UPDATE', '2025-05-22 21:48:53', 'root@localhost'),
(47, 4, 'f', 4, NULL, 'INSERT', '2025-05-22 23:46:34', 'root@localhost'),
(48, 4, 'f', 4, NULL, 'DELETE', '2025-05-22 23:46:39', 'root@localhost');

-- --------------------------------------------------------

--
-- Table structure for table `records`
--

CREATE TABLE `records` (
  `id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `filepath` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `request_id` int(10) UNSIGNED NOT NULL,
  `tracking_id` varchar(100) DEFAULT NULL,
  `request_Type` varchar(70) NOT NULL,
  `req_id` int(10) UNSIGNED NOT NULL,
  `request_desc` varchar(500) NOT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `location` varchar(250) NOT NULL,
  `request_date` date DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `request`
--

INSERT INTO `request` (`request_id`, `tracking_id`, `request_Type`, `req_id`, `request_desc`, `unit`, `location`, `request_date`, `image_path`) VALUES
(6, 'TRK-20250512-8ALWJ', 'Air-Condition', 2, 'Ayaw gumana ', 'Tagum Unit', 'Comlab2', '2025-05-12', 'Screenshot 2025-05-10 150955.png'),
(7, 'TRK-20250512-G6MBD', 'Plumbing', 2, 'Baradooo', 'Tagum Unit', 'BLB2', '2025-05-12', 'Screenshot 2025-03-09 114039.png'),
(8, 'TRK-20250512-K1YS9', 'Landscaping', 1, 'Bastapo', 'Tagum Unit', 'PECC', '2025-05-12', 'Screenshot 2025-03-07 211634.png'),
(9, 'TRK-20250513-BIYK1', 'Air-Condition', 1, 'ayaw nya', 'Tagum Unit', 'Comlab2', '2025-05-13', 'IMG20230223180418.jpg'),
(10, 'TRK-20250513-B12TU', 'Carpentry/Masonry', 5, 'aaa', 'Tagum Unit', 'PECC', '2025-05-13', '368678515_812123157371511_7811681712340942776_n (2).jpg'),
(11, 'TRK-20250513-0O9P8', 'Repair', 5, 'baba', 'Tagum Unit', 'SB01', '2025-05-13', '368849275_272784518830996_8753425425369288432_n.jpg'),
(12, 'TRK-20250513-EVS9R', 'Alteration', 6, 'naguba akong selpon dbkjgdkjbfseggkdtuhfskjrshvh naguba akong selpon dbkjgdkjbfseggkdtuhfskjrshvhnaguba akong selpon dbkjgdkjbfseggkdtuhfskjrshvhnaguba akong selpon dbkjgdkjbfseggkdtuhfskjrshvhnaguba akong selpon dbkjgdkjbfseggkdtuhfskjrshvhnaguba akong selpon dbkjgdkjbfseggkdtuhfskjrshvhnaguba akong selpon dbkjgdkjbfseggkdtuhfskjrshvhnaguba akong selpon dbkjgdkjbfseggkdtuhfskjrshvhnaguba akong selpon dbkjgdkjbfseggkdtuhfskjrshvhnaguba akong selpon dbkjgdkjbfseggkdtuhfskjrshvhnaguba akong selpon', 'Tagum Unit', 'pecc', '2025-05-13', 'download.jpg'),
(13, 'TRK-20250513-27K51', 'Air-Condition', 6, 'igang na kkaayu', 'Tagum Unit', 'pecc', '2025-05-13', 'download.jpg'),
(14, 'TRK-20250521-FNJVU', 'Electrical', 1, 'Hihi', 'Tagum Unit', 'PECC GYM - Fitness Room', '2025-05-21', 'IMG20230223180431.jpg'),
(15, 'TRK-20250522-PFAKW', 'Electrical', 8, 'No electricity', 'Tagum Unit', 'SOM/SCIENCE BUILDING - SB 41 Classroom', '2025-05-22', 'Green Minimalist Landscape Quote Desktop Wallpaper.png'),
(16, 'TRK-20250522-Q16TX', 'Carpentry/Masonry', 8, 'Uhm', 'Tagum Unit', 'PECC GYM - PECC-04 Classroom', '2025-05-22', 'Screenshot 2025-04-18 194152.png'),
(17, 'TRK-20250522-CNHEM', 'Air-Condition', 10, 'di mu andar', 'Tagum Unit', 'PECC GYM - Office of Student Affairs and Services (OSAS)', '2025-05-22', 'Screenshot 2025-03-02 104149.png'),
(18, 'TRK-20250522-A680Z', 'Carpentry/Masonry', 1, 'Makapasar Kyoti', 'Tagum Unit', 'PECC GYM - PECC-03 Classroom', '2025-05-22', 'IMG20250520191614.jpg'),
(19, 'TRK-20250523-YH25O', 'Air-Condition', 6, 'Dli mo on ang aircon.', 'Tagum Unit', 'SOM/SCIENCE BUILDING - Conference Room', '2025-05-23', 'broken_aircon.jpg'),
(20, 'TRK-20250523-WCEMG', 'Carpentry/Masonry', 6, 'door knob ', 'Mabini Unit', 'FTC BUILDING - Faculty Club Office', '2025-05-23', 'broken_doorknob.jpg'),
(21, 'TRK-20250523-1H8Y6', 'Air-Condition', 5, 'Muandar pero dli bugnaw', 'Tagum Unit', 'SOM/SCIENCE BUILDING - SOM Library', '2025-05-23', 'broken_aircon.jpg');

--
-- Triggers `request`
--
DELIMITER $$
CREATE TRIGGER `after_request_delete` AFTER DELETE ON `request` FOR EACH ROW BEGIN
    INSERT INTO request_audit (action_type, request_id, request_type, description)
    VALUES ('DELETE', OLD.request_id, OLD.request_Type, CONCAT('Request removed from ', OLD.location));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_request_insert` AFTER INSERT ON `request` FOR EACH ROW BEGIN
    INSERT INTO request_audit (action_type, request_id, request_type, description)
    VALUES ('INSERT', NEW.request_id, NEW.request_Type, CONCAT('New request added at ', NEW.location));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_request_update` AFTER UPDATE ON `request` FOR EACH ROW BEGIN
    INSERT INTO request_audit (action_type, request_id, request_type, description)
    VALUES ('UPDATE', NEW.request_id, NEW.request_Type, CONCAT('Request updated at ', NEW.location));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `requester`
--

CREATE TABLE `requester` (
  `req_id` int(10) UNSIGNED NOT NULL,
  `requester_id` varchar(50) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `middleInitial` varchar(5) DEFAULT NULL,
  `pass` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `officeOrDept` varchar(250) DEFAULT NULL,
  `profile_pic` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `requester`
--

INSERT INTO `requester` (`req_id`, `requester_id`, `firstName`, `lastName`, `middleInitial`, `pass`, `email`, `officeOrDept`, `profile_pic`) VALUES
(1, '2023-00060', 'Jonalyn', 'Gujol', 'A.', 'NmJoVXdoQWZiL2VvQmVBT1FPcWF1UT09', 'jsgujol00060@usep.edu.ph', 'BSED', 'uploads/profile_pics/profile_682f990cf02e0.jpg'),
(2, '1', 'M', 'M', 'M', 'czBJNm5nSVFVKytneStSQitXN2U5dz09', 'abc@usep.edu.ph', 'BSIT', NULL),
(4, '2023-10101', 'Ben', 'Ten', NULL, 'VGVxdm9jZjVKdVpkT2g3Z25ONVBkZz09', 'za@usep.edu.ph', NULL, NULL),
(5, '2023-00081', 'Judilou', 'Gayte', 'bauti', 'cWlGd2F2dkM3dU1WMFZod3Fwa1h3dz09', 'jbgayte00081@usep.edu.ph', NULL, NULL),
(6, '2023-00140', 'kim ', 'canja', NULL, 'RE83NXVCTGpweFpvV0hyemtzUlcrdm50VlpjbSswVkdJVnBEQ2tDRk40WT0=', 'kocanja00140@usep.edu.ph', NULL, NULL),
(7, '2023-000001', 'Lay', 'Zhang', NULL, 'Q1BudjdlR004b0pvNEd2WWJ1M3lTUT09', 'jia@usep.edu.ph', NULL, NULL),
(8, '2023-00313', 'Kim', 'Luayon', '', 'Y2Y4a1ZDKytnM2FOOVVveC83bUFTdz09', 'kmluayon00313@usep.edu.ph', NULL, 'uploads/profile_pics/profile_682f9fd520473.png'),
(9, '2023-00023', 'Kaizer Dredd', 'Millana', NULL, 'RHA4cHppOVh0cnkzT24zU1hCd1Qxdz09', 'kcmillana00023@usep.edu.ph', NULL, NULL),
(10, '2023-00172', 'Beth Sophia', 'Tajale', NULL, 'UVd5Ym1uSzV3cjgvdTBha0VLRnZWUT09', 'bltajale00172@usep.edu.ph', NULL, NULL),
(11, '2023-00470', 'Bfell', 'Adobas', NULL, 'K1A1S08rb1BkYkc4MHZaM2NXWDdudz09', 'baadobas00470@usep.edu.ph', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `request_assigned_personnel`
--

CREATE TABLE `request_assigned_personnel` (
  `assigned_id` int(11) NOT NULL,
  `request_id` int(10) UNSIGNED NOT NULL,
  `staff_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `request_assigned_personnel`
--

INSERT INTO `request_assigned_personnel` (`assigned_id`, `request_id`, `staff_id`) VALUES
(1, 6, 10006),
(2, 7, 10006),
(27, 10, 10001),
(36, 13, 100011),
(40, 14, 10001),
(41, 12, 10004),
(43, 8, 10009),
(44, 18, 100011);

--
-- Triggers `request_assigned_personnel`
--
DELIMITER $$
CREATE TRIGGER `trg_insert_request_assigned_personnel` AFTER INSERT ON `request_assigned_personnel` FOR EACH ROW BEGIN
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
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `request_assigned_personnel_audit`
--

CREATE TABLE `request_assigned_personnel_audit` (
  `audit_id` int(11) NOT NULL,
  `action_type` enum('INSERT') NOT NULL,
  `action_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `request_id` int(10) UNSIGNED DEFAULT NULL,
  `staff_id` int(10) UNSIGNED DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `request_assigned_personnel_audit`
--

INSERT INTO `request_assigned_personnel_audit` (`audit_id`, `action_type`, `action_date`, `request_id`, `staff_id`, `description`) VALUES
(1, 'INSERT', '2025-05-21 13:12:12', 12, 10004, 'Assigned staff ID 10004 to request ID 12'),
(2, 'INSERT', '2025-05-21 13:17:25', 8, 10012, 'Assigned staff ID 10012 to request ID 8'),
(3, 'INSERT', '2025-05-21 13:21:58', 8, 10009, 'Assigned Rufino Canas to request ID 8'),
(4, 'INSERT', '2025-05-23 00:45:19', 18, 100011, 'Assigned Gar Field to request ID 18');

-- --------------------------------------------------------

--
-- Table structure for table `request_assignment`
--

CREATE TABLE `request_assignment` (
  `reqAssignment_id` int(10) UNSIGNED NOT NULL,
  `request_id` int(10) UNSIGNED DEFAULT NULL,
  `req_id` int(10) UNSIGNED DEFAULT NULL,
  `req_status` varchar(25) NOT NULL,
  `date_finished` date DEFAULT NULL,
  `priority_status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `request_assignment`
--

INSERT INTO `request_assignment` (`reqAssignment_id`, `request_id`, `req_id`, `req_status`, `date_finished`, `priority_status`) VALUES
(1, 6, 2, 'Completed', '2025-05-12', NULL),
(2, 7, 2, 'Completed', '2025-05-13', NULL),
(3, 8, 1, 'Completed', '2025-05-22', 'Average'),
(4, 9, 1, 'Completed', '2025-05-13', NULL),
(5, 10, 5, 'Completed', '2025-05-17', 'Low'),
(6, 11, 5, 'Completed', '2025-05-21', 'Average'),
(7, 12, 6, 'In Progress', NULL, 'Low'),
(8, 13, 6, 'Completed', '2025-05-22', 'Average'),
(9, 14, 1, 'In Progress', NULL, 'Average'),
(10, 15, 8, 'Completed', '2025-05-22', NULL),
(11, 16, 8, 'Completed', '2025-05-22', NULL),
(12, 17, 10, 'Completed', '2025-05-22', NULL),
(13, 18, 1, 'In Progress', NULL, 'Low'),
(14, 19, 6, 'To Inspect', NULL, NULL),
(15, 20, 6, 'To Inspect', NULL, NULL),
(16, 21, 5, 'To Inspect', NULL, NULL);

--
-- Triggers `request_assignment`
--
DELIMITER $$
CREATE TRIGGER `after_status_update` AFTER UPDATE ON `request_assignment` FOR EACH ROW BEGIN
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
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `request_audit`
--

CREATE TABLE `request_audit` (
  `audit_id` int(11) NOT NULL,
  `action_type` enum('INSERT','UPDATE','DELETE') NOT NULL,
  `action_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `request_id` int(10) UNSIGNED DEFAULT NULL,
  `request_type` varchar(70) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `request_audit`
--

INSERT INTO `request_audit` (`audit_id`, `action_type`, `action_date`, `request_id`, `request_type`, `description`) VALUES
(1, 'INSERT', '2025-05-21 12:40:24', 14, 'Electrical', 'New request added at PECC GYM - Fitness Room'),
(2, 'INSERT', '2025-05-22 01:16:44', 15, 'Electrical', 'New request added at SOM/SCIENCE BUILDING - SB 41 Classroom'),
(3, 'INSERT', '2025-05-22 01:28:59', 16, 'Carpentry/Masonry', 'New request added at PECC GYM - PECC-04 Classroom'),
(4, 'INSERT', '2025-05-22 02:01:23', 17, 'Air-Condition', 'New request added at PECC GYM - Office of Student Affairs and Services (OSAS)'),
(5, 'INSERT', '2025-05-22 21:09:56', 18, 'Carpentry/Masonry', 'New request added at PECC GYM - PECC-03 Classroom'),
(6, 'INSERT', '2025-05-23 00:28:17', 19, 'Air-Condition', 'New request added at SOM/SCIENCE BUILDING - Conference Room'),
(7, 'INSERT', '2025-05-23 00:45:29', 20, 'Carpentry/Masonry', 'New request added at FTC BUILDING - Faculty Club Office'),
(8, 'INSERT', '2025-05-23 00:52:33', 21, 'Air-Condition', 'New request added at SOM/SCIENCE BUILDING - SOM Library'),
(9, 'UPDATE', '2025-05-23 01:28:28', 12, 'Alteration', 'Request updated at pecc'),
(10, 'UPDATE', '2025-05-23 01:30:53', 11, 'Repair', 'Request updated at d'),
(11, 'UPDATE', '2025-05-23 01:31:47', 11, 'Repair', 'Request updated at SB01');

-- --------------------------------------------------------

--
-- Table structure for table `request_materials_needed`
--

CREATE TABLE `request_materials_needed` (
  `request_material_id` int(11) NOT NULL,
  `reqAssignment_id` int(10) UNSIGNED NOT NULL,
  `material_code` int(10) UNSIGNED NOT NULL,
  `quantity_needed` int(11) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `request_materials_needed`
--

INSERT INTO `request_materials_needed` (`request_material_id`, `reqAssignment_id`, `material_code`, `quantity_needed`, `date_added`) VALUES
(6, 5, 321, 10, '2025-05-17 02:37:30'),
(11, 8, 123, 3, '2025-05-17 04:28:50'),
(13, 3, 101, 10, '2025-05-17 05:41:48'),
(14, 3, 321, 10, '2025-05-17 05:44:08'),
(15, 3, 321, 10, '2025-05-18 12:30:47'),
(16, 3, 101, 10, '2025-05-18 12:52:15'),
(17, 3, 321, 3, '2025-05-18 12:58:01'),
(18, 8, 123, 1, '2025-05-21 10:22:48'),
(19, 8, 123, 3, '2025-05-21 10:26:27'),
(20, 8, 123, 10, '2025-05-21 10:28:43'),
(22, 6, 9091, 10, '2025-05-21 10:36:52'),
(23, 6, 101, 10, '2025-05-21 10:36:52'),
(24, 6, 9090, 1, '2025-05-21 10:43:53'),
(25, 9, 1211, 5, '2025-05-22 01:18:29');

-- --------------------------------------------------------

--
-- Table structure for table `status_audit`
--

CREATE TABLE `status_audit` (
  `audit_id` int(11) NOT NULL,
  `action_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `request_id` int(10) UNSIGNED DEFAULT NULL,
  `reqAssignment_id` int(11) DEFAULT NULL,
  `old_status` varchar(50) DEFAULT NULL,
  `new_status` varchar(50) DEFAULT NULL,
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `status_audit`
--

INSERT INTO `status_audit` (`audit_id`, `action_date`, `request_id`, `reqAssignment_id`, `old_status`, `new_status`, `remarks`) VALUES
(1, '2025-05-21 12:52:51', 14, 9, 'To Inspect', 'In Progress', 'Status changed from To Inspect to In Progress'),
(2, '2025-05-21 13:12:12', 12, 7, 'To Inspect', 'In Progress', 'Status changed from To Inspect to In Progress'),
(3, '2025-05-21 13:17:25', 8, 3, 'To Inspect', 'In Progress', 'Status changed from To Inspect to In Progress'),
(4, '2025-05-21 13:17:58', 8, 3, 'In Progress', 'To Inspect', 'Status changed from In Progress to To Inspect'),
(5, '2025-05-21 13:21:58', 8, 3, 'To Inspect', 'In Progress', 'Status changed from To Inspect to In Progress'),
(6, '2025-05-22 01:29:12', 15, 10, 'To Inspect', 'Completed', 'Status changed from To Inspect to Completed'),
(7, '2025-05-22 02:10:59', 16, 11, 'To Inspect', 'Completed', 'Status changed from To Inspect to Completed'),
(8, '2025-05-22 02:12:20', 17, 12, 'To Inspect', 'Completed', 'Status changed from To Inspect to Completed'),
(9, '2025-05-22 02:13:14', 8, 3, 'In Progress', 'To Inspect', 'Status changed from In Progress to To Inspect'),
(10, '2025-05-22 02:13:29', 13, 8, 'In Progress', 'Completed', 'Status changed from In Progress to Completed'),
(11, '2025-05-22 02:21:02', 8, 3, 'To Inspect', 'Completed', 'Status changed from To Inspect to Completed'),
(12, '2025-05-23 00:45:19', 18, 13, 'To Inspect', 'In Progress', 'Status changed from To Inspect to In Progress');

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_completerequests`
-- (See below for the actual view)
--
CREATE TABLE `vw_completerequests` (
`request_id` int(10) unsigned
,`Name` varchar(101)
,`request_Type` varchar(70)
,`location` varchar(250)
,`request_date` date
,`req_status` varchar(25)
,`date_finished` date
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_dashboardreq`
-- (See below for the actual view)
--
CREATE TABLE `vw_dashboardreq` (
`request_id` int(10) unsigned
,`full_name` varchar(101)
,`request_Type` varchar(70)
,`location` varchar(250)
,`req_status` varchar(25)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_dashboardrequest`
-- (See below for the actual view)
--
CREATE TABLE `vw_dashboardrequest` (
`request_id` int(10) unsigned
,`full_name` varchar(101)
,`request_Type` varchar(70)
,`location` varchar(250)
,`req_status` varchar(25)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_gsu_personnel`
-- (See below for the actual view)
--
CREATE TABLE `vw_gsu_personnel` (
`staff_id` int(10) unsigned
,`full_name` varchar(101)
,`department` varchar(50)
,`contact` int(11)
,`hire_date` date
,`unit` varchar(100)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_gsu_personnel_audit`
-- (See below for the actual view)
--
CREATE TABLE `vw_gsu_personnel_audit` (
`audit_id` int(11)
,`staff_id` int(10) unsigned
,`full_name` varchar(101)
,`department` varchar(50)
,`contact` int(11)
,`hire_date` date
,`action_type` varchar(20)
,`action_date` timestamp
,`action_by` varchar(50)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_materials`
-- (See below for the actual view)
--
CREATE TABLE `vw_materials` (
`material_code` int(10) unsigned
,`material_desc` varchar(50)
,`qty` int(11)
,`material_status` varchar(50)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_materials_audit`
-- (See below for the actual view)
--
CREATE TABLE `vw_materials_audit` (
`audit_id` int(11)
,`material_code` int(10) unsigned
,`material_desc` varchar(50)
,`qty` int(11)
,`material_status` varchar(50)
,`action_type` varchar(20)
,`action_date` timestamp
,`action_by` varchar(50)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_requesters`
-- (See below for the actual view)
--
CREATE TABLE `vw_requesters` (
`requester_id` varchar(50)
,`firstName` varchar(50)
,`lastName` varchar(50)
,`middleInitial` varchar(5)
,`email` varchar(100)
,`officeOrDept` varchar(250)
,`profile_pic` varchar(255)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_requests`
-- (See below for the actual view)
--
CREATE TABLE `vw_requests` (
`request_id` int(10) unsigned
,`Name` varchar(101)
,`request_Type` varchar(70)
,`location` varchar(250)
,`request_date` date
,`req_status` varchar(25)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_rqtrack`
-- (See below for the actual view)
--
CREATE TABLE `vw_rqtrack` (
`tracking_id` varchar(100)
,`nature_request` varchar(70)
,`location` varchar(250)
,`req_status` varchar(25)
,`date_finished` date
,`req_id` int(10) unsigned
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_useraccount`
-- (See below for the actual view)
--
CREATE TABLE `vw_useraccount` (
`requester_id` varchar(50)
,`full_name` varchar(101)
,`email` varchar(100)
,`officeOrDept` varchar(250)
,`status` varchar(18)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_work_history`
-- (See below for the actual view)
--
CREATE TABLE `vw_work_history` (
`staff_id` int(10) unsigned
,`request_id` int(10) unsigned
,`request_Type` varchar(70)
,`date_finished` date
);

-- --------------------------------------------------------

--
-- Structure for view `vw_completerequests`
--
DROP TABLE IF EXISTS `vw_completerequests`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_completerequests`  AS SELECT `r`.`request_id` AS `request_id`, concat(`req`.`firstName`,' ',`req`.`lastName`) AS `Name`, `r`.`request_Type` AS `request_Type`, `r`.`location` AS `location`, `r`.`request_date` AS `request_date`, `ra`.`req_status` AS `req_status`, `ra`.`date_finished` AS `date_finished` FROM ((`request` `r` join `requester` `req` on(`r`.`req_id` = `req`.`req_id`)) join `request_assignment` `ra` on(`r`.`request_id` = `ra`.`request_id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `vw_dashboardreq`
--
DROP TABLE IF EXISTS `vw_dashboardreq`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_dashboardreq`  AS SELECT `r`.`request_id` AS `request_id`, concat(`rt`.`firstName`,' ',`rt`.`lastName`) AS `full_name`, `r`.`request_Type` AS `request_Type`, `r`.`location` AS `location`, `ra`.`req_status` AS `req_status` FROM ((`request` `r` join `requester` `rt` on(`r`.`req_id` = `rt`.`req_id`)) join `request_assignment` `ra` on(`r`.`req_id` = `ra`.`req_id`)) GROUP BY `r`.`request_id`, concat(`rt`.`firstName`,' ',`rt`.`lastName`), `r`.`request_Type`, `r`.`location`, `ra`.`req_status` ;

-- --------------------------------------------------------

--
-- Structure for view `vw_dashboardrequest`
--
DROP TABLE IF EXISTS `vw_dashboardrequest`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_dashboardrequest`  AS SELECT `r`.`request_id` AS `request_id`, concat(`rt`.`firstName`,' ',`rt`.`lastName`) AS `full_name`, `r`.`request_Type` AS `request_Type`, `r`.`location` AS `location`, `ra`.`req_status` AS `req_status` FROM ((`request` `r` join `requester` `rt` on(`r`.`req_id` = `rt`.`req_id`)) join `request_assignment` `ra` on(`r`.`request_id` = `ra`.`request_id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `vw_gsu_personnel`
--
DROP TABLE IF EXISTS `vw_gsu_personnel`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_gsu_personnel`  AS SELECT `gsu_personnel`.`staff_id` AS `staff_id`, concat(`gsu_personnel`.`firstName`,' ',`gsu_personnel`.`lastName`) AS `full_name`, `gsu_personnel`.`department` AS `department`, `gsu_personnel`.`contact` AS `contact`, `gsu_personnel`.`hire_date` AS `hire_date`, `gsu_personnel`.`unit` AS `unit` FROM `gsu_personnel` ;

-- --------------------------------------------------------

--
-- Structure for view `vw_gsu_personnel_audit`
--
DROP TABLE IF EXISTS `vw_gsu_personnel_audit`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_gsu_personnel_audit`  AS SELECT `gsu_personnel_audit`.`audit_id` AS `audit_id`, `gsu_personnel_audit`.`staff_id` AS `staff_id`, concat(`gsu_personnel_audit`.`firstName`,' ',`gsu_personnel_audit`.`lastName`) AS `full_name`, `gsu_personnel_audit`.`department` AS `department`, `gsu_personnel_audit`.`contact` AS `contact`, `gsu_personnel_audit`.`hire_date` AS `hire_date`, `gsu_personnel_audit`.`action_type` AS `action_type`, `gsu_personnel_audit`.`action_date` AS `action_date`, `gsu_personnel_audit`.`action_by` AS `action_by` FROM `gsu_personnel_audit` ORDER BY `gsu_personnel_audit`.`action_date` DESC ;

-- --------------------------------------------------------

--
-- Structure for view `vw_materials`
--
DROP TABLE IF EXISTS `vw_materials`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_materials`  AS SELECT `materials`.`material_code` AS `material_code`, `materials`.`material_desc` AS `material_desc`, `materials`.`qty` AS `qty`, `materials`.`material_status` AS `material_status` FROM `materials` ;

-- --------------------------------------------------------

--
-- Structure for view `vw_materials_audit`
--
DROP TABLE IF EXISTS `vw_materials_audit`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_materials_audit`  AS SELECT `materials_audit`.`audit_id` AS `audit_id`, `materials_audit`.`material_code` AS `material_code`, `materials_audit`.`material_desc` AS `material_desc`, `materials_audit`.`qty` AS `qty`, `materials_audit`.`material_status` AS `material_status`, `materials_audit`.`action_type` AS `action_type`, `materials_audit`.`action_date` AS `action_date`, `materials_audit`.`action_by` AS `action_by` FROM `materials_audit` ORDER BY `materials_audit`.`action_date` DESC ;

-- --------------------------------------------------------

--
-- Structure for view `vw_requesters`
--
DROP TABLE IF EXISTS `vw_requesters`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_requesters`  AS SELECT `requester`.`requester_id` AS `requester_id`, `requester`.`firstName` AS `firstName`, `requester`.`lastName` AS `lastName`, `requester`.`middleInitial` AS `middleInitial`, `requester`.`email` AS `email`, `requester`.`officeOrDept` AS `officeOrDept`, `requester`.`profile_pic` AS `profile_pic` FROM `requester` ;

-- --------------------------------------------------------

--
-- Structure for view `vw_requests`
--
DROP TABLE IF EXISTS `vw_requests`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_requests`  AS SELECT `r`.`request_id` AS `request_id`, concat(`req`.`firstName`,' ',`req`.`lastName`) AS `Name`, `r`.`request_Type` AS `request_Type`, `r`.`location` AS `location`, `r`.`request_date` AS `request_date`, `ra`.`req_status` AS `req_status` FROM ((`request` `r` join `requester` `req` on(`r`.`req_id` = `req`.`req_id`)) join `request_assignment` `ra` on(`r`.`request_id` = `ra`.`request_id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `vw_rqtrack`
--
DROP TABLE IF EXISTS `vw_rqtrack`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_rqtrack`  AS SELECT `r`.`tracking_id` AS `tracking_id`, `r`.`request_Type` AS `nature_request`, `r`.`location` AS `location`, `ra`.`req_status` AS `req_status`, `ra`.`date_finished` AS `date_finished`, `r`.`req_id` AS `req_id` FROM (`request` `r` join `request_assignment` `ra` on(`r`.`req_id` = `ra`.`req_id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `vw_useraccount`
--
DROP TABLE IF EXISTS `vw_useraccount`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_useraccount`  AS SELECT `rt`.`requester_id` AS `requester_id`, concat(`rt`.`firstName`,' ',`rt`.`lastName`) AS `full_name`, `rt`.`email` AS `email`, `rt`.`officeOrDept` AS `officeOrDept`, CASE WHEN sum(case when `ra`.`req_status` is null then 0 when lcase(`ra`.`req_status`) in ('complete','completed') then 0 else 1 end) > 0 THEN 'Pending Request' ELSE 'No Pending Request' END AS `status` FROM ((`requester` `rt` left join `request` `r` on(`rt`.`req_id` = `r`.`req_id`)) left join `request_assignment` `ra` on(`rt`.`req_id` = `ra`.`req_id`)) GROUP BY `rt`.`requester_id`, `rt`.`firstName`, `rt`.`lastName`, `rt`.`email`, `rt`.`officeOrDept` ;

-- --------------------------------------------------------

--
-- Structure for view `vw_work_history`
--
DROP TABLE IF EXISTS `vw_work_history`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_work_history`  AS SELECT `rap`.`staff_id` AS `staff_id`, `rap`.`request_id` AS `request_id`, `r`.`request_Type` AS `request_Type`, `ra`.`date_finished` AS `date_finished` FROM ((`request_assigned_personnel` `rap` join `request` `r` on(`rap`.`request_id` = `r`.`request_id`)) left join `request_assignment` `ra` on(`rap`.`request_id` = `ra`.`request_id`)) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gsu_personnel`
--
ALTER TABLE `gsu_personnel`
  ADD PRIMARY KEY (`staff_id`);

--
-- Indexes for table `gsu_personnel_audit`
--
ALTER TABLE `gsu_personnel_audit`
  ADD PRIMARY KEY (`audit_id`);

--
-- Indexes for table `materials`
--
ALTER TABLE `materials`
  ADD PRIMARY KEY (`material_code`),
  ADD UNIQUE KEY `material_code` (`material_code`);

--
-- Indexes for table `materials_audit`
--
ALTER TABLE `materials_audit`
  ADD PRIMARY KEY (`audit_id`);

--
-- Indexes for table `records`
--
ALTER TABLE `records`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`request_id`),
  ADD UNIQUE KEY `tracking_id` (`tracking_id`),
  ADD KEY `req_id` (`req_id`);

--
-- Indexes for table `requester`
--
ALTER TABLE `requester`
  ADD PRIMARY KEY (`req_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `request_assigned_personnel`
--
ALTER TABLE `request_assigned_personnel`
  ADD PRIMARY KEY (`assigned_id`),
  ADD KEY `request_id` (`request_id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `request_assigned_personnel_audit`
--
ALTER TABLE `request_assigned_personnel_audit`
  ADD PRIMARY KEY (`audit_id`);

--
-- Indexes for table `request_assignment`
--
ALTER TABLE `request_assignment`
  ADD PRIMARY KEY (`reqAssignment_id`),
  ADD KEY `request_id` (`request_id`),
  ADD KEY `req_id` (`req_id`);

--
-- Indexes for table `request_audit`
--
ALTER TABLE `request_audit`
  ADD PRIMARY KEY (`audit_id`),
  ADD KEY `request_id` (`request_id`);

--
-- Indexes for table `request_materials_needed`
--
ALTER TABLE `request_materials_needed`
  ADD PRIMARY KEY (`request_material_id`),
  ADD KEY `reqAssignment_id` (`reqAssignment_id`),
  ADD KEY `material_code` (`material_code`);

--
-- Indexes for table `status_audit`
--
ALTER TABLE `status_audit`
  ADD PRIMARY KEY (`audit_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `gsu_personnel_audit`
--
ALTER TABLE `gsu_personnel_audit`
  MODIFY `audit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `materials_audit`
--
ALTER TABLE `materials_audit`
  MODIFY `audit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `records`
--
ALTER TABLE `records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `request`
--
ALTER TABLE `request`
  MODIFY `request_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `requester`
--
ALTER TABLE `requester`
  MODIFY `req_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `request_assigned_personnel`
--
ALTER TABLE `request_assigned_personnel`
  MODIFY `assigned_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `request_assigned_personnel_audit`
--
ALTER TABLE `request_assigned_personnel_audit`
  MODIFY `audit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `request_audit`
--
ALTER TABLE `request_audit`
  MODIFY `audit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `request_materials_needed`
--
ALTER TABLE `request_materials_needed`
  MODIFY `request_material_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `status_audit`
--
ALTER TABLE `status_audit`
  MODIFY `audit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `request`
--
ALTER TABLE `request`
  ADD CONSTRAINT `request_ibfk_1` FOREIGN KEY (`req_id`) REFERENCES `requester` (`req_id`) ON DELETE CASCADE;

--
-- Constraints for table `request_assigned_personnel`
--
ALTER TABLE `request_assigned_personnel`
  ADD CONSTRAINT `request_assigned_personnel_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `request` (`request_id`),
  ADD CONSTRAINT `request_assigned_personnel_ibfk_2` FOREIGN KEY (`staff_id`) REFERENCES `gsu_personnel` (`staff_id`);

--
-- Constraints for table `request_assignment`
--
ALTER TABLE `request_assignment`
  ADD CONSTRAINT `request_assignment_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `request` (`request_id`),
  ADD CONSTRAINT `request_assignment_ibfk_2` FOREIGN KEY (`req_id`) REFERENCES `requester` (`req_id`);

--
-- Constraints for table `request_audit`
--
ALTER TABLE `request_audit`
  ADD CONSTRAINT `request_audit_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `request` (`request_id`) ON DELETE SET NULL;

--
-- Constraints for table `request_materials_needed`
--
ALTER TABLE `request_materials_needed`
  ADD CONSTRAINT `request_materials_needed_ibfk_1` FOREIGN KEY (`reqAssignment_id`) REFERENCES `request_assignment` (`reqAssignment_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `request_materials_needed_ibfk_2` FOREIGN KEY (`material_code`) REFERENCES `materials` (`material_code`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
