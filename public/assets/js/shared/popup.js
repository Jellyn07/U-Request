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
            ${row.date_finished 
                ? new Date(row.date_finished).toLocaleDateString('en-US', {
                    month: 'short',
                    day: 'numeric',
                    year: 'numeric'
                  })
                : '-'
            }
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
          <!-- HEADER -->
            <div class="flex flex-col items-center justify-center mb-2">
              <img src="/public/assets/img/usep.png" class="w-20 h-20 mb-2 mt-4" alt="USeP Logo">
              <h2 class="text-lg font-semibold text-center">REPAIR REQUEST DETAILS</h2>
            </div>

          <div class="text-sm mb-1 font-medium mt-3">
            <label class="text-base">Requester</label>
            <input type="text" class="view-field w-full font-normal" value="${selected.Name}" readonly />
          </div>

          <div class="text-sm mb-1 font-medium mt-3">
            <label class="text-base">Requester Office / Department</label>
            <input type="text" class="view-field w-full font-normal" value="${selected.requester_officeOrDept || 'Undefined'}" readonly />
          </div>

          <div class="text-sm mb-1 font-medium">
            <label class="text-base">Tracking No.</label>
            <input type="text" class="view-field w-full font-normal" value="${selected.tracking_id}" readonly />
          </div>     
          
          <div class="font-medium text-left">
            <label class="text-base mb-2">Photo Evidence</label>
            <img src="${selected.image_path 
                      ? '/public/uploads/' + selected.image_path 
                      : '/public/assets/img/default-img.png'}"
               onerror="this.src='/public/assets/img/default-img.png'"
               class="mt-1 rounded-md border border-gray-200 max-h-48"/>
          </div>  

          <hr class="my-6 border-gray-400">
          <h4 class="text-base font-semibold mb-2">Request Information</h4>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="text-xs mb-1 font-medium">
              <label class="text-xs">Date the Issue was Noticed</label>
              <input type="text" class="view-field w-full font-normal" value="${selected.request_date}" readonly />
            </div>
            <div class="text-xs mb-1 font-medium">
              <label class="text-xs">Date Finished</label>
              <input type="text" class="view-field w-full font-normal" value="${selected.date_finished || 'Not Applicable'}" readonly />
            </div>          
          </div>


          <div class="text-xs mb-1 font-medium mt-2">
            <label class="text-xs">Location</label>
            <input type="text" class="view-field w-full font-normal" value="${selected.location}" readonly />
          </div>

          <div class="text-xs mb-1 font-medium mt-3">
            <label class="text-xs">Nature of Request</label>
            <input type="text" class="view-field w-full font-normal" value="${selected.request_Type}" readonly />
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="text-xs mb-1 font-medium mt-3">
              <label class="text-xs">Unit</label>
              <input type="text" class="view-field w-full font-normal" value="${selected.unit}" readonly />
            </div>
            <div class="text-xs mb-1 font-medium mt-3">
              <label class="text-xs">Priority Level</label>
              <input type="text" class="view-field w-full font-normal" value="${selected.priority_status || 'No Priority Level'}" readonly />
            </div>

          </div>

          <div class="text-xs mb-1 font-medium mt-3">
            <label class="text-xs">Detailed Description of the Issue</label>
            <input type="text" class="view-field w-full font-normal" value="${selected.request_desc}" readonly />
          </div>

          <hr class="my-6 border-gray-400">
          <!-- SECTION: Repair Info -->
          <h4 class="text-base font-semibold mb-2">Repair Information</h4>

          <div class="text-xs mb-1 font-medium mt-3"><label class="text-xs">Assigned Personnel</label>
            <input type="text" class="view-field w-full font-normal" value="${selected.assigned_personnel || ''}" readonly />
          </div>
          
          <div class="text-xs mb-1 font-medium mt-3"><label class="text-xs">Materials Used</label>
            <input type="text" class="view-field w-full font-normal" value="${selected.materials_needed || ''}" readonly />
          </div>

          <div class="text-xs mb-1 font-medium mt-3"><label class="text-xs">Status</label>
            <input type="text" class="view-field w-full font-normal" value="${selected.req_status}" readonly />
          </div>

          <p class="text-xs text-gray-500 text-center mt-6">
            © 2025 University of Southeastern Philippines — U-Request System
          </p>
        </div>
      `,
      width: 600,
      customClass: {
          popup: 'swal-custom-popup text-black m-10 rounded-lg',
          confirmButton: 'btn btn-primary font-normal',
          textColor: 'text-sm'
        },
      confirmButtonText: 'Close'
    });
  };
});

window.exportDetails = function(selected) {

  const imagePath = selected.image_path 
    ? selected.image_path 
    : 'default-img.png';

  const params = new URLSearchParams({
    request_id: selected.request_id,
    name: selected.Name,
    office: selected.requester_officeOrDept,
    tracking: selected.tracking_id,
    date: selected.request_date,
    finished: selected.date_finished,
    location: selected.location,
    type: selected.request_Type,
    unit: selected.unit,
    priority: selected.priority_status,
    description: selected.request_desc,
    personnel: selected.assigned_personnel,
    materials: selected.materials_used ?? selected.materials_needed ?? selected.materials ?? '',
    status: selected.req_status,
    image: imagePath   // ✅ ADD THIS
  }).toString();

  window.location.href = "gsu_form_export.php?" + params;
};


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

document.addEventListener("click", function (e) {
    if (e.target.closest(".historyBtn")) {

        const btn = e.target.closest(".historyBtn");
        const material_code = btn.getAttribute("data-code");

        if (!material_code) {
            return Swal.fire("Error", "Material code not found.", "error");
        }

        fetch('../../../controllers/MaterialController.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({
                fetch_material_history: true,
                material_code: material_code
            })
        })
        .then(res => res.json())
        .then(response => {

            if (response.status !== "success" || response.data.length === 0) {
                return Swal.fire(
                    'No Records Found',
                    'No usage history found for this material.',
                    'info'
                );
            }

            let rows = response.data.map(item => `
                <tr>
                    <td class="border px-2 py-1 text-center">${item.tracking_id}</td>
                    <td class="border px-2 py-1">${item.location}</td>
                    <td class="border px-2 py-1">${item.material_desc}</td>
                    <td class="border px-2 py-1 text-center">${item.quantity_needed}</td>
                    <td class="border px-2 py-1 text-center">
                        ${new Date(item.material_requested_date).toLocaleDateString('en-US', {
                            month: 'short',
                            day: 'numeric',
                            year: 'numeric'
                        })}
                    </td>
                </tr>
            `).join('');

            Swal.fire({
                title: 'Material Used History',
                width: 900,
                confirmButtonText: 'Close',
                confirmButtonColor: '#800000',
                background: '#fff',
                customClass: {
                    popup: 'border-t-4 border-red-500 shadow-lg rounded-lg'
                },
                html: `
                    <div class="overflow-auto max-h-60">
                        <table class="min-w-full text-xs text-center border border-gray-300">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="border px-2 py-1">Tracking ID</th>
                                    <th class="border px-2 py-1">Location</th>
                                    <th class="border px-2 py-1">Description</th>
                                    <th class="border px-2 py-1">Qty Used</th>
                                    <th class="border px-2 py-1">Date Requested</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${rows}
                            </tbody>
                        </table>
                    </div>
                `
            });

        })
        .catch(err => {
            console.error('Error fetching material history:', err);
            Swal.fire('Error', 'Unable to fetch material history.', 'error');
        });
    }
});