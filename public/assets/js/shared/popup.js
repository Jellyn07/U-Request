// WORK HISTORY PERSONNEL

function viewWorkHistory(staff_id) {
  if (!staff_id) {
    Swal.fire('No Staff ID', 'Please select a personnel first.', 'warning');
    return;
  }

  fetch('../../../controllers/PersonnelController.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: new URLSearchParams({ get_work_history: true, staff_id })
  })
  .then(res => res.json())
  .then(data => {
    if (data.length === 0) {
      Swal.fire('No Records Found', 'This staff has no work history yet.', 'info');
      return;
    }

    let tableRows = data.map(row => `
      <tr>
        <td class="border px-2 py-1 text-center">${row.request_id}</td>
        <td class="border px-2 py-1">${row.request_Type}</td>
       <td class="border px-2 py-1 text-center">
        ${new Date(row.date_finished).toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric'
        })}
        </td>

      </tr>
    `).join('');

    Swal.fire({
      title: 'Work History',
      html: `
        <div class="overflow-auto max-h-60">
          <table class="min-w-full text-xs text-center border border-gray-300">
            <thead class="bg-gray-100">
              <tr>
                <th class="border px-2 py-1 text-center">Request ID</th>
                <th class="border px-2 py-1">Request Type</th>
                <th class="border px-2 py-1 text-center">Date Finished</th>
              </tr>
            </thead>
            <tbody>${tableRows}</tbody>
          </table>
        </div>
      `,
       confirmButtonText: 'Close',
       confirmButtonColor: '#800000', // 🟤 maroon color
       width: 600,
       background: '#fff',
       customClass: {
         popup: 'border-t-4 border-red-500 shadow-lg rounded-lg'
      }
    });
  })
  .catch(err => {
    console.error('Error fetching work history:', err);
    Swal.fire('Error', 'Unable to fetch work history.', 'error');
  });
}

// popup.js
document.addEventListener("alpine:init", () => {
  // 🔹 View details popup
  window.viewDetails = function(selected) {
    Swal.fire({
      html: `
        <div class="text-left text-sm max-w-full overflow-x-auto">
          <h2 class="text-base font-bold mb-2">Repair Information</h2>
          <img src="${selected.image_path 
                      ? '/public/uploads/' + selected.image_path 
                      : '/public/assets/img/default-img.png'}"
               onerror="this.src='/public/assets/img/default-img.png'"
               class="w-6/12 shadow-lg mx-auto rounded-lg mb-3"/>

          <div class="mb-2"><label class="text-xs">Date Request</label>
            <input type="text" class="w-full border px-2 py-1 rounded text-sm" value="${selected.request_date}" readonly />
          </div>

          <div class="mb-2"><label class="text-xs">Tracking No.</label>
            <input type="text" class="w-full border px-2 py-1 rounded text-sm" value="${selected.tracking_id}" readonly />
          </div>

          <div class="mb-2"><label class="text-xs">Requester</label>
            <input type="text" class="w-full border px-2 py-1 rounded text-sm" value="${selected.Name}" readonly />
          </div>

          <div class="mb-2"><label class="text-xs">Category</label>
            <input type="text" class="w-full border px-2 py-1 rounded text-sm" value="${selected.request_Type}" readonly />
          </div>

          <div class="mb-2"><label class="text-xs">Description</label>
            <input type="text" class="w-full border px-2 py-1 rounded text-sm" value="${selected.request_desc}" readonly />
          </div>

          <div class="mb-2"><label class="text-xs">Unit</label>
            <input type="text" class="w-full border px-2 py-1 rounded text-sm" value="${selected.unit}" readonly />
          </div>

          <div class="mb-2"><label class="text-xs">Location</label>
            <input type="text" class="w-full border px-2 py-1 rounded text-sm" value="${selected.location}" readonly />
          </div>

          <div class="mb-2"><label class="text-xs">Priority Level</label>
            <input type="text" class="w-full border px-2 py-1 rounded text-sm" value="${selected.priority_status || 'No Priority Level'}" readonly />
          </div>

          <div class="mb-2"><label class="text-xs">Assigned Personnel</label>
            <input type="text" class="w-full border px-2 py-1 rounded text-sm" value="${selected.full_name || 'Not Assigned'}" readonly />
          </div>

          <div class="mb-2"><label class="text-xs">Status</label>
            <input type="text" class="w-full border px-2 py-1 rounded text-sm" value="${selected.req_status}" readonly />
          </div>

          <div class="mb-2"><label class="text-xs">Date Finished</label>
            <input type="text" class="w-full border px-2 py-1 rounded text-sm" value="${selected.date_finished || 'Pending'}" readonly />
          </div>
        </div>
      `,
      width: 600,
      confirmButtonText: 'Close',
      confirmButtonColor: '#800000' // 🔴 maroon color
    });
  };
});


