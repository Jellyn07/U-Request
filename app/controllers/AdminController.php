<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();   // ✅ Start only if not already active
}

require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/encryption.php'; 
require_once __DIR__ . '/../models/AdminModel.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../config/helpers.php';

$action = $_GET['action'] ?? '';

if ($action === 'toggleAdminMenu') {
    $controller = new AdminController();
    $controller->toggleAdminMenu(); // call the toggle function
    exit; // stop further execution
}

$login_error = "";

// ✅ Initialize login attempts if not set
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['lock_time'] = null;
}

// ✅ Check if locked
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['lock_time'] = null;
}

if (isset($_SESSION['lock_time']) && time() < $_SESSION['lock_time']) {
    $remaining = $_SESSION['lock_time'] - time();
    $_SESSION['login_error'] = "Too many failed attempts. Please wait {$remaining} seconds before trying again.";
    header("Location: ../modules/shared/views/admin_login.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signin'])) {
    $email = trim($_POST['email'] ?? '');
    $input_pass = trim($_POST['password'] ?? '');

    $userModel = new UserModel();
    $admin = $userModel->getAdminUserByEmail($email); // this calls the stored procedure

    // Check if admin record exists and procedure returned success
    if ($admin && isset($admin['result']) && $admin['result'] === 'success') {

        if ($userModel->verifyPassword($input_pass, $admin['password'])) {

            // ✅ SUCCESS
            $_SESSION['login_attempts'] = 0;
            $_SESSION['lock_time'] = null;

            $staff_id = $admin['staff_id'];

            // 🔹 Store session info
            $_SESSION['staff_id'] = $staff_id;
            $_SESSION['email'] = $admin['email'];
            $_SESSION['access_level'] = $admin['accessLevel_id'];
            $_SESSION['full_name'] = $admin['first_name'] . ' ' . $admin['last_name'];

            $adminModel = new AdministratorModel();
            $menuAccess = $adminModel->getAdminMenuAccess($staff_id);
            $_SESSION['canSeeAdminManagement'] = ($menuAccess == 1);

            // 🔹 Redirect based on access level
            switch ($admin['accessLevel_id']) {
                case 1:
                    header("Location: ../modules/superadmin/views/dashboard.php");
                    break;
                case 2:
                    header("Location: ../modules/gsu_admin/views/dashboard.php");
                    break;
                case 3:
                    header("Location: ../modules/motorpool_admin/views/dashboard.php");
                    break;
                default:
                    $_SESSION['login_error'] = "Unknown access level.";
                    header("Location: ../modules/shared/views/admin_login.php");
            }
            exit;

        } else {
            // ❌ Invalid password
            $_SESSION['login_attempts']++;
            $_SESSION['login_error'] = "Invalid password. Attempt {$_SESSION['login_attempts']} of 3.";
        }

    } else {
        // ❌ Admin not found or account inactive
        $_SESSION['login_error'] = $admin['error_message'] ?? "Invalid email or password.";

        // Optional: count failed attempts
        $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
        if ($_SESSION['login_attempts'] >= 3) {
            $_SESSION['lock_time'] = time() + 60;
            $_SESSION['login_error'] .= " Login locked for 60 seconds.";
        }
    }

    $_SESSION['old_email'] = $email;
    $_SESSION['old_password'] = $input_pass;
    header("Location: ../modules/shared/views/admin_login.php");
    exit;
}


    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_admin'])) {
    $adminModel = new AdministratorModel(); // ✅ This must be inside the condition

    $staff_id       = $_POST['staff_id'] ?? '';
    $email          = $_POST['email'] ?? '';
    $first_name     = formatName($_POST['first_name'] ?? '');
    $last_name      = formatName($_POST['last_name'] ?? '');
    $contact_no     = $_POST['contact_no'] ?? '';
    $access_level   = $_POST['access_level'] ?? '';
    $password_raw   = $_POST['password'] ?? '';
    $confirm_pass   = $_POST['confirm_password'] ?? '';

    $_SESSION['admin_form_data'] = [
        'staff_id' => $staff_id,
        'email' => $email,
        'first_name' => $first_name,
        'last_name' => $last_name,
        'contact_no' => $contact_no,
        'access_level' => $access_level,
        'password' => $password_raw,
        'confirm_password' => $confirm_pass
    ];

    // ✅ Password validation function
    function isValidPassword($password) {
        return preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/", $password);
        // Must contain:
        // - at least one lowercase letter
        // - at least one uppercase letter
        // - at least one number
        // - at least 8 characters total
    }

    if (!isValidPassword($password_raw)) {
        $_SESSION['admin_error'] = "Password must be at least 8 characters long and contain uppercase, lowercase letters,special character and numbers.";
        header("Location: ../modules/superadmin/views/manage_admin.php");
        exit;
    }

    if ($password_raw !== $confirm_pass) {
        $_SESSION['admin_error'] = "Passwords do not match.";
        header("Location: ../modules/superadmin/views/manage_admin.php");
        exit;
    }

    // $encrypted_pass = encrypt($password_raw);

    // Upload profile picture
    $profile_picture_path = null;

    if (!empty($_FILES['profile_picture']['name']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . "/../../public/uploads/profile_pics";

        // Create directory if not exists
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $filename = basename($_FILES["profile_picture"]["name"]);
        $target_file = $upload_dir . '/' . $filename;

        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
            $profile_picture_path = "/public/uploads/profile_pics/" . $filename;
        } else {
            $_SESSION['admin_error'] = "Failed to upload profile picture.";
            header("Location: ../modules/superadmin/views/manage_admin.php");
            exit;
        }
    }


    // ✅ Now use the model
    $result = $adminModel->addAdministrator(
        $staff_id,
        $email,
        $first_name,
        $last_name,
        $contact_no,
        $access_level,
        $password_raw,
        $filename
    );

    if ($result) {
        $_SESSION['admin_success'] = "Administrator successfully added.";
        header("Location: ../modules/superadmin/views/manage_admin.php");
        exit;
    } else {
        $_SESSION['admin_error'] = $_SESSION['db_error'] ?? "Unknown error occurred.";
        header("Location: ../modules/superadmin/views/manage_admin.php");
        exit;
    }
}

