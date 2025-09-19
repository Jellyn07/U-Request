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
  const form = document.getElementById("adminForm");

  if (form) {
    form.addEventListener("submit", function (e) {
      e.preventDefault(); 

      Swal.fire({
        title: "Are you sure?",
        text: "Do you want to update this administrator’s details?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, update it",
        cancelButtonText: "Cancel"
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit();
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