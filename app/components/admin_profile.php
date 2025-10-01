<main class="w-1/2 container mx-auto px-4 py-10 flex-1">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <form id="pictureForm" method="post" action="../../../controllers/AdminProfileController.php" enctype="multipart/form-data">
    <div class="rounded-xl flex flex-col items-center">
      <div class="relative">
        <img id="profile-preview"
          src="<?php echo htmlspecialchars(!empty($profile['profile_picture']) ? $profile['profile_picture'] : '/public/assets/img/user-default.png'); ?>"
          alt="<?php echo htmlspecialchars($profile['first_name'] ?? 'User Profile'); ?>"
          class="w-36 h-36 rounded-full object-cover border border-secondary shadow-sm" />

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
        <form id="pictureForm" method="post" enctype="multipart/form-data" action="../../../controllers/AdminProfileController.php">
          <input type="hidden" name="action" value="upload_picture">
          <input type="file" id="profile_picture" name="profile_picture" accept="image/*" class="hidden" />
        </form>
      </div>

    </div>
    <!-- <div class="flex justify-center my-2">
      <button type="submit" name="action" value="upload_picture" class="btn btn-primary ">
        Save Profile Picture
      </button>
    </div> -->
  </form>

  <!-- Identity Information -->
  <div class="bg-background shadow-md rounded-xl p-6 mb-5 border border-gray-200 mt-5">
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
          <input type="text" value="<?php echo htmlspecialchars($profile['first_name'] ?? ''); ?>" disabled class="w-full view-field cursor-not-allowed" />
        </div>
        <div>
          <label class="text-sm text-text mb-1">
            Last Name
          </label>
          <input type="text" value="<?php echo htmlspecialchars($profile['last_name'] ?? ''); ?>" disabled class="w-full view-field cursor-not-allowed" />
        </div>
      </div>
    </form>
  </div>

  <!-- Password Update -->
  <div class="bg-white shadow-md rounded-xl p-6 mb-5 border border-gray-200">
    <h2 class="text-xl font-semibold">Update Password</h2>
    <form id="passwordForm" class="space-y-5" method="post" action="../../../controllers/AdminProfileController.php">
      <input type="hidden" name="action" value="change_password">

      <!-- Old Password -->
      <div class="relative">
        <label for="old_password" class="text-sm text-text mb-1">Old Password</label>
        <input type="password" id="old_password" name="old_password" class="w-full input-field pr-10" placeholder="Enter old password" required />
        <span class="absolute right-3 top-9 cursor-pointer text-gray-500" onclick="togglePassword('old_password', this)">
          <!-- eye open (default hidden) -->
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 eye-icon eye-open" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z" />
            <circle cx="12" cy="12" r="3" />
          </svg>
          <!-- eye closed -->
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 eye-icon eye-closed hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path d="M17.94 17.94A10.94 10.94 0 0112 20c-7 0-11-8-11-8a21.77 21.77 0 015.06-6.94M9.88 9.88A3 3 0 0114.12 14.12M1 1l22 22" />
          </svg>
        </span>
      </div>

      <!-- New Password -->
      <div class="relative">
        <label for="new_password" class="text-sm text-text mb-1">New Password</label>
        <input type="password" id="new_password" name="new_password" class="w-full input-field pr-10" placeholder="Enter new password" required />
        <span class="absolute right-3 top-9 cursor-pointer text-gray-500" onclick="togglePassword('new_password', this)">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 eye-icon eye-open" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z" />
            <circle cx="12" cy="12" r="3" />
          </svg>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 eye-icon eye-closed hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path d="M17.94 17.94A10.94 10.94 0 0112 20c-7 0-11-8-11-8a21.77 21.77 0 015.06-6.94M9.88 9.88A3 3 0 0114.12 14.12M1 1l22 22" />
          </svg>
        </span>
      </div>

      <!-- Confirm Password -->
      <div class="relative">
        <label for="confirm_password" class="text-sm text-text mb-1">Confirm Password</label>
        <input type="password" id="confirm_password" name="confirm_password" class="w-full input-field pr-10" placeholder="Re-enter new password" required />
        <span class="absolute right-3 top-9 cursor-pointer text-gray-500" onclick="togglePassword('confirm_password', this)">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 eye-icon eye-open" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z" />
            <circle cx="12" cy="12" r="3" />
          </svg>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 eye-icon eye-closed hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path d="M17.94 17.94A10.94 10.94 0 0112 20c-7 0-11-8-11-8a21.77 21.77 0 015.06-6.94M9.88 9.88A3 3 0 0114.12 14.12M1 1l22 22" />
          </svg>
        </span>
      </div>

      <div class="flex justify-end">
        <button type="submit" class="btn btn-primary">
          Save New Password
        </button>
      </div>
    </form>
  </div>

  <script>
    // Preview profile picture before upload
    function previewProfile(event) {
      const reader = new FileReader();
      reader.onload = function() {
        document.getElementById("profile-preview").src = reader.result;
      }
      reader.readAsDataURL(event.target.files[0]);
    }
  </script>

  <script>
    function togglePassword(fieldId, el) {
      const input = document.getElementById(fieldId);
      const eyeOpen = el.querySelector(".eye-open");
      const eyeClosed = el.querySelector(".eye-closed");

      if (input.type === "password") {
        input.type = "text";
        eyeOpen.classList.add("hidden");
        eyeClosed.classList.remove("hidden");
      } else {
        input.type = "password";
        eyeOpen.classList.remove("hidden");
        eyeClosed.classList.add("hidden");
      }
    }
  </script>

  <!-- this is for change password -->
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const form = document.getElementById("passwordForm");

      form.addEventListener("submit", function(e) {
        e.preventDefault(); // stop normal submit

        const oldPassword = document.getElementById("old_password").value;
        const newPassword = document.getElementById("new_password").value;
        const confirmPassword = document.getElementById("confirm_password").value;

        // 1️⃣ Check new password and confirm password first
        if (newPassword !== confirmPassword) {
          Swal.fire({
            icon: "error",
            title: "Password Mismatch",
            text: "New Password and Confirm Password do not match."
          });
          return;
        }

        // 2️⃣ AJAX request to backend to check old password
        fetch("../../../controllers/AdminProfileController.php", {
            method: "POST",
            headers: {
              "Content-Type": "application/x-www-form-urlencoded"
            },
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

            // 3️⃣ If all checks pass, confirm save
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
              if (result.isConfirmed) {
                form.submit(); // finally submit the form
              }
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

  <!-- Edit profile pic -->
  <script>
document.getElementById("profile_picture").addEventListener("change", function(event) {
  const file = event.target.files[0];
  if (!file) return;

  // Save old image source in case user cancels
  const previewImg = document.getElementById("profile-preview");
  const oldSrc = previewImg.src;

  // Show preview of selected image
  const reader = new FileReader();
  reader.onload = function(e) {
    previewImg.src = e.target.result;
  };
  reader.readAsDataURL(file);

  // SweetAlert confirm
  Swal.fire({
    title: "Change Profile Picture?",
    text: "Do you want to save this new profile picture?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Yes, save it",
    cancelButtonText: "Cancel"
  }).then((result) => {
    if (result.isConfirmed) {
      // Show success before submitting
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
      // Reset to old picture if canceled
      previewImg.src = oldSrc;
      event.target.value = "";
    }
  });
});
</script>


</main>