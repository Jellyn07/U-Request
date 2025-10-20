// ===========================
// Step Navigation Functions
// ===========================

// Step 1 → Step 2: Send OTP
function goToOtp() {
  const email = document.getElementById("email").value.trim();
  if (!email) {
    Swal.fire("Error", "Please enter your email.", "error");
    return;
  }

  Swal.fire({
    title: "Sending OTP...",
    text: "Please wait while we send a verification code.",
    allowOutsideClick: false,
    didOpen: () => Swal.showLoading(),
  });

  fetch("../../../controllers/ForgotPasswordController.php", {
    method: "POST",
    body: new URLSearchParams({ email }),
  })
    .then((res) => res.json())
    .then((data) => {
      Swal.close();
      if (data.success) { // Changed from data.status
        Swal.fire("Success", data.message, "success");
        document.getElementById("step-email").classList.add("hidden");
        document.getElementById("step-otp").classList.remove("hidden");
      } else {
        Swal.fire("Error", data.message, "error");
      }
    })
    .catch((err) => {
      Swal.close();
      Swal.fire("Error", "Something went wrong: " + err, "error");
    });
}

// Step 2 → Step 3: Verify OTP
function goToReset() {
  const email = document.getElementById("email").value.trim();
  const otp = document.getElementById("otp").value.trim();

  if (!otp) {
    Swal.fire("Error", "Please enter the OTP code.", "error");
    return;
  }

  Swal.fire({
    title: "Verifying OTP...",
    allowOutsideClick: false,
    didOpen: () => Swal.showLoading(),
  });

  fetch("../../../controllers/ForgotPasswordController.php", {
    method: "POST",
    body: new URLSearchParams({ email, verify_otp: otp }),
  })
    .then((res) => res.json())
    .then((data) => {
      Swal.close();
      if (data.success) {
        Swal.fire("Verified", "OTP verified successfully!", "success");
        document.getElementById("step-otp").classList.add("hidden");
        document.getElementById("step-reset").classList.remove("hidden");
      } else {
        Swal.fire("Error", data.message, "error");
      }
    })
    .catch((err) => {
      Swal.close();
      Swal.fire("Error", "Failed to verify OTP: " + err, "error");
    });
}

// Step 3: Reset Password
document.getElementById("forgotForm").addEventListener("submit", function (e) {
  e.preventDefault();

  const email = document.getElementById("email").value.trim();
  const new_password = document.getElementById("new_password").value.trim();
  const confirm_password = document.getElementById("confirm_password").value.trim();

  if (!new_password || !confirm_password) {
    Swal.fire("Error", "Please fill in both password fields.", "error");
    return;
  }

  if (new_password !== confirm_password) {
    Swal.fire("Error", "Passwords do not match.", "error");
    return;
  }

  Swal.fire({
    title: "Updating Password...",
    allowOutsideClick: false,
    didOpen: () => Swal.showLoading(),
  });

  fetch("../../../controllers/ForgotPasswordController.php", {
    method: "POST",
    body: new URLSearchParams({ email, reset: true, new_password }),
  })
    .then((res) => res.json())
    .then((data) => {
      Swal.close();
      if (data.success) {
        Swal.fire("Success", "Your password has been reset.", "success").then(() => {
          window.location.href = "login.php"; // redirect to login page
        });
      } else {
        Swal.fire("Error", data.message, "error");
      }
    })
    .catch((err) => {
      Swal.close();
      Swal.fire("Error", "Something went wrong. Try again later: " + err, "error");
    });
});
