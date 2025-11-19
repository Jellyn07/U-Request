    function dynamicConfirm(options) {
        return Swal.fire({
            title: options.title || "Are you sure?",
            text: options.text || "Do you want to proceed?",
            icon: options.icon || "warning",
            showCancelButton: true,
            confirmButtonText: options.confirmText || "Yes",
            cancelButtonText: options.cancelText || "Cancel",
            confirmButtonColor: options.confirmColor || "#0F69AF",
            cancelButtonColor: options.cancelColor || "#6c757d"
        });
    }

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

      document.getElementById("saveContactBtn").addEventListener("click", function (e) {
        e.preventDefault();

        const form = document.getElementById("contact-form");

        // Get current values in form
        const newOffice = document.getElementById("dept").value;
        const newContact = form.querySelector("input[name='contact_no']").value.trim();

        // Validate contact number format
        const contactPattern = /^09\d{9}$/; // must start with 09 and have 11 digits total
        if (!contactPattern.test(newContact)) {
            Swal.fire({
                icon: "error",
                title: "Invalid Contact Number",
                text: "Contact number must start with '09' and be exactly 11 digits.",
            });
            return;
        }

        // Get original values from PHP (passed via dataset)
        const originalOffice = form.dataset.originalOffice;
        const originalContact = form.dataset.originalContact;

        // Determine what changed
        let changes = [];

        if (newOffice !== originalOffice) {
            changes.push("Program/Office");
        }
        if (newContact !== originalContact) {
            changes.push("Contact Number");
        }

        // If nothing changed
        if (changes.length === 0) {
            Swal.fire({
                icon: "info",
                title: "No Changes Detected",
                text: "Update something before saving.",
            });
            return;
        }

        // Build dynamic message
        const changeText = "You updated: " + changes.join(" and ") + ".";

        dynamicConfirm({
            title: "Save Changes?",
            text: changeText,
            icon: "question",
            confirmText: "Yes, update",
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

document.getElementById("savePasswordBtn").addEventListener("click", function (e) {
    e.preventDefault(); // prevent default form submit

    const form = document.getElementById("password-form");
    const oldPass = form.querySelector("input[name='old_password']").value;
    const newPass = form.querySelector("input[name='new_password']").value;
    const confirmPass = form.querySelector("input[name='confirm_password']").value;

    // Check if new password and confirm password match
    if (newPass !== confirmPass) {
        Swal.fire({
            icon: "error",
            title: "Mismatch",
            text: "New password and confirmation do not match.",
        });
        return;
    }

    // Password strength checker
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
    if (!passwordRegex.test(newPass)) {
        Swal.fire({
            icon: "error",
            title: "Weak Password",
            html: "Password must be at least 8 characters long and include:<br>- 1 uppercase letter<br>- 1 lowercase letter<br>- 1 number<br>- 1 special character",
        });
        return;
    }

    // Confirm password change
    dynamicConfirm({
        title: "Change Password?",
        text: "Are you sure you want to update your password?",
        icon: "warning",
        confirmText: "Yes, update",
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
});
