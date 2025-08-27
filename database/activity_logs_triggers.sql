-- First, drop existing triggers if they exist
DROP TRIGGER IF EXISTS after_personnel_insert;
DROP TRIGGER IF EXISTS after_personnel_update;
DROP TRIGGER IF EXISTS after_personnel_delete;
DROP TRIGGER IF EXISTS after_materials_insert;
DROP TRIGGER IF EXISTS after_materials_update;
DROP TRIGGER IF EXISTS after_materials_delete;

-- Create activity_logs table if not exists
CREATE TABLE IF NOT EXISTS activity_logs (
    log_id INT PRIMARY KEY AUTO_INCREMENT,
    table_name VARCHAR(50) NOT NULL,
    action_type VARCHAR(20) NOT NULL,
    record_id VARCHAR(50) NOT NULL,
    description TEXT NOT NULL,
    performed_by VARCHAR(100),
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

DELIMITER //

-- After Insert trigger for gsu_personnel
CREATE TRIGGER after_personnel_insert
AFTER INSERT ON gsu_personnel
FOR EACH ROW
BEGIN
    INSERT INTO activity_logs (table_name, action_type, record_id, description)
    VALUES ('gsu_personnel', 'INSERT', NEW.staff_id, 
            CONCAT('New personnel added: ', NEW.firstName, ' ', NEW.lastName, ' (', NEW.department, ')'));
END//

-- After Update trigger for gsu_personnel
CREATE TRIGGER after_personnel_update
AFTER UPDATE ON gsu_personnel
FOR EACH ROW
BEGIN
    SET @changes = '';
    
    IF NEW.firstName != OLD.firstName THEN
        SET @changes = CONCAT(@changes, 'First name changed from "', OLD.firstName, '" to "', NEW.firstName, '". ');
    END IF;
    
    IF NEW.lastName != OLD.lastName THEN
        SET @changes = CONCAT(@changes, 'Last name changed from "', OLD.lastName, '" to "', NEW.lastName, '". ');
    END IF;
    
    IF NEW.department != OLD.department THEN
        SET @changes = CONCAT(@changes, 'Department changed from "', OLD.department, '" to "', NEW.department, '". ');
    END IF;
    
    IF NEW.position != OLD.position THEN
        SET @changes = CONCAT(@changes, 'Position changed from "', OLD.position, '" to "', NEW.position, '". ');
    END IF;

    IF @changes != '' THEN
        INSERT INTO activity_logs (table_name, action_type, record_id, description)
        VALUES ('gsu_personnel', 'UPDATE', NEW.staff_id, 
                CONCAT('Personnel updated: ', NEW.firstName, ' ', NEW.lastName, '. Changes: ', @changes));
    END IF;
END//

-- After Delete trigger for gsu_personnel
CREATE TRIGGER after_personnel_delete
AFTER DELETE ON gsu_personnel
FOR EACH ROW
BEGIN
    INSERT INTO activity_logs (table_name, action_type, record_id, description)
    VALUES ('gsu_personnel', 'DELETE', OLD.staff_id, 
            CONCAT('Personnel deleted: ', OLD.firstName, ' ', OLD.lastName, ' (', OLD.department, ')'));
END//

-- After Insert trigger for materials
CREATE TRIGGER after_materials_insert
AFTER INSERT ON materials
FOR EACH ROW
BEGIN
    INSERT INTO activity_logs (table_name, action_type, record_id, description)
    VALUES ('materials', 'INSERT', NEW.material_id, 
            CONCAT('New material added: ', NEW.material_name, ', Quantity: ', NEW.quantity, ', Unit: ', NEW.unit));
END//

-- After Update trigger for materials
CREATE TRIGGER after_materials_update
AFTER UPDATE ON materials
FOR EACH ROW
BEGIN
    SET @changes = '';
    
    IF NEW.material_name != OLD.material_name THEN
        SET @changes = CONCAT(@changes, 'Name changed from "', OLD.material_name, '" to "', NEW.material_name, '". ');
    END IF;
    
    IF NEW.quantity != OLD.quantity THEN
        SET @changes = CONCAT(@changes, 'Quantity changed from ', OLD.quantity, ' to ', NEW.quantity, '. ');
    END IF;
    
    IF NEW.unit != OLD.unit THEN
        SET @changes = CONCAT(@changes, 'Unit changed from "', OLD.unit, '" to "', NEW.unit, '". ');
    END IF;

    IF @changes != '' THEN
        INSERT INTO activity_logs (table_name, action_type, record_id, description)
        VALUES ('materials', 'UPDATE', NEW.material_id, 
                CONCAT('Material updated: ', NEW.material_name, '. Changes: ', @changes));
    END IF;
END//

-- After Delete trigger for materials
CREATE TRIGGER after_materials_delete
AFTER DELETE ON materials
FOR EACH ROW
BEGIN
    INSERT INTO activity_logs (table_name, action_type, record_id, description)
    VALUES ('materials', 'DELETE', OLD.material_id, 
            CONCAT('Material deleted: ', OLD.material_name, ', Last quantity: ', OLD.quantity, ' ', OLD.unit));
END//

DELIMITER ;

-- Verify triggers are created
SHOW TRIGGERS; 