// --- HANDLE POST REQUEST ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_admin']) ) {
    $controller = new AdminController();

    $data = [
        'staff_id'       => $_POST['staff_id'] ?? null,
        'firstName'      => $_POST['firstName'] ?? null,
        'lastName'       => $_POST['lastName'] ?? null,
        'contact_no'     => $_POST['contact_no'] ?? null,
        'accessLevel_id' => $_POST['accessLevel_id'] ?? null,
        'admin_email'    => $_POST['admin_email'] ?? null,
        'status'         => $_POST['status'] ?? null,
    ];

    $updated = $controller->updateAdmin($data);

    if ($updated) {
        $_SESSION['admin_success'] = ['type' => 'success', 'message' => 'Administrator details updated successfully.'];
    } else {
        $_SESSION['admin_error'] = ['type' => 'error', 'message' => 'Failed to update administrator details.'];
    }

    $redirect = $_SERVER['HTTP_REFERER'] ?? '/'; // Go back to the page where the request came from
    header("Location: $redirect");
    exit;
}

class AdminController {
    private $model;

    public function __construct() {
        $this->model = new AdministratorModel();
    }

    public function getAllAdmins() {
        // Get the current user's access level from the session
        $currentAccessLevel = $_SESSION['access_level'] ?? 1; // default to Superadmin if not set
        return $this->model->getAdministrators($currentAccessLevel);
    }

     // --- UPDATE ADMIN DETAILS ---
     public function updateAdmin($data) {
        // Check if staff_id already exists (exclude current admin by email)
        if (!empty($data['staff_id']) && $this->model->isAdminIdExists($data['staff_id'], $data['admin_email'])) {
            $_SESSION['update_status'] = 'duplicate_staff';
            return false;
        }
    
        // Check if email already exists (exclude current admin by staff_id)
        if (!empty($data['admin_email']) && $this->model->isAdminEmailExists($data['admin_email'], $data['staff_id'])) {
            $_SESSION['update_status'] = 'duplicate_email';
            return false;
        }
    
        // Check if contact number already exists (exclude current admin by staff_id)
        if (!empty($data['contact_no']) && $this->model->isAdminContactExists($data['contact_no'], $data['staff_id'])) {
            $_SESSION['update_status'] = 'duplicate_contact';
            return false;
        }
    
        // ✅ Proceed with update if no duplicates
        $success = $this->model->updateAdminDetails($data);
        $_SESSION['update_status'] = $success ? 'success' : 'error';
        return $success;
    }
    