// 🔹 Handle Save Button Confirmation
document.addEventListener("DOMContentLoaded", () => {
  const saveBtn = document.getElementById('saveBtn');
  if (saveBtn) {
    saveBtn.addEventListener('click', function() {
      const priority = document.getElementById('prioritySelect').value;
      const staff = document.getElementById('staffSelect').value;
      const form = document.getElementById('assignmentForm');

      if (!priority) {
        Swal.fire({
          icon: 'warning',
          title: 'Missing Priority Level',
          text: 'Please select a priority level before saving.'
        });
        return;
      }

      if (!staff) {
        Swal.fire({
          icon: 'warning',
          title: 'Missing Personnel',
          text: 'Please assign a personnel before saving.'
        });
        return;
      }

      Swal.fire({
        title: 'Save Changes?',
        text: 'Are you sure you want to update this request assignment?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Save',
        cancelButtonText: 'Cancel'
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit();
        }
      });
    });
  }

  // 🔹 Handle Status Dropdown Change
  const dropdowns = document.querySelectorAll(".status-dropdown");
  dropdowns.forEach(dropdown => {
    dropdown.addEventListener("change", function() {
      const requestId = this.dataset.requestId;
      const oldStatus = this.dataset.currentStatus;
      const newStatus = this.value;

      if (oldStatus === newStatus) return;

      Swal.fire({
        title: 'Update Status?',
        text: `Are you sure you want to change status to "${newStatus}"?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, update',
        cancelButtonText: 'Cancel'
      }).then((result) => {
        if (result.isConfirmed) {
          fetch('../../../controllers/RequestController.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({
              action: 'updateStatus',
              request_id: requestId,
              req_status: newStatus
            })
          })
          .then(res => res.json())
          .then(data => {
            if (data.success) {
              Swal.fire({
                icon: 'success',
                title: 'Status Updated',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
              });
              dropdown.dataset.currentStatus = newStatus;
              dropdown.className = `status-dropdown px-2 py-1 rounded-full text-xs ${
                newStatus === 'In Progress' ? 'bg-blue-100 text-blue-800' :
                (newStatus === 'Completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800')
              }`;
            } else {
              Swal.fire({ icon: 'error', title: 'Error', text: data.message });
              dropdown.value = oldStatus;
            }
          })
          .catch(() => {
            Swal.fire({ icon: 'error', title: 'AJAX Error', text: 'Something went wrong. Try again.' });
            dropdown.value = oldStatus;
          });
        } else {
          dropdown.value = oldStatus;
        }
      });
    });
  });
});

async function viewVehicleRequestHistory(requester_id) {
  if (!requester_id) return;

  try {
    const formData = new FormData();
    formData.append("get_vehicle_request_history", "1");
    formData.append("requester_id", requester_id);

    const res = await fetch("../../../controllers/UserAdminController.php", {
      method: "POST",
      body: formData
    });

    const history = await res.json();

    if (!Array.isArray(history) || history.length === 0) {
      Swal.fire({
        icon: "info",
        title: "No Vehicle Request History Found",
        text: "This requester has no recorded vehicle requests yet."
      });
      return;
    }

    // Helper function to format dates as Month Day, Year
    const formatDate = (dateString) => {
      if (!dateString) return "—";
      const date = new Date(dateString);
      return date.toLocaleDateString("en-US", {
        year: "numeric",
        month: "long",
        day: "numeric"
      });
    };

    // Create table rows dynamically
    const rows = history.map(item => `
      <tr class="hover:bg-gray-50">
        <td class="px-1 py-1 border">${item.tracking_id}</td>
        <td class="px-1 py-1 border">${item.trip_purpose}</td>
        <td class="px-1 py-1 border">${item.travel_destination}</td>
        <td class="px-1 py-1 border">${formatDate(item.travel_date)}</td>
        <td class="px-1 py-1 border">${formatDate(item.return_date)}</td>
        <td class="px-1 py-1 border">${item.req_status ?? "—"}</td>
      </tr>
    `).join("");

    Swal.fire({
      title: "Vehicle Request History",
      html: `
        <div class="overflow-x-auto max-h-[400px] overflow-y-auto">
          <table class="w-full border text-sm text-left">
            <thead>
              <tr class="bg-gray-100">
                <th class="px-1 py-1 border">Tracking No</th>
                <th class="px-1 py-1 border">Trip Purpose</th>
                <th class="px-1 py-1 border">Destination</th>
                <th class="px-1 py-1 border">Date of Travel</th>
                <th class="px-1 py-1 border">Date of Return</th>
                <th class="px-1 py-1 border">Status</th>
              </tr>
            </thead>
            <tbody>${rows}</tbody>
          </table>
        </div>
      `,
      width: 900,
      confirmButtonText: "Close",
      confirmButtonColor: "#800000"
    });
  } catch (err) {
    console.error("Vehicle request history fetch error:", err);
    Swal.fire({ 
      icon: "error", 
      title: "Error", 
      text: "Unable to fetch vehicle request history." 
    });
  }
}
