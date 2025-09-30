<main class="w-1/2 container mx-auto px-4 py-10 flex-1">
    <form method="post" action="../../../controllers/AdminProfileController.php" enctype="multipart/form-data">
          <div class="bg-background rounded-xl flex flex-col items-center">
            <div class="relative">
              <img id="profile-preview"  
                  src="<?php echo htmlspecialchars(!empty($profile['profile_picture']) ? $profile['profile_picture'] : '/public/assets/img/user-default.png'); ?>" 
                  alt="<?php echo htmlspecialchars($profile['first_name'] ?? 'User Profile'); ?>"
                  class="w-36 h-36 rounded-full object-cover border border-secondary shadow-sm"
              />

              <?php
              $defaultPic = '/public/assets/img/user-default.png';
              $profilePic = (!empty($profile['profile_picture']) && file_exists($_SERVER['DOCUMENT_ROOT'] . $profile['profile_picture']))
              ? $profile['profile_picture']
              : $defaultPic;
              ?>
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
          <div class="flex justify-center my-2">
            <button type="submit" name="action" value="upload_picture" class="btn btn-primary ">
              Save Profile Picture
            </button>
          </div>
        </form>

    <!-- Identity Information -->
    <div class="bg-background shadow-md rounded-xl p-6 mb-5 border border-gray-200">
        <h2 class="text-xl font-semibold">
            Profile Information
        </h2>
        <form class="space-y-5" method="post" action="../../../controllers/AdminProfileController.php">
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($profile['email'] ?? ''); ?>">
            <div>
                <label class="text-sm text-text mb-1">
                    Staff ID No.
                </label>
                <input type="text" value="<?php echo htmlspecialchars($profile['staff_id'] ?? ''); ?>" disabled class="w-full view-field cursor-not-allowed" />
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
                    <input type="text" value="<?php echo htmlspecialchars($profile['first_name'] ?? ''); ?>" disabled  class="w-full view-field cursor-not-allowed" />
                </div>
                <div>
                    <label class="text-sm text-text mb-1">
                        Last Name
                    </label>
                    <input type="text" value="<?php echo htmlspecialchars($profile['last_name'] ?? ''); ?>" disabled class="w-full view-field cursor-not-allowed" />
                </div>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="btn btn-primary">
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    <!-- Password Update -->
    <div class="bg-white shadow-md rounded-xl p-6 border border-gray-200">
        <h2 class="text-xl font-semibold">Update Password</h2>
        <form class="space-y-5" method="post" action="../../../controllers/AdminProfileController.php">
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
    </div>
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
</main>