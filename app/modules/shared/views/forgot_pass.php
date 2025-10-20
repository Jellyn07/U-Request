<?php
session_start();
require_once __DIR__ . '/../../../config/constants.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>U-Request | Forgot Password</title>
  <link rel="stylesheet" href="<?php echo PUBLIC_URL; ?>/assets/css/output.css" />
  <link rel="icon" href="<?php echo PUBLIC_URL; ?>/assets/img/upper_logo.png"/>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-background min-h-screen flex relative overflow-hidden">

  <!-- LEFT SECTION -->
  <div class="w-1/2 flex items-center justify-center relative z-10">
    <div class="w-1/2 max-w-md bg-background">
      <div class="text-center mb-4">
        <img src="<?php echo PUBLIC_URL; ?>/assets/img/logo_light.png" alt="U-Request Logo" class="mx-auto h-20 w-20">
        <p class="text-2xl font-bold">U<span class="text-accent">-</span>REQUEST</p>
      </div>

      <form id="forgotForm">

        <!-- STEP 1: EMAIL -->
        <div id="step-email">
          <h1 class="text-center text-sm font-medium mb-2">Forgot Your Password?</h1>
          <label class="text-sm text-text mb-1">USeP Email Address</label>
          <input type="email" id="email" name="email" class="mb-3 w-full input-field" placeholder="your@usep.edu.ph" required>
          <button type="button" class="w-full btn btn-primary" onclick="sendEmail()">Send OTP</button>
          <p class="w-full text-center mt-2">
            <a href="../../user/views/login.php" class="text-primary text-sm hover:underline">Return to Login</a>
          </p>
        </div>

        <!-- STEP 2: OTP -->
        <div id="step-otp" class="hidden">
          <h1 class="text-center text-sm font-medium mb-2">Enter the OTP</h1>
          <label class="text-sm text-text mb-1">One-Time Password</label>
          <input type="text" id="otp" name="otp" placeholder="ex. 123456" class="mb-3 w-full input-field" required>
          <button type="button" class="w-full btn btn-primary" onclick="verifyOtp()">Verify OTP</button>
          <p class="w-full text-center mt-2">
            <a href="../../user/views/login.php" class="text-primary text-sm hover:underline">Return to Login</a>
          </p>
        </div>

        <!-- STEP 3: RESET PASSWORD -->
        <div id="step-reset" class="hidden">
          <h1 class="text-center text-sm font-medium mb-2">Reset Your Password</h1>
          <label class="text-sm text-text mb-1">New Password</label>
          <input type="password" id="new_password" name="new_password" class="mb-3 w-full input-field" required>
          <label class="text-sm text-text mb-1">Confirm Password</label>
          <input type="password" id="confirm_password" name="confirm_password" class="mb-3 w-full input-field" required>
          <button type="button" class="w-full btn btn-primary" onclick="resetPassword()">Save New Password</button>
        </div>

      </form>
    </div>
  </div>

  <!-- RIGHT SECTION -->
  <div class="w-1/2 flex items-end justify-end relative z-10 text-white">
    <div class="text-right p-8">
      <p class="text-sm text-text/70">
        &copy; All rights reserved.
        <a href="#" class="text-text underline">Terms & Conditions</a>
        <span class="text-text">&middot;</span>
        <a href="#" class="text-text underline">Privacy Policy</a>
      </p>
    </div>
  </div>

  <!-- BACKGROUND LAYERS -->
  <div class="absolute inset-0 clip-diagonal z-0"></div>
  <div class="absolute inset-0 clip-diagonal1"></div>
  <div class="absolute inset-0 clip-diagonal2"></div>

  <script>
const HANDLER_URL = "../../../handlers/forgot_password_handler.php";

function showStep(stepId) {
  document.querySelectorAll("#forgotForm > div").forEach(d => d.classList.add("hidden"));
  document.getElementById(stepId).classList.remove("hidden");
}

