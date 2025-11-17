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
          // title: '<span class="text-lg font-bold text-text">Request Details</span>',
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
            htmlContainer: 'text-sm text-black'
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
  const driverForm = document.getElementById("driverForm");
  const userForm = document.getElementById("userForm");
  const updateBtn = document.getElementById("updateBtn");

  // Helper: Validate contact number
  function validateContact(input) {
    const value = input.value.trim();
    let errorEl = input.parentElement.querySelector('.contact-error');
    if (!errorEl) {
      errorEl = document.createElement('p');
      errorEl.classList.add('contact-error', 'text-red-600', 'text-xs', 'mt-1');
      input.parentElement.appendChild(errorEl);
    }
    errorEl.textContent = "";

    const pattern = /^09\d{9}$/;
    if (!pattern.test(value)) {
      errorEl.textContent = "Contact number must start with 09 and be 11 digits long.";
      input.focus();
      return false;
    }
    return true;
  }

  // Helper: Attach confirmation + optional contact validation
  function attachFormConfirmation(form, message, actionInputName = null) {
    if (!form) return;

    form.addEventListener("submit", function (e) {
      e.preventDefault();

      // Validate contact if exists
      const contactInput = form.querySelector('input[name="contact_no"]');
      if (contactInput && !validateContact(contactInput)) return;

      // Show confirmation
      Swal.fire({
        title: "Are you sure?",
        text: message,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, update it",
        cancelButtonText: "Cancel"
      }).then((result) => {
        if (result.isConfirmed) {
          // Add hidden action input if needed
          if (actionInputName && !form.querySelector(`input[name="${actionInputName}"]`)) {
            const hidden = document.createElement("input");
            hidden.type = "hidden";
            hidden.name = actionInputName;
            hidden.value = "1";
            form.appendChild(hidden);
          }
          form.submit();
        }
      });
    });
  }

  // Attach confirmations
  attachFormConfirmation(adminForm, "Do you want to update this administrator's details?");
  attachFormConfirmation(personnelForm, "Do you want to update this personnel's details?", "update_personnel");
  attachFormConfirmation(driverForm, "Do you want to update this driver's details?", "update_driver");
  attachFormConfirmation(userForm, "Do you want to save these changes?");

  // Update button (outside form)
  if (updateBtn && userForm) {
    updateBtn.addEventListener("click", function () {
      Swal.fire({
        title: "Update User Details?",
        text: "Are you sure you want to save these changes?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, update!"
      }).then((result) => {
        if (result.isConfirmed) userForm.submit();
      });
    });
  }

  // ✅ Show success/error alerts based on URL params
  const urlParams = new URLSearchParams(window.location.search);
  const updated = urlParams.get("updated");
  if (updated === "1") {
    Swal.fire({
      title: "Updated!",
      text: "Details have been updated successfully.",
      icon: "success",
      confirmButtonColor: "#3085d6"
    });
  } else if (updated === "0") {
    Swal.fire({
      title: "Error!",
      text: "Something went wrong while updating.",
      icon: "error",
      confirmButtonColor: "#d33"
    });
  }
});

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
  const fetchUrl = '/app/controllers/UserController.php'; // adjust if needed

  function fetchHistory(requesterId) {
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
        <table class="min-w-full border text-xs text-left">
          <thead class="bg-gray-100 text-xs">
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
          <tr class="text-xs">
            <td class="p-1 border">${row.tracking_id ?? ''}</td>
            <td class="p-1 border">${row.request_Type ?? ''}</td>
            <td class="p-1 border">${row.request_desc ?? ''}</td>
            <td class="p-1 border">${row.location ?? ''}</td>
            <td class="p-1 border">${row.req_status ?? ''}</td>
            <td class="p-1 border">${row.date_finished ?? '-'}</td>
          </tr>
        `;
      });

      historyHtml += `</tbody></table></div>`;

      Swal.fire({
        title: 'Request History',
        html: historyHtml,
        width: 1200,
        confirmButtonText: 'Close',
        didOpen: () => {
          const btn = Swal.getConfirmButton();
          btn.style.backgroundColor = '#800000'; // maroon
          btn.style.color = '#fff'; // white text
          btn.style.border = 'none';
          btn.style.padding = '0.5rem 1rem';
          btn.style.borderRadius = '0.25rem';
        }
      });
    })
    .catch(err => {
      console.error('History fetch error:', err);
      Swal.fire('Error', 'Failed to load request history. Check console or network tab for details.', 'error');
    });
  }

  document.querySelectorAll('.uhistoryBtn').forEach(button => {
    button.addEventListener('click', () => {
      const requesterId = button.getAttribute('data-requester-id');
      fetchHistory(requesterId);
    });
  });
});

// User Request History Repair
async function viewRequestHistory(requester_id) {
  if (!requester_id) return;

  try {
    const formData = new FormData();
    formData.append("get_request_history", "1");
    formData.append("requester_id", requester_id);

    const res = await fetch("../../../controllers/UserAdminController.php", {
      method: "POST",
      body: formData
    });

    const history = await res.json();

    if (!Array.isArray(history) || !history.length) {
      Swal.fire({
        icon: "info",
        title: "No Request History Found",
        text: "This requester has no recorded requests yet."
      });
      return;
    }

    // Create table rows dynamically
    const rows = history.map(item => `
      <tr class="hover:bg-gray-50">
        <td class="px-3 py-1 border">${item.tracking_id}</td>
        <td class="px-3 py-1 border">${item.request_Type}</td>
        <td class="px-3 py-1 border">${item.req_status}</td>
        <td class="px-3 py-1 border">${item.date_finished ?? "—"}</td>
      </tr>
    `).join("");

    Swal.fire({
      title: "Request History",
      html: `
        <div class="overflow-x-auto">
          <table class="w-full border text-sm text-left">
            <thead>
              <tr class="bg-gray-100">
                <th class="px-3 py-1 border">Tracking ID</th>
                <th class="px-3 py-1 border">Request Type</th>
                <th class="px-3 py-1 border">Status</th>
                <th class="px-3 py-1 border">Date Finished</th>
              </tr>
            </thead>
            <tbody>${rows}</tbody>
          </table>
        </div>
      `,
      width: 800,
      confirmButtonText: "Close",
      confirmButtonColor: "#800000"
    });
  } catch (err) {
    console.error(err);
    Swal.fire({ icon: "error", title: "Error", text: "Could not fetch history." });
  }
}

// DRIVER ALERT
document.addEventListener('DOMContentLoaded', () => {
  // --- Driver Alerts ---
  if (window.driverSuccess) {
    const msg = (typeof window.driverSuccess === 'string')
      ? window.driverSuccess
      : (window.driverSuccess.message || JSON.stringify(window.driverSuccess));

    Swal.fire({
      icon: 'success',
      title: 'Driver Added Successfully',
      text: msg,
      timer: 2000,
      timerProgressBar: true,
      showConfirmButton: false
    });
    window.driverSuccess = null;
  }

  if (window.driverError) {
    const msg = (typeof window.driverError === 'string')
      ? window.driverError
      : (window.driverError.message || JSON.stringify(window.driverError));

    Swal.fire({
      icon: 'error',
      title: 'Driver Action Failed',
      text: msg,
      timer: 4000,
      timerProgressBar: true,
      showConfirmButton: true
    });
    window.driverError = null;
  }
});
