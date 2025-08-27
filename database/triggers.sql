-- First create audit tables to log changes

-- GSU Personnel Audit Table
CREATE TABLE IF NOT EXISTS gsu_personnel_audit (
    audit_id INT AUTO_INCREMENT PRIMARY KEY,
    staff_id INT UNSIGNED,
    firstName VARCHAR(50),
    lastName VARCHAR(50),
    department VARCHAR(50),
    contact INT(11),
    hire_date DATE,
    action_type VARCHAR(20),
    action_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    action_by VARCHAR(50)
);

-- Materials Audit Table
CREATE TABLE IF NOT EXISTS materials_audit (
    audit_id INT AUTO_INCREMENT PRIMARY KEY,
    material_code INT UNSIGNED,
    material_desc VARCHAR(50),
    qty INT,
    material_status VARCHAR(50),
    action_type VARCHAR(20),
    action_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    action_by VARCHAR(50)
);

-- Request Audit Table
CREATE TABLE IF NOT EXISTS request_audit (
    audit_id INT AUTO_INCREMENT PRIMARY KEY,
    action_type ENUM('INSERT', 'UPDATE', 'DELETE') NOT NULL,
    action_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    request_id INT UNSIGNED,
    request_type VARCHAR(70),
    description TEXT,
    FOREIGN KEY (request_id) REFERENCES request(request_id) ON DELETE SET NULL
);

-- Status Audit Table
CREATE TABLE IF NOT EXISTS status_audit (
    audit_id INT AUTO_INCREMENT PRIMARY KEY,
    action_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    request_id INT UNSIGNED,
    reqAssignment_id INT UNSIGNED,
    old_status VARCHAR(50),
    new_status VARCHAR(50),
    remarks TEXT
);

-- Request Assigned Personnel Audit Table
CREATE TABLE IF NOT EXISTS request_assigned_personnel_audit (
    audit_id INT AUTO_INCREMENT PRIMARY KEY,
    action_type ENUM('INSERT') NOT NULL,
    action_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    request_id INT UNSIGNED,
    staff_id INT UNSIGNED,
    description TEXT
);




-- Triggers for GSU Personnel

DELIMITER //

-- Trigger for INSERT on GSU_PERSONNEL
CREATE TRIGGER after_gsu_personnel_insert
AFTER INSERT ON GSU_PERSONNEL
FOR EACH ROW
BEGIN
    INSERT INTO gsu_personnel_audit (
        staff_id, firstName, lastName, department, contact, hire_date,
        action_type, action_by
    )
    VALUES (
        NEW.staff_id, NEW.firstName, NEW.lastName, NEW.department, NEW.contact, NEW.hire_date,
        'INSERT', USER()
    );
END//

-- Trigger for UPDATE on GSU_PERSONNEL
CREATE TRIGGER after_gsu_personnel_update
AFTER UPDATE ON GSU_PERSONNEL
FOR EACH ROW
BEGIN
    INSERT INTO gsu_personnel_audit (
        staff_id, firstName, lastName, department, contact, hire_date,
        action_type, action_by
    )
    VALUES (
        NEW.staff_id, NEW.firstName, NEW.lastName, NEW.department, NEW.contact, NEW.hire_date,
        'UPDATE', USER()
    );
END//

-- Trigger for DELETE on GSU_PERSONNEL
CREATE TRIGGER after_gsu_personnel_delete
AFTER DELETE ON GSU_PERSONNEL
FOR EACH ROW
BEGIN
    INSERT INTO gsu_personnel_audit (
        staff_id, firstName, lastName, department, contact, hire_date,
        action_type, action_by
    )
    VALUES (
        OLD.staff_id, OLD.firstName, OLD.lastName, OLD.department, OLD.contact, OLD.hire_date,
        'DELETE', USER()
    );
END//

-- Triggers for Materials

-- Trigger for INSERT on MATERIALS
CREATE TRIGGER after_materials_insert
AFTER INSERT ON MATERIALS
FOR EACH ROW
BEGIN
    INSERT INTO materials_audit (
        material_code, material_desc, qty, material_status,
        action_type, action_by
    )
    VALUES (
        NEW.material_code, NEW.material_desc, NEW.qty, NEW.material_status,
        'INSERT', USER()
    );
END//

