// alerts.js

function showErrorAlert(message) {
    Swal.fire({
        icon: 'error',
        title: 'Login Failed',
        text: message,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Try Again'
    });
}

function showErrorA(msg) {
    Swal.fire({
        icon: 'error',
        title: 'Signup Failed',
        text: msg
    });
}
function showSuccessAlert(msg) {
    Swal.fire({
        icon: 'success',
        title: 'Signup Successful',
        text: msg
    });
}

function openDetails(trackingId) {
    fetch("tracking_details.php?id=" + trackingId)
      .then(response => response.text())
      .then(data => {
        Swal.fire({
          title: '<span class="text-lg font-bold text-text">Request Details</span>',
          html: `
            <div class="text-left">
              ${data}
            </div>
          `,
          width: 600,
          showCloseButton: true,
          showConfirmButton: false,
          background: '#fff',
          customClass: {
            popup: 'rounded-lg shadow-lg',
            title: 'mb-2',
            htmlContainer: 'text-sm text-gray-700'
          }
        });
      })
      .catch(error => {
        console.error("Error loading details:", error);
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Failed to load details. Please try again later.'
        });
      });
  }

function showRequestSuccess(message, redirect = null) {
    Swal.fire({
        icon: 'success',
        title: 'Request Submitted',
        text: message,
        confirmButtonColor: '#28a745',
        confirmButtonText: 'OK'
    }).then(() => {
        if (redirect) {
            window.location.href = redirect;
        }
    });
}

function showRequestError(message, redirect = null) {
    Swal.fire({
        icon: 'error',
        title: 'Request Failed',
        text: message,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Try Again'
    }).then(() => {
        if (redirect) {
            window.location.href = redirect;
        }
    });
}

// alert.js
document.addEventListener('DOMContentLoaded', () => {
  if (window.adminSuccess) {
    const successMsg = typeof window.adminSuccess === 'string'
      ? window.adminSuccess
      : (window.adminSuccess.message || 'Operation successful.');
    Swal.fire({
      icon: 'success',
      title: 'Success',
      text: successMsg,
      timer: 1000,
      timerProgressBar: true,
      showConfirmButton: false
    });
  }

  if (window.adminError) {
    const errorMsg = typeof window.adminError === 'string'
      ? window.adminError
      : (window.adminError.message || 'Something went wrong.');
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: errorMsg,
      timer: 3000,
      timerProgressBar: true,
      showConfirmButton: true
    });
  }
});

document.addEventListener("DOMContentLoaded", function () {
  const adminForm = document.getElementById("adminForm");
  const personnelForm = document.getElementById("personnelForm");

  if (adminForm) {
    adminForm.addEventListener("submit", function (e) {
      e.preventDefault(); 

      Swal.fire({
        title: "Are you sure?",
        text: "Do you want to update this administrator's details?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, update it",
        cancelButtonText: "Cancel"
      }).then((result) => {
        if (result.isConfirmed) {
          adminForm.submit();
        }
      });
    });
  }

  if (personnelForm) {
    personnelForm.addEventListener("submit", function (e) {
      e.preventDefault(); 

      Swal.fire({
        title: "Are you sure?",
        text: "Do you want to update this personnel's details?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, update it",
        cancelButtonText: "Cancel"
      }).then((result) => {
        if (result.isConfirmed) {
          // Ensure routing flag is present because programmatic submit omits the clicked button name
          if (!personnelForm.querySelector('input[name="update_personnel"]')) {
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'update_personnel';
            hidden.value = '1';
            personnelForm.appendChild(hidden);
          }
          personnelForm.submit();
        }
      });
    });
  }

  // Show success alert after redirect
  const urlParams = new URLSearchParams(window.location.search);
  if (urlParams.get("updated") === "1") {
    Swal.fire({
      title: "Updated!",
      text: "Administrator details have been updated successfully.",
      icon: "success",
      confirmButtonColor: "#3085d6"
    });
  } else if (urlParams.get("updated") === "0") {
    Swal.fire({
      title: "Error!",
      text: "Something went wrong while updating.",
      icon: "error",
      confirmButtonColor: "#d33"
    });
  }
});

const userForm = document.getElementById("userForm");
if (userForm) {
  userForm.addEventListener("submit", function(e) {
    e.preventDefault();

    Swal.fire({
      title: "Are you sure?",
      text: "Do you want to save these changes?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, save it!"
    }).then((result) => {
      if (result.isConfirmed) {
        e.target.submit();
      }
    });
  });
}

const updateBtn = document.getElementById('updateBtn');
if (updateBtn) {
  updateBtn.addEventListener('click', function(e) {
    Swal.fire({
        title: 'Update User Details?',
        text: "Are you sure you want to save these changes?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, update!'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('userForm').submit();
        }
    })
  });
}

document.addEventListener('DOMContentLoaded', () => {
  // debug lines — remove when stable
  // console.log('alert.js loaded, personnelSuccess:', window.personnelSuccess, 'personnelError:', window.personnelError);

  if (window.personnelSuccess) {
    const msg = (typeof window.personnelSuccess === 'string') ? window.personnelSuccess
               : (window.personnelSuccess.message || JSON.stringify(window.personnelSuccess));
    Swal.fire({
      icon: 'success',
      title: 'Success',
      text: msg,
      timer: 2000,
      timerProgressBar: true,
      showConfirmButton: false
    });
    window.personnelSuccess = null;
  }

  if (window.personnelError) {
    const msg = (typeof window.personnelError === 'string') ? window.personnelError
              : (window.personnelError.message || JSON.stringify(window.personnelError));
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: msg,
      timer: 4000,
      timerProgressBar: true,
      showConfirmButton: true
    });
    window.personnelError = null;
  }
});
