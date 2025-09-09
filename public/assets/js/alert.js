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
          title: '<span class="text-xl font-bold text-gray-800">Request Details</span>',
          html: `
            <div class="text-left space-y-3">
              ${data}
            </div>
          `,
          width: 600,
          showCloseButton: true,
          showConfirmButton: false,
          background: '#fff',
          customClass: {
            popup: 'rounded-lg shadow-lg',
            title: 'mb-4',
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
  