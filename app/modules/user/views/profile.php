<?php
session_start();
require_once __DIR__ . '/../../../config/constants.php';
require_once __DIR__ . '/../../../controllers/ProfileController.php';


if (!isset($_SESSION['email'])) {
  header("Location: login.php");
  exit;
}

if (isset($_SESSION['alert'])) {
  $alert = $_SESSION['alert'];
  unset($_SESSION['alert']); // prevent repeat alerts
}

if (isset($_SESSION['succcess'])) {
  $alert = $_SESSION['success'];
  unset($_SESSION['success']); // prevent repeat alerts
}
// Check if req_id exists, otherwise redirect
if (!isset($_SESSION['req_id'])) {
  header("Location: http://localhost:3000/app/modules/user/views/login.php");
  exit;
}

$req_id = $_SESSION['req_id'];
$controller = new ProfileController();

// Get user ID from session
$requester_email = $_SESSION['email'] ?? null;

// Safely load profile; may be null if not found
$profile = $requester_email ? $controller->getProfile($requester_email) : null;
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>U-Request | My Profile</title>
  <link rel="stylesheet" href="<?php echo PUBLIC_URL; ?>/assets/css/output.css" />
  <link rel="stylesheet" href="/public/assets/css/remove.css">
  <link rel="icon" href="<?php echo PUBLIC_URL; ?>/assets/img/upper_logo.png" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="/public/assets/js/alert.js"></script>
</head>

