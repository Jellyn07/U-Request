<main class="w-full md:w-1/2 container mx-auto px-4 py-10 flex-1">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Profile Picture Form -->
  <form id="pictureForm" method="post" action="../../../controllers/AdminProfileController.php" enctype="multipart/form-data">
    <div class="rounded-xl flex flex-col items-center">
      <div class="relative">
        <img id="profile-preview"
          src="<?php echo !empty($profile['profile_picture']) 
            ? '/public/uploads/profile_pics/' . htmlspecialchars($profile['profile_picture']) 
            : '/public/assets/img/user-default.png'; ?>" 
            alt="Profile Picture" class="w-36 h-36 rounded-full object-cover shadow-sm mx-auto mb-4">
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

        <!-- File input -->
        <input type="hidden" name="action" value="upload_picture">
        <input type="file" id="profile_picture" name="profile_picture" accept="image/*" class="hidden" />
      </div>
    </div>
  </form>

  <!-- Profile Information -->
  <div class="bg-background shadow-md rounded-xl p-6 mb-5 border border-gray-200 mt-5">
    <h2 class="text-xl font-semibold">Profile Information</h2>
    <form class="space-y-5" method="post" action="../../../controllers/AdminProfileController.php">
      <input type="hidden" name="email" value="<?php echo htmlspecialchars($profile['email'] ?? ''); ?>">

      <div>
        <label class="text-sm text-text mb-1">Staff ID No.</label>
        <input type="text" value="<?php echo htmlspecialchars($profile['staff_id'] ?? ''); ?>" disabled class="w-full view-field cursor-not-allowed" />
      </div>

      <div>
        <label class="text-sm text-text mb-1">USeP Email</label>
        <input type="email" value="<?php echo htmlspecialchars($profile['email'] ?? ''); ?>" disabled class="w-full view-field cursor-not-allowed" />
        <input type="hidden" name="requester_email" value="<?php echo htmlspecialchars($profile['email'] ?? ''); ?>">
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="text-sm text-text mb-1">First Name</label>
          <input type="text" value="<?php echo htmlspecialchars($profile['first_name'] ?? ''); ?>" disabled class="w-full view-field cursor-not-allowed" />
        </div>
        <div>
          <label class="text-sm text-text mb-1">Last Name</label>
          <input type="text" value="<?php echo htmlspecialchars($profile['last_name'] ?? ''); ?>" disabled class="w-full view-field cursor-not-allowed" />
        </div>
      </div>
    </form>
  </div>

  <!-- Password Update -->
  <div class="bg-white shadow-md rounded-xl p-6 mb-5 border border-gray-200">
    <h2 class="text-xl font-semibold">Update Password</h2>
    <form id="passwordForm" class="space-y-4" method="post" action="../../../controllers/AdminProfileController.php">
      <input type="hidden" name="action" value="change_password">

      <!-- Old Password -->
      <div class="relative w-full">
        <label for="old_password" class="text-sm text-text mb-1">Old Password</label>
        <input type="password" id="old_password" name="old_password"
               class="w-full input-field pr-10" placeholder="Enter old password" required />
        <span class="absolute right-3 cursor-pointer" data-password-toggle="old_password">
          <img src="/public/assets/img/view.png" class="size-4 eye-open my-2.5 transition-opacity duration-200">
          <img src="/public/assets/img/hide.png" class="size-4 eye-closed hidden my-2.5 transition-opacity duration-200">
        </span>
      </div>

      <!-- New Password -->
      <div class="relative w-full">
        <label for="new_password" class="text-sm text-text mb-1">New Password</label>
        <input type="password" id="new_password" name="new_password"
               class="w-full input-field pr-10" placeholder="Enter new password" required />
        <span class="absolute right-3 cursor-pointer" data-password-toggle="new_password">
          <img src="/public/assets/img/view.png" class="size-4 eye-open my-2.5 transition-opacity duration-200">
          <img src="/public/assets/img/hide.png" class="size-4 eye-closed hidden my-2.5 transition-opacity duration-200">
        </span>
      </div>

      <!-- Confirm Password -->
      <div class="relative w-full">
        <label for="confirm_password" class="text-sm text-text mb-1">Confirm Password</label>
        <input type="password" id="confirm_password" name="confirm_password"
               class="w-full input-field pr-10" placeholder="Re-enter new password" required />
        <span class="absolute right-3 cursor-pointer" data-password-toggle="confirm_password">
          <img src="/public/assets/img/view.png" class="size-4 eye-open my-2.5 transition-opacity duration-200">
          <img src="/public/assets/img/hide.png" class="size-4 eye-closed hidden my-2.5 transition-opacity duration-200">
        </span>
      </div>

      <div class="flex">
        <button type="submit" class="btn btn-primary">Change my password</button>
      </div>
    </form>
  </div>

  <!-- Preview Profile Picture -->
  <script>
    document.getElementById("profile_picture").addEventListener("change", function(event) {
      const file = event.target.files[0];
      if (!file) return;

      const previewImg = document.getElementById("profile-preview");
      const oldSrc = previewImg.src;
      const reader = new FileReader();

      reader.onload = function(e) {
        previewImg.src = e.target.result;
      };
      reader.readAsDataURL(file);

      Swal.fire({
        title: "Change Profile Picture?",
        text: "Do you want to save this new profile picture?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, save it",
        cancelButtonText: "Cancel"
      }).then((result) => {
        if (result.isConfirmed) {
          Swal.fire({
            icon: "success",
            title: "Profile Picture Updated",
            text: "Your new profile picture will be saved.",
            showConfirmButton: false,
            timer: 1500
          }).then(() => {
            document.getElementById("pictureForm").submit();
          });
        } else {
          previewImg.src = oldSrc;
          event.target.value = "";
        }
      });
    });
  </script>

  <!-- Change Password Logic -->
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const form = document.getElementById("passwordForm");

      form.addEventListener("submit", function(e) {
        e.preventDefault();

        const oldPassword = document.getElementById("old_password").value;
        const newPassword = document.getElementById("new_password").value;
        const confirmPassword = document.getElementById("confirm_password").value;

        if (newPassword !== confirmPassword) {
          Swal.fire({
            icon: "error",
            title: "Password Mismatch",
            text: "New Password and Confirm Password do not match."
          });
          return;
        }

        fetch("../../../controllers/AdminProfileController.php", {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: new URLSearchParams({
            action: "verify_old_password",
            old_password: oldPassword
          })
        })
          .then(res => res.json())
          .then(data => {
            if (!data.valid) {
              Swal.fire({
                icon: "error",
                title: "Mismatched Current Password",
                text: "The entered Old Password is incorrect!"
              });
              return;
            }

            Swal.fire({
              title: "Are you sure?",
              text: "Do you want to save the new password?",
              icon: "warning",
              showCancelButton: true,
              confirmButtonColor: "#3085d6",
              cancelButtonColor: "#d33",
              confirmButtonText: "Yes, save it",
              cancelButtonText: "Cancel"
            }).then((result) => {
              if (result.isConfirmed) form.submit();
            });
          })
          .catch(err => {
            console.error("Error verifying password:", err);
            Swal.fire({
              icon: "error",
              title: "Server Error",
              text: "Something went wrong. Please try again."
            });
          });
      });
    });
  </script>
  <!-- Password Visibility Script -->
  <script src="/public/assets/js/shared/password-visibility.js"></script>
</main>
