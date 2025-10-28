document.addEventListener("alpine:init", () => {
  Alpine.data("requestList", () => ({
    showDetails: false,
    selected: {},

    selectRow(request) {
    his.selected = request;
    this.showDetails = true;
  },
}));
});

fetch('../../../controllers/VehicleRequestController.php?vehicles=1')
  .then(res => res.json())
  .then(data => {
    const vehicleSelect = document.getElementById('vehicleSelect');
    vehicleSelect.innerHTML = '<option value="">No Vehicle Assigned</option>';
    data.forEach(v => {
      const opt = document.createElement('option');
      opt.value = v.vehicle_id;
      opt.textContent = v.vehicle_name;
      vehicleSelect.appendChild(opt);
    });
});

fetch('../../../controllers/VehicleRequestController.php?drivers=1')
  .then(res => res.json())
  .then(data => {
    const staffSelect = document.getElementById('staffSelect');
    staffSelect.innerHTML = '<option value="">No Assigned Driver</option>';
    data.forEach(p => {
      const opt = document.createElement('option');
      opt.value = p.driver_id; // ✅ corrected
      opt.textContent = p.full_name;
      staffSelect.appendChild(opt);
    });
});

document.addEventListener("alpine:init", () => {
  Alpine.data("requestList", () => ({
    showDetails: false,
    selected: {},

    selectRow(request) {
      this.selected = request;
      this.showDetails = true;
    },

    viewFullDetails(selected) {
      Swal.fire({
        html: `
          <div class="text-left text-sm max-w-full overflow-x-auto">
            <h2 class="text-base font-bold mb-2">Vehicle Request Details</h2>

            <div class="mb-2"><label class="text-xs">Tracking No.</label>
              <input type="text" class="w-full border px-2 py-1 rounded text-sm" value="${selected.tracking_id}" readonly />
            </div>

            <div class="mb-2"><label class="text-xs">Request Date</label>
              <input type="text" class="w-full border px-2 py-1 rounded text-sm" value="${selected.date_request}" readonly />
            </div>

            <div class="mb-2"><label class="text-xs">Requester</label>
              <input type="text" class="w-full border px-2 py-1 rounded text-sm" value="${selected.requester_name}" readonly />
            </div>

            <div class="mb-2"><label class="text-xs">Requester Contact No</label>
              <input type="text" class="w-full border px-2 py-1 rounded text-sm" value="${selected.contact}" readonly />
            </div>

            <div class="mb-2"><label class="text-xs">Travel Date</label>
              <input type="text" class="w-full border px-2 py-1 rounded text-sm" value="${selected.travel_date}" readonly />
            </div>

            <div class="mb-2"><label class="text-xs">Return Travel Date</label>
              <input type="text" class="w-full border px-2 py-1 rounded text-sm" value="${selected.return_date}" readonly />
            </div>

            <div class="mb-2"><label class="text-xs">Destination</label>
              <input type="text" class="w-full border px-2 py-1 rounded text-sm" value="${selected.travel_destination}" readonly />
            </div>

            <div class="mb-2"><label class="text-xs">Trip Purpose</label>
              <input type="text" class="w-full border px-2 py-1 rounded text-sm" value="${selected.trip_purpose}" readonly />
            </div>

            <div class="mb-2"><label class="text-xs">Departure and Return Time</label>
              <input type="text" class="w-full border px-2 py-1 rounded text-sm" value="${selected.depret_time || 'N/A'}" readonly />
            </div>

            <div class="mb-2"><label class="text-xs">Passengers</label>
              <ul class="border px-2 py-1 rounded text-sm max-h-40 overflow-y-auto">
                ${selected.passengers && selected.passengers.length > 0 
                  ? selected.passengers.map(p => `<li>${p.name || p}</li>`).join('') 
                  : '<li>No Passengers</li>'}
              </ul>
            </div>

            <div class="mb-2"><label class="text-xs">Assigned Vehicle</label>
              <input type="text" class="w-full border px-2 py-1 rounded text-sm" value="${selected.vehicle_name || 'Not Assigned'}" readonly />
            </div>

            <div class="mb-2"><label class="text-xs">Assigned Driver</label>
              <input type="text" class="w-full border px-2 py-1 rounded text-sm" value="${selected.full_name || 'Not Assigned'}" readonly />
            </div>

            <div class="mb-2"><label class="text-xs">Status</label>
              <input type="text" class="w-full border px-2 py-1 rounded text-sm" value="${selected.req_status}" readonly />
            </div>

          </div>
        `,
        width: 600,
        confirmButtonText: 'Close',
        confirmButtonColor: '#800000'
      });
    }
  }));
});