<body class="bg-gray-200 min-h-screen flex flex-col">
  <?php include COMPONENTS_PATH . '/header.php'; ?>

  <main class="md:w-1/2 w-full container mx-auto px-4 py-1 md:py-10 flex-1">
    <script>
      function previewProfile(event) {
        const output = document.getElementById('profile-preview');
        output.src = URL.createObjectURL(event.target.files[0]);
      }
    </script>
    <div class="max-w-4xl mx-auto space-y-8 m-2">
      <!-- Profile Picture Form -->
      <form id="pictureForm" method="post" action="../../../controllers/ProfileController.php" enctype="multipart/form-data">
        <div class="rounded-xl flex flex-col items-center">
          <div class="relative">

            <!-- Vue Template for Profile Picture -->
            <img 
                id="profile-preview" 
                src="/public/uploads/profile_pics/<?php echo htmlspecialchars($profile['profile_pic']); ?>" 
                onerror="this.onerror=null; this.src='/public/assets/img/user-default.png';" 
                alt="<?php echo htmlspecialchars($first_name); ?>" 
                class="w-36 h-36 rounded-full object-cover border border-secondary shadow-sm mx-auto" 
            />
            
            <!-- Edit button -->
            <label for="profile_picture" title="Change Profile Picture"
              class="absolute bottom-2 right-2 bg-primary text-white p-2 rounded-full shadow-md cursor-pointer transition">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15.232 5.232l3.536 3.536m-2.036-5.036
                      a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
              </svg>
            </label>

            <!-- File input -->
            <input type="hidden" name="action" value="upload_picture">
            <input type="file" id="profile_picture" name="profile_picture" accept="image/*" class="hidden" />
          </div>
        </div>
      </form>
    </div>

    <!-- Identity Information -->
    <div class="bg-background shadow-md rounded-xl p-6 mb-5 border border-gray-200">
      <h2 class="text-xl font-semibold">
        Profile Information
      </h2>
      <form id="contact-form"
        data-original-office="<?= htmlspecialchars($profile['officeOrDept'] ?? '') ?>"
        data-original-contact="<?= htmlspecialchars($profile['contact'] ?? '') ?>"
        class="space-y-5"
        method="post"
        action="../../../controllers/ProfileController.php">

        <input type="hidden" name="requester_email" value="<?php echo htmlspecialchars($profile['email'] ?? ''); ?>">
        <input type="hidden" name="action" value="update_contact">

        <div>
          <label class="text-sm text-text mb-1">
            Student/Staff ID No.
          </label>
          <input type="text" value="<?php echo htmlspecialchars($profile['requester_id'] ?? ''); ?>" disabled class="w-full view-field cursor-not-allowed" />
        </div>

        <div>
          <label class="text-sm text-text mb-1">
            USeP Email
          </label>
          <input type="email" value="<?php echo htmlspecialchars($profile['email'] ?? ''); ?>" disabled class="w-full view-field cursor-not-allowed" />
          <input type="hidden" name="requester_email" value="<?php echo htmlspecialchars($profile['email'] ?? ''); ?>">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="text-sm text-text mb-1">
              First Name
            </label>
            <input type="text" value="<?php echo htmlspecialchars($profile['firstName'] ?? ''); ?>" disabled class="w-full view-field cursor-not-allowed" />
          </div>
          <div>
            <label class="text-sm text-text mb-1">
              Last Name
            </label>
            <input type="text" value="<?php echo htmlspecialchars($profile['lastName'] ?? ''); ?>" disabled class="w-full view-field cursor-not-allowed" />
          </div>
        </div>

        <div>
          <label for="dept" class="text-sm text-text mb-1">Program/Office</label>
          <select id="dept" name="officeOrDept" class="w-full input-field">
            <option disabled <?= empty($profile['officeOrDept']) ? 'selected' : ''; ?>>Select Department/Office</option>

            <optgroup label="Department">
              <option value="BEED" <?= ($profile['officeOrDept'] ?? '') === 'BEED' ? 'selected' : '' ?>>BEED</option>
              <option value="BSNED" <?= ($profile['officeOrDept'] ?? '') === 'BSNED' ? 'selected' : '' ?>>BSNED</option>
              <option value="BECED" <?= ($profile['officeOrDept'] ?? '') === 'BECED' ? 'selected' : '' ?>>BECED</option>
              <option value="BSED" <?= ($profile['officeOrDept'] ?? '') === 'BSED' ? 'selected' : '' ?>>BSED</option>
              <option value="BSIT" <?= ($profile['officeOrDept'] ?? '') === 'BSIT' ? 'selected' : '' ?>>BSIT</option>
              <option value="BTVTED" <?= ($profile['officeOrDept'] ?? '') === 'BTVTED' ? 'selected' : '' ?>>BTVTED</option>
              <option value="BSABE" <?= ($profile['officeOrDept'] ?? '') === 'BSABE' ? 'selected' : '' ?>>BSABE</option>
            </optgroup>

            <optgroup label="OFFICES">
              <option value="OSAS" <?= ($profile['officeOrDept'] ?? '') === 'OSAS' ? 'selected' : '' ?>>OSAS</option>
              <option value="CTET" <?= ($profile['officeOrDept'] ?? '') === 'CTET' ? 'selected' : '' ?>>CTET</option>
              <option value="SDMD" <?= ($profile['officeOrDept'] ?? '') === 'SDMD' ? 'selected' : '' ?>>SDMD</option>
              <option value="CPU" <?= ($profile['officeOrDept'] ?? '') === 'CPU' ? 'selected' : '' ?>>CPU</option>
              <option value="Chancellor Office" <?= ($profile['officeOrDept'] ?? '') === 'Chancellor Office' ? 'selected' : '' ?>>Chancellor Office</option>
              <option value="Campus Library" <?= ($profile['officeOrDept'] ?? '') === 'Campus Library' ? 'selected' : '' ?>>Campus Library</option>
              <option value="Campus Clinic" <?= ($profile['officeOrDept'] ?? '') === 'Campus Clinic' ? 'selected' : '' ?>>Campus Clinic</option>
              <option value="Campus Register" <?= ($profile['officeOrDept'] ?? '') === 'Campus Register' ? 'selected' : '' ?>>Campus Register</option>
              <option value="Admin Office" <?= ($profile['officeOrDept'] ?? '') === 'Admin Office' ? 'selected' : '' ?>>Admin Office</option>
            </optgroup>

            <option value="Others" <?= ($profile['officeOrDept'] ?? '') === 'Others' ? 'selected' : '' ?>>Others</option>
          </select>
        </div>
        <div>
          <label class="text-sm text-text mb-1">
            Contact Number
          </label>
          <input
            type="text"
            name="contact_no"
            value="<?php echo htmlspecialchars($profile['contact'] ?? ''); ?>"
            class="w-full input-field"
            placeholder="Ex. 09123456789"
            required />
        </div>

        <div class="flex justify-end">
          <button type="submit" class="btn btn-primary" id="saveContactBtn">
            Save Changes
          </button>
        </div>
      </form>
    </div>

    <!-- Password Update -->
    <div class="bg-white shadow-md rounded-xl p-6 mb-5 border border-gray-200">
      <h2 class="text-xl font-semibold">Update Password</h2>
      <form id="password-form" class="space-y-5" method="post" action="../../../controllers/ProfileController.php">
        <input type="hidden" name="action" value="change_password">

        <div class="relative">
          <label for="old_password" class="text-sm text-text mb-1">Old Password</label>
          <input type="password" id="old_password" name="old_password" class="w-full input-field" placeholder="Enter old password" required />
          <span class="absolute right-3 cursor-pointer" data-password-toggle="old_password">
            <img src="/public/assets/img/view.png" class="size-4 eye-open my-2.5 transition-opacity duration-200">
            <img src="/public/assets/img/hide.png" class="size-4 eye-closed hidden my-2.5 transition-opacity duration-200">
          </span>
        </div>

        <div class="relative">
          <label for="new_password" class="text-sm text-text mb-1">New Password</label>
          <input type="password" id="new_password" name="new_password" class="w-full input-field" placeholder="Enter new password" required />
          <span class="absolute right-3 cursor-pointer" data-password-toggle="new_password">
            <img src="/public/assets/img/view.png" class="size-4 eye-open my-2.5 transition-opacity duration-200">
            <img src="/public/assets/img/hide.png" class="size-4 eye-closed hidden my-2.5 transition-opacity duration-200">
          </span>
        </div>

        <div class="relative">
          <label for="confirm_password" class="text-sm text-text mb-1">Confirm Password</label>
          <input type="password" id="confirm_password" name="confirm_password" class="w-full input-field" placeholder="Re-enter new password" required />
          <span class="absolute right-3 cursor-pointer" data-password-toggle="confirm_password">
            <img src="/public/assets/img/view.png" class="size-4 eye-open my-2.5 transition-opacity duration-200">
            <img src="/public/assets/img/hide.png" class="size-4 eye-closed hidden my-2.5 transition-opacity duration-200">
          </span>
        </div>

        <div class="flex justify-end">
          <button type="submit" class="btn btn-primary" id="savePasswordBtn">
            Save New Password
          </button>
        </div>
      </form>
    </div>


    <!-- Delete Account
        <div class="bg-white shadow-md rounded-xl p-6">
          <h2 class="text-xl font-semibold mb-6">
            Delete Account
          </h2>
          <form method="post" action="../../../controllers/ProfileController.php">
            
            <input type="hidden" name="requester_email">
            
            <input type="hidden" name="action" value="delete_account">

            <p class="text-sm text-text mb-1">
                If you no longer wish to use U-Request, you can permanently delete your account.
            </p>

            <button type="submit" class="flex-1 btn btn-primary"
                    onclick="return confirm('Are you sure you want to delete your account? This action cannot be undone.')">
                &#9888; Delete My Account
            </button>
          </form>
        </div> -->

    </div>
  </main>

  <?php include COMPONENTS_PATH . '/footer.php'; ?>
  <script src="/public/assets/js/shared/password-visibility.js"></script>
  <script src="/public/assets/js/user/profile.js"></script>
</body>

</html>
<?php if (isset($alert)) : ?>
  <script>
    Swal.fire({
      title: "<?php echo $alert['title']; ?>",
      text: "<?php echo $alert['message']; ?>",
      icon: "<?php echo $alert['icon']; ?>",
      confirmButtonColor: "#0F69AF"
    });
  </script>
<?php endif; ?>