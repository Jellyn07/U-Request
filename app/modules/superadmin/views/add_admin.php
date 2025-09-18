<?php
session_start();
require_once __DIR__ . '/../../../config/constants.php';
require_once __DIR__ . '/../../../controllers/UserController.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>U-Request</title>
  <link rel="stylesheet" href="/public/assets/css/output.css" />
  <link rel="icon" href="/public/assets/img/upper_logo.png"/>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-100">
    <?php include COMPONENTS_PATH . '/superadmin_menu.php'; ?>
    <main class="ml-16 md:ml-64 flex flex-col min-h-screen transition-all duration-300">
      <script>
      function previewProfile(event) {
        const output = document.getElementById('profile-preview');
        output.src = URL.createObjectURL(event.target.files[0]);
      }
      </script>
      <div class="max-w-4xl mx-auto space-y-8 m-2 mt-20">
        <!-- Profile Picture -->
        <form method="post" action="../../../controllers/ProfileController.php" enctype="multipart/form-data">
          <div class="rounded-xl flex flex-col items-center">
            <div class="relative">
              <img id="profile-preview"  
                  src="/public/assets/img/user-default.png" 
                  alt="profile picture"
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
              <input type="file" id="profile_picture" value="upload_picture" name="profile_picture" accept="image/*" class="hidden" onchange="previewProfile(event)">
            </div>
          </div>
        </form>
      </div>

        <!-- Identity Information -->
        <div class="flex justify-center mb-5">
            <div class="w-3/4 md:w-1/2 bg-background shadow-md rounded-xl p-6 mb-5 border border-gray-200">
            <h2 class="text-xl font-semibold mb-3">
                Admin Credentials
            </h2>
            <form class="space-y-5" method="post">
                <div>
                <label class="text-sm text-text mb-1">
                    Staff ID No.
                </label>
                <input type="text" class="w-full input-field"/>
                </div>

                <div>
                <label class="text-sm text-text mb-1">
                    USeP Email
                </label>
                <input type="text" class="w-full input-field"/>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-text mb-1">
                    First Name
                    </label>
                    <input type="text" class="w-full input-field"/>
                </div>
                <div>
                    <label class="text-sm text-text mb-1">
                    Last Name
                    </label>
                    <input type="text" class="w-full input-field"/>
                </div>
                </div>

                <div>
                <label for="program" class="text-sm text-text mb-1">Access Level</label>
                <select id="dept" name="officeOrDept" class="w-full input-field" >
                <option>Select Access</option>
                <option value="1">Super Admin</option>
                <option value="2">GSU Admin</option>
                <option value="3">Motorpool Admin</option>
                </select>
                </div>
            </form>
            </div>
        </div>

        <!-- Password Update -->
        <div class="flex justify-center mb-5">
            <div class="w-3/4 md:w-1/2 bg-white shadow-md rounded-xl p-6 mb-5 border border-gray-200">
            <h2 class="text-xl font-semibold mb-3">Default Password</h2>
                <form class="space-y-5" method="post" action="../../../controllers/ProfileController.php">
                    <div>
                    <label for="new_password" class="text-sm text-text mb-1">Password</label>
                    <input type="password" id="new_password" name="new_password" class="w-full input-field" placeholder="Enter password" required />
                    </div>
                    <div>
                    <label for="confirm_password" class="text-sm text-text mb-1">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="w-full input-field" placeholder="Re-enter password" required />
                    </div>
                </form>
            </div>            
        </div>


        <!-- Action Buttons -->
        <div class="flex justify-center mb-20 gap-2">
          <button type="button" class="btn btn-secondary">
            Cancel
          </button>
          <button type="button" class="btn btn-primary px-7">
            Save
          </button>
        </div>
      </div>
    </main>
</body>
</html>