-- Trigger for UPDATE on MATERIALS
CREATE TRIGGER after_materials_update
AFTER UPDATE ON MATERIALS
FOR EACH ROW
BEGIN
    INSERT INTO materials_audit (
        material_code, material_desc, qty, material_status,
        action_type, action_by
    )
    VALUES (
        NEW.material_code, NEW.material_desc, NEW.qty, NEW.material_status,
        'UPDATE', USER()
    );
END//

-- Trigger for DELETE on MATERIALS
CREATE TRIGGER after_materials_delete
AFTER DELETE ON MATERIALS
FOR EACH ROW
BEGIN
    INSERT INTO materials_audit (
        material_code, material_desc, qty, material_status,
        action_type, action_by
    )
    VALUES (
        OLD.material_code, OLD.material_desc, OLD.qty, OLD.material_status,
        'DELETE', USER()
    );
END//

DELIMITER ;

-- Create a view to easily monitor audit logs
CREATE OR REPLACE VIEW vw_gsu_personnel_audit AS
SELECT 
    audit_id,
    staff_id,
    CONCAT(firstName, ' ', lastName) as full_name,
    department,
    contact,
    hire_date,
    action_type,
    action_date,
    action_by
FROM gsu_personnel_audit
ORDER BY action_date DESC;

CREATE OR REPLACE VIEW vw_materials_audit AS
SELECT 
    audit_id,
    material_code,
    material_desc,
    qty,
    material_status,
    action_type,
    action_date,
    action_by
FROM materials_audit
ORDER BY action_date DESC; 

-- Trigger for INSERT
DELIMITER //
CREATE TRIGGER after_request_insert
AFTER INSERT ON request
FOR EACH ROW
BEGIN
    INSERT INTO request_audit (action_type, request_id, request_type, description)
    VALUES ('INSERT', NEW.request_id, NEW.request_Type, CONCAT('New request added at ', NEW.location));
END;
//
DELIMITER ;

-- Trigger for UPDATE
DELIMITER //
CREATE TRIGGER after_request_update
AFTER UPDATE ON request
FOR EACH ROW
BEGIN
    INSERT INTO request_audit (action_type, request_id, request_type, description)
    VALUES ('UPDATE', NEW.request_id, NEW.request_Type, CONCAT('Request updated at ', NEW.location));
END;
//
DELIMITER ;

-- Trigger for DELETE
DELIMITER //
CREATE TRIGGER after_request_delete
AFTER DELETE ON request
FOR EACH ROW
BEGIN
    INSERT INTO request_audit (action_type, request_id, request_type, description)
    VALUES ('DELETE', OLD.request_id, OLD.request_Type, CONCAT('Request removed from ', OLD.location));
END;
//
DELIMITER ;

-- Trigger for Status Update
DELIMITER //

CREATE TRIGGER after_status_update
AFTER UPDATE ON request_assignment
FOR EACH ROW
BEGIN
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
END;
//

DELIMITER ;

-- Trigger for INSERT on Request Assigned Personnel
DELIMITER $$

CREATE TRIGGER trg_insert_request_assigned_personnel
AFTER INSERT ON request_assigned_personnel
FOR EACH ROW
BEGIN
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
END$$

DELIMITER ;

-- Trigger for INSERT on Uploaded Files
DELIMITER $$

CREATE TRIGGER trg_uploaded_files_insert
AFTER INSERT ON uploaded_files
FOR EACH ROW
BEGIN
    INSERT INTO activity_logs (source, action_type, affected_item, description)
    VALUES (
        'Files',
        'INSERT',
        NEW.filename,
        CONCAT('Uploaded file: ', NEW.filename)
    );
END$$

DELIMITER ;

-- Trigger for DELETE on Uploaded Files
DELIMITER $$

CREATE TRIGGER trg_uploaded_files_delete
AFTER DELETE ON uploaded_files
FOR EACH ROW
BEGIN
    INSERT INTO activity_logs (source, action_type, affected_item, description)
    VALUES (
        'Files',
        'DELETE',
        OLD.filename,
        CONCAT('Deleted file: ', OLD.filename)
    );
END$$

DELIMITER ;

-- Trigger for UPDATE on Uploaded Files
DELIMITER $$

CREATE TRIGGER trg_uploaded_files_update
AFTER UPDATE ON uploaded_files
FOR EACH ROW
BEGIN
    INSERT INTO activity_logs (source, action_type, affected_item, description)
    VALUES (
        'Files',
        'UPDATE',
        NEW.filename,
        CONCAT('Updated file: ', OLD.filename, ' to ', NEW.filename)
    );
END$$

DELIMITER ;