/* ─────────────── STEP 1: SEND EMAIL ─────────────── */
function sendEmail() {
  const email = document.getElementById("email").value.trim();
  if (!email) return Swal.fire({
    icon: "warning",
    title: "Missing Email",
    text: "Please enter your registered email address.",
    confirmButtonColor: "#800000"
  });

  Swal.fire({
    title: "Sending OTP...",
    text: "Please wait while we send a verification code to your email.",
    allowOutsideClick: false,
    didOpen: () => Swal.showLoading(),
  });

  fetch(HANDLER_URL, {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: new URLSearchParams({ action: "send_otp", email })
  })
  .then(r => r.json())
  .then(data => {
    Swal.close();
    if (data.success) {
      Swal.fire({
        icon: "success",
        title: "OTP Sent!",
        text: data.message,
        confirmButtonColor: "#800000"
      }).then(() => showStep("step-otp"));
    } else {
      Swal.fire({
        icon: "error",
        title: "Failed",
        text: data.message,
        confirmButtonColor: "#800000"
      });
    }
  })
  .catch(() => {
    Swal.fire({
      icon: "error",
      title: "Connection Error",
      text: "Unable to contact the server. Try again later.",
      confirmButtonColor: "#800000"
    });
  });
}

/* ─────────────── STEP 2: VERIFY OTP ─────────────── */
function verifyOtp() {
  const email = document.getElementById("email").value.trim();
  const otp = document.getElementById("otp").value.trim();
  if (!otp) return Swal.fire({
    icon: "warning",
    title: "Missing OTP",
    text: "Please enter the code sent to your email.",
    confirmButtonColor: "#800000"
  });

  Swal.fire({
    title: "Verifying...",
    text: "Please wait while we verify your OTP.",
    allowOutsideClick: false,
    didOpen: () => Swal.showLoading(),
  });

  fetch(HANDLER_URL, {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: new URLSearchParams({ action: "verify_otp", email, otp })
  })
  .then(r => r.json())
  .then(data => {
    Swal.close();
    if (data.success) {
      Swal.fire({
        icon: "success",
        title: "Verified!",
        text: data.message,
        confirmButtonColor: "#800000"
      }).then(() => showStep("step-reset"));
    } else {
      Swal.fire({
        icon: "error",
        title: "Invalid OTP",
        text: data.message,
        confirmButtonColor: "#800000"
      });
    }
  })
  .catch(() => {
    Swal.fire({
      icon: "error",
      title: "Server Error",
      text: "Unable to verify OTP right now.",
      confirmButtonColor: "#800000"
    });
  });
}

/* ─────────────── STEP 3: RESET PASSWORD ─────────────── */
function resetPassword() {
  const email = document.getElementById("email").value.trim();
  const newPass = document.getElementById("new_password").value.trim();
  const confirmPass = document.getElementById("confirm_password").value.trim();

  if (!newPass || !confirmPass) return Swal.fire({
    icon: "warning",
    title: "Missing Fields",
    text: "Please fill out both password fields.",
    confirmButtonColor: "#800000"
  });

  if (newPass !== confirmPass) return Swal.fire({
    icon: "error",
    title: "Password Mismatch",
    text: "Passwords do not match. Try again.",
    confirmButtonColor: "#800000"
  });

  Swal.fire({
    title: "Saving...",
    text: "Please wait while we update your password.",
    allowOutsideClick: false,
    didOpen: () => Swal.showLoading(),
  });

  fetch(HANDLER_URL, {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: new URLSearchParams({ action: "reset_password", email, new_password: newPass })
  })
  .then(r => r.json())
  .then(data => {
    Swal.close();
    if (data.success) {
      Swal.fire({
        icon: "success",
        title: "Password Reset Successful!",
        text: data.message,
        confirmButtonColor: "#800000",
        timer: 2500,
        showConfirmButton: false
      }).then(() => window.location.href = "../../user/views/login.php");
    } else {
      Swal.fire({
        icon: "error",
        title: "Failed",
        text: data.message,
        confirmButtonColor: "#800000"
      });
    }
  })
  .catch(() => {
    Swal.fire({
      icon: "error",
      title: "Server Error",
      text: "Could not reset your password.",
      confirmButtonColor: "#800000"
    });
  });
}
</script>

</body>
</html>
