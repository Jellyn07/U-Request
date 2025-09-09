<?php
session_start();
require_once __DIR__ . '/../../../config/constants.php';
require_once __DIR__ . '/../../../controllers/ProfileController.php';

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

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
    <link rel="icon" href="<?php echo PUBLIC_URL; ?>/assets/img/upper_logo.png"/>
  </head>
  <body class="bg-background min-h-screen flex flex-col">
    <?php include COMPONENTS_PATH . '/header.php'; ?>

    <main class="container mx-auto px-4 py-10 flex-1">
      <div class="max-w-4xl mx-auto space-y-8">
        <!-- Profile Picture -->
        <form method="post" action="../../../controllers/ProfileController.php" enctype="multipart/form-data">
          <div class="bg-background rounded-xl flex flex-col items-center">
            <div class="relative">
              <img 
                src="<?php echo htmlspecialchars($profile['profile_pic'] ?? '/public/assets/img/user-default.png'); ?>" 
                alt="User Profile" 
                class="w-36 h-36 rounded-full object-cover border-2 border-secondary shadow-sm"
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
              <input type="file" id="profile_picture" name="profile_picture" accept="image/*" class="hidden" onchange="previewProfile(event)">
            </div>
          </div>
          <div class="flex justify-end mt-4">
            <button type="submit" name="action" value="upload_picture" class="btn btn-primary">
              Save New Picture
            </button>
          </div>
        </form>
        <script>
        function previewProfile(event) {
          const output = document.getElementById('profile-preview');
          output.src = URL.createObjectURL(event.target.files[0]);
        }
        </script>



        <!-- Identity Information -->
        <div class="bg-background shadow-md rounded-xl p-6">
          <h2 class="text-xl font-semibold mb-6">
            Profile Information
          </h2>
          <form class="space-y-5" method="post" action="../../../controllers/ProfileController.php">
            <input type="email" value="<?php echo htmlspecialchars($profile['email'] ?? ''); ?>" disabled class="w-full input-field bg-gray-100 cursor-not-allowed"/>
            <input type="hidden" name="requester_email" value="<?php echo htmlspecialchars($profile['email'] ?? ''); ?>">
            <div>
              <label class="text-sm text-text mb-1">
                Student/Staff ID No.
              </label>
              <input type="text" value="<?php echo htmlspecialchars($profile['requester_id'] ?? ''); ?>" disabled  class="w-full input-field bg-gray-100 cursor-not-allowed"/>
            </div>

            <div>
              <label class="text-sm text-text mb-1">
                USeP Email
              </label>
              <input type="email" value="<?php echo htmlspecialchars($profile['email'] ?? ''); ?>" disabled class="w-full input-field bg-gray-100 cursor-not-allowed"/>
              <input type="hidden" name="requester_email" value="<?php echo htmlspecialchars($profile['email'] ?? ''); ?>">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="text-sm text-text mb-1">
                  First Name
                </label>
                <input type="text" value="<?php echo htmlspecialchars($profile['firstName'] ?? ''); ?>" disabled class="w-full input-field bg-gray-100 cursor-not-allowed"/>
              </div>
              <div>
                <label class="text-sm text-text mb-1">
                  Last Name
                </label>
                <input type="text" value="<?php echo htmlspecialchars($profile['lastName'] ?? ''); ?>" disabled class="w-full input-field bg-gray-100 cursor-not-allowed"/>
              </div>
            </div>

            <div>
              <label for="program" class="text-sm text-text mb-1">Program/Office</label>
              <select id="dept" name="officeOrDept" class="w-full input-field">
              <option disabled selected>Select Department/Office</option>
                <optgroup label="Department">
                    <option value="BEED">BEED</option>
                    <option value="BSNED">BSNED</option>
                    <option value="BECED">BECED</option>
                    <option value="BSED">BSED</option>
                    <option value="BSIT">BSIT</option>
                    <option value="BTVTED">BTVTED</option>
                    <option value="BSABE">BSABE</option>
                </optgroup>
                <optgroup label="OFFICES">
                    <option value="OSAS">OSAS</option>
                    <option value="CTET">CTET</option>
                    <option value="SDMD">SDMD</option>
                    <option value="CPU">CPU</option>
                    <option value="Chancellor Office">Chancellor Office</option>
                    <option value="Campus Library">Campus Library</option>
                    <option value="Campus Clinic">Campus Clinic</option>
                    <option value="Campus Register">Campus Register</option>
                    <option value="Admin Office">Admin Office</option>
                </optgroup>
                <option value="Others">Others</option>
              </select>
            </div>
            <div class="flex justify-end">
              <button type="submit" class="btn btn-primary">
                Save Changes
              </button>
            </div>
          </form>
        </div>

        <!-- Password Update -->
        <div class="bg-white shadow-md rounded-xl p-6">
          <h2 class="text-xl font-semibold mb-6">Update Password</h2>
          <form class="space-y-5" method="post" action="../../../controllers/ProfileController.php">
            <input type="hidden" name="action" value="change_password">

            <div>
              <label for="old_password" class="text-sm text-text mb-1">Old Password</label>
              <input type="password" id="old_password" name="old_password" class="w-full input-field" placeholder="Enter old password" required />
            </div>

            <div>
              <label for="new_password" class="text-sm text-text mb-1">New Password</label>
              <input type="password" id="new_password" name="new_password" class="w-full input-field" placeholder="Enter new password" required />
            </div>

            <div>
              <label for="confirm_password" class="text-sm text-text mb-1">Confirm Password</label>
              <input type="password" id="confirm_password" name="confirm_password" class="w-full input-field" placeholder="Re-enter new password" required />
            </div>

            <div class="flex justify-end">
              <button type="submit" class="btn btn-primary">
                Save New Password
              </button>
            </div>
          </form>
        </div>


        Delete Account
        <div class="bg-white shadow-md rounded-xl p-6">
          <h2 class="text-xl font-semibold mb-6">
            Delete Account
          </h2>
          <form method="post" action="../../../controllers/ProfileController.php">
            <!-- Hidden email to identify user -->
            <input type="hidden" name="requester_email" value="<?php echo htmlspecialchars($profile['email']); ?>">
            <!-- Action identifier for the controller -->
            <input type="hidden" name="action" value="delete_account">

            <p class="text-sm text-text mb-1">
                If you no longer wish to use U-Request, you can permanently delete your account.
            </p>

            <button type="submit" class="flex-1 btn btn-primary"
                    onclick="return confirm('Are you sure you want to delete your account? This action cannot be undone.')">
                &#9888; Delete My Account
            </button>
          </form>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-3">
          <button 
            type="button"
            onclick="window.location.href='/app/controllers/LogoutController.php';"
            class="flex-1 btn btn-secondary"
          >
            Logout
          </button>
        </div>
      </div>
    </main>

    <?php include COMPONENTS_PATH . '/footer.php'; ?>

    <script>
      // Preview profile picture before upload
      function previewProfile(event) {
        const reader = new FileReader();
        reader.onload = function(){
          document.getElementById("profile-preview").src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
      }
    </script>
  </body>
</html>