    public function addAdmin($data) {
        // Check if staff_id already exists
        if (!empty($data['staff_id']) && $this->model->isAdminIdExistsOnAdd($data['staff_id'])) {
            $_SESSION['add_status'] = 'duplicate_staff';
            return false;
        }

        // Check if email already exists
        if (!empty($data['admin_email']) && $this->model->isAdminEmailExistsOnAdd($data['admin_email'])) {
            $_SESSION['add_status'] = 'duplicate_email';
            return false;
        }

        // Check if contact number already exists
        if (!empty($data['contact_no']) && $this->model->isAdminContactExistsOnAdd($data['contact_no'])) {
            $_SESSION['add_status'] = 'duplicate_contact';
            return false;
        }

        // Insert admin
        $success = $this->model->addAdministrator(
            $data['staff_id'],
            $data['admin_email'],
            $data['first_name'],
            $data['last_name'],
            $data['contact_no'],
            $data['access_level'],
            $data['password'],
            $data['profile_picture']
        );

        $_SESSION['add_status'] = $success ? 'success' : 'error';
        return $success;
    }
    
    public function updateUser($data) {
        // Check if requester_id exists
        if (!empty($data['requester_id']) && $this->model->isRequesterIdExists($data['requester_id'], $data['email'])) {
            $_SESSION['update_status'] = 'duplicate';
            return false;
        }
    
        if (!empty($data['email'])) {
            return $this->model->updateUserDetails($data);
        }
    
        return false;
    }

    public function getRequestHistory() {
        header('Content-Type: application/json');

        $requester_id = $_GET['requester_id'] ?? null;
        if (!$requester_id) {
            echo json_encode([]);
            return;
        }

        try {
            $history = $this->model->getRequestHistory($requester_id);
            echo json_encode($history);
        } catch (Exception $e) {
            echo json_encode([]);
        }
    }

    // Add quantity to an existing material
    public function addQuantity($material_id, $quantity)
    {
        return $this->model->increaseQuantity($material_id, $quantity);
    }
    

    public function getProfile($admin_email)
    {
        return $this->model->getProfileByEmail($admin_email);
    }

    public function getAllFeedbacks() {
        return $this->model->getAllFeedbacks();
    }

    public function getAllMotorpoolFeedbacks() {
        return $this->model->getAllMotorpoolFeedbacks();
    }
    
    public function getOverallFeedbacks() {
        return $this->model->getOverallFeedbacks();
    }

    public function toggleAdminMenu(){
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $staff_id = $_POST['staff_id'] ?? null;
        $enabled  = $_POST['enabled'] ?? null;

        if (!$staff_id || $enabled === null) {
            echo json_encode(['status' => 'error', 'message' => 'Missing parameters']);
            exit();
        }

        $result = $this->model->toggleAdminMenuAccess($staff_id, $enabled);

        echo json_encode([
            'status' => $result ? 'success' : 'error',
            'enabled' => $enabled
        ]);
        exit();
    }
}


}

// Handle POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $controller = new AdminController(); // or AdminController if using admin

    $data = [
        'email' => $_POST['requester_email'],
        'requester_id' => $_POST['requester_id'] ?? null,
        'firstName' => $_POST['firstName'] ?? null,
        'lastName' => $_POST['lastName'] ?? null,
        'officeOrDept' => $_POST['officeOrDept'] ?? null
    ];

    $success = $controller->updateUser($data);

    if (isset($_SESSION['update_status']) && $_SESSION['update_status'] === 'duplicate') {
        $_SESSION['update_status'] = 'duplicate';
    } else {
        $_SESSION['update_status'] = $success ? 'success' : 'error';
    }

    header("Location: ../modules/superadmin/views/manage_user.php");
    exit;
}

// ✅ Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new AdminController();

    $quantity = $_POST['quantity'] ?? null;
    $material_id = $_POST['material_id'] ?? null;

    if (!$quantity || !$material_id) {
        $_SESSION['error'] = "Invalid request. Missing material or quantity.";
    } else {
        if ($controller->addQuantity($material_id, (int)$quantity)) {
            $_SESSION['success'] = "Quantity added successfully.";
        } else {
            $_SESSION['error'] = "Failed to update material quantity.";
        }
    }

    // redirect back to material list page (adjust path as needed)
    header("Location: ../modules/gsu_admin/views/inventory.php");
    exit;
}

///////////////////////////////// FOR MOTORPOOL FEEDBACK ///////////////////////////////////////////