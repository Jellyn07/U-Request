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

document.addEventListener('DOMContentLoaded', () => {
  // adjust this path so it points to the controller PHP file (relative to the current page)
  const fetchUrl = '/app/controllers/UserController.php'; // <<— CHANGE if necessary

  document.querySelectorAll('.uhistoryBtn').forEach(button => {
    button.addEventListener('click', () => {
      const requesterId = button.getAttribute('data-requester-id');
      if (!requesterId) {
        Swal.fire('Error', 'Missing requester id', 'error');
        return;
      }

      fetch(fetchUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
          action: 'get_history',
          requester_id: requesterId
        }).toString()
      })
      .then(async response => {
        // help debugging: if response not JSON show its text
        const text = await response.text();
        try {
          return JSON.parse(text);
        } catch (err) {
          throw new Error('Invalid JSON from server: ' + text);
        }
      })
      .then(data => {
        if (!data) throw new Error('Empty response');
        if (!data.success) {
          const errMsg = data.error || 'No History Found';
          return Swal.fire('No History', errMsg, 'info');
        }

        const rows = data.records || [];
        let historyHtml = `
          <div style="max-height:60vh; overflow:auto;">
          <table class="min-w-full border text-sm text-left">
            <thead class="bg-gray-100">
              <tr>
                <th class="p-2 border">Tracking ID</th>
                <th class="p-2 border">Type</th>
                <th class="p-2 border">Description</th>
                <th class="p-2 border">Location</th>
                <th class="p-2 border">Status</th>
                <th class="p-2 border">Date Finished</th>
              </tr>
            </thead>
            <tbody>
        `;

        rows.forEach(row => {
          historyHtml += `
            <tr>
              <td class="p-2 border">${row.tracking_id ?? ''}</td>
              <td class="p-2 border">${row.request_Type ?? ''}</td>
              <td class="p-2 border">${row.request_desc ?? ''}</td>
              <td class="p-2 border">${row.location ?? ''}</td>
              <td class="p-2 border">${row.req_status ?? ''}</td>
              <td class="p-2 border">${row.date_finished ?? '-'}</td>
            </tr>
          `;
        });

        historyHtml += `</tbody></table></div>`;

        Swal.fire({
          title: 'Request History',
          html: historyHtml,
          width: 900,
          confirmButtonText: 'Close'
        });
      })
      .catch(err => {
        console.error('History fetch error:', err);
        Swal.fire('Error', 'Failed to load request history. Check console or network tab for details.', 'error');
      });
    });
  });
});
