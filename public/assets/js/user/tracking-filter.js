document.addEventListener('DOMContentLoaded', () => {
  const repairStatuses = ['To Inspect', 'In Progress', 'Completed'];
  const vehicleStatuses = ['Pending', 'Approved', 'Disapproved'];

  const statusSelect = document.getElementById('statusSelect');
  const form = document.getElementById('filterForm');
  const typeInput = document.getElementById('typeInput');

  if (!form || !statusSelect || !typeInput) return;

  // 🔄 When clicking "Repair" or "Vehicle" tab
  form.querySelectorAll('button[data-type]').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      const newType = e.target.getAttribute('data-type');
      typeInput.value = newType;
      updateStatusOptions(newType);
      form.submit();
    });
  });

  // 🔄 Update status dropdown dynamically
  function updateStatusOptions(type) {
    const options = type === 'vehicle' ? vehicleStatuses : repairStatuses;
    const currentStatus = statusSelect.value;
    statusSelect.innerHTML = '<option value="">All Status</option>';
    options.forEach(opt => {
      const option = document.createElement('option');
      option.value = opt;
      option.textContent = opt;
      if (opt === currentStatus) option.selected = true;
      statusSelect.appendChild(option);
    });
  }

  // 🔄 Auto-submit on status or sort change
  statusSelect.addEventListener('change', () => form.submit());
  document.querySelector('select[name="sort"]').addEventListener('change', () => form.submit());
});

function openCancelModal(control_no) {
    Swal.fire({
        title: "Cancel This Request?",
        html: `
            <p class="mb-2 text-sm text-gray-700">
                Please provide your reason for cancellation.
            </p>
            <p class="mb-4 text-xs text-red-600">
                Note: Please refrain from cancelling more than 3 times in a week.
            </p>
        `,
        icon: "warning",
        input: "textarea",
        inputPlaceholder: "Enter your reason...",
        showCancelButton: true,
        confirmButtonText: "Submit",
        cancelButtonText: "Close",
        preConfirm: (reason) => {
            if (!reason) {
                Swal.showValidationMessage("Reason is required.");
                return false;
            }

            // You can optionally check cancel count here via an API
            // Example: if (cancelCount >= 3) { Swal.showValidationMessage("You have reached the weekly limit."); return false; }

            // Send POST request to controller
            return fetch("../../../controllers/VehicleRequestController.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: new URLSearchParams({
                    form_action: "cancelRequest",
                    control_no: control_no,
                    reason: reason
                })
            })
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    Swal.showValidationMessage(data.message || "Request failed");
                    return false;
                }
                return data;
            })
            .catch(() => Swal.showValidationMessage("Request failed."));
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                icon: "success",
                title: "Cancelled",
                text: "Your request has been cancelled.",
            }).then(() => location.reload()); // You can later replace this with dynamic UI update
        }
    });
}
