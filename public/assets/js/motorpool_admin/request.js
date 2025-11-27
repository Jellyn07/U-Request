document.addEventListener('alpine:init', () => {
  Alpine.data('requestList', () => ({
    showDetails: false,
    selected: {},

    // Called when user clicks a table row
    selectRow(request) {
      this.selected = request;
      this.showDetails = true;
      console.log('Selected request:', request);
    },

    // Optional: open modal or show a full details view
    viewFullDetails(selected) {
      Swal.fire({
        html: `
          <div class="text-black text-sm max-w-full overflow-x-auto">

            <!-- HEADER -->
            <div class="flex flex-col items-center justify-center mb-4">
              <img src="/public/assets/img/usep.png" class="w-20 h-20 mb-2 mt-4" alt="USeP Logo">
              <h2 class="text-lg font-semibold text-center">VEHICLE REQUEST DETAILS</h2>
            </div>

            <!-- TRIP INFORMATION -->
            <h4 class="text-base font-semibold mb-2">Trip Information</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
              <div>
                <label class="text-xs mb-1 flex font-medium">Tracking No.</label>
                <p class="view-field font-normal">${selected.tracking_id}</p>
              </div>
              <div>
                <label class="text-xs mb-1 flex font-medium">Request Date</label>
                <p class="view-field font-normal">${selected.date_request}</p>
              </div>
              <div>
                <label class="text-xs mb-1 flex font-medium">Requester</label>
                <p class="view-field font-normal">${selected.requester_name}</p>
              </div>
              <div>
                <label class="text-xs mb-1 flex font-medium">Contact No</label>
                <p class="view-field font-normal">${selected.contact || 'N/A'}</p>
              </div>
              <div>
                <label class="text-xs mb-1 flex font-medium">Travel Date</label>
                <p class="view-field font-normal">${selected.travel_date}</p>
              </div>
              <div>
                <label class="text-xs mb-1 flex font-medium">Return Travel Date</label>
                <p class="view-field font-normal">${selected.return_date}</p>
              </div>
              <div>
                <label class="text-xs mb-1 flex font-medium">Destination</label>
                <p class="view-field font-normal">${selected.travel_destination}</p>
              </div>
              <div>
                <label class="text-xs mb-1 flex font-medium">Departure & Return Time</label>
                <p class="view-field font-normal">${selected.depret_time || 'N/A'}</p>
              </div>
            </div>
              <div class="w-full">
                <label class="text-xs mb-1 block font-medium">Trip Purpose</label>
                <p class="view-field font-normal">${selected.trip_purpose}</p>
              </div>

            <hr class="my-6 border-gray-300">

            <!-- PASSENGERS -->
            <h4 class="text-base font-semibold mb-2">Passenger Information</h4>
            <div class="space-y-2 mb-6">
              ${selected.passengers?.length > 0
                ? selected.passengers.map(p => `
                  <p class="view-field font-normal">${p.name || p}</p>
                `).join('')
                : `<p class="text-xs text-gray-500">No Passengers</p>`}
            </div>

            <hr class="my-6 border-gray-300"></hr>

            <!-- VEHICLE & DRIVER -->
            <h4 class="text-base font-semibold mb-2">Assignment Details</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-6">
              <div>
                <label class="text-xs mb-1 flex font-medium">Assigned Vehicle</label>
                <p class="view-field font-normal">${selected.vehicle_name || 'Not Assigned'}</p>
              </div>
              <div>
                <label class="text-xs mb-1 flex font-medium">Assigned Driver</label>
                <p class="view-field font-normal">${selected.driver_name || 'Not Assigned'}</p>
              </div>
            </div>
            <div class="w-full">
                <label class="text-xs mb-1 block font-medium">Status</label>
                <p class="view-field font-normal">${selected.req_status}</p>
              </div>

            <p class="text-xs text-gray-500 text-center mt-6">
              © 2025 University of Southeastern Philippines — U-Request System
            </p>

          </div>
        `,
        width: 600,
        customClass: {
          popup: 'swal-custom-popup text-black m-10',
          confirmButton: 'btn btn-primary font-normal',
          textColor: 'text-sm'
        },
        confirmButtonText: 'Close',
        confirmButtonColor: '#800000'
      });
    }
  }));
});
document.addEventListener("alpine:init", () => {
    Alpine.data("vehicleDropdown", () => ({
        vehicles: [],

        async init() {
            const control_no  = this.$root.getAttribute("data-controlno");
            const travel_date = this.$root.getAttribute("data-traveldate");
            const return_date = this.$root.getAttribute("data-returndate");

            console.log("INIT PARAMS:", control_no, travel_date, return_date);

            await this.loadAvailableVehicles(control_no, travel_date, return_date);
        },

        async loadAvailableVehicles(control_no, travel_date, return_date) {

            const params = new URLSearchParams({
                control_no,
                travel_date,
                return_date
            });

            try {
                const res = await fetch(`../../../controllers/VehicleController.php?getVehicle=1&${params}`);
                const data = await res.json();

                this.vehicles = data;
                console.log("AVAILABLE VEHICLES:", data);
            } 
            catch (err) {
                console.error("VEHICLE LOAD ERROR:", err);
                Swal.fire("Error", "Failed to load vehicles.", "error");
            }
        }
    }));
});

// <!-- SOURCE OF FUND -->
//             <h4 class="text-base font-semibold mb-2">Source of Fund</h4>
//             <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
//               <div>
//                 <label class="text-xs mb-1 block font-medium">Fuel</label>
//                 <p class="view-field w-full">${selected.source_of_fuel || 'N/A'}</p>
//               </div>
//               <div>
//                 <label class="text-xs mb-1 block font-medium">Oil</label>
//                 <p class="view-field w-full">${selected.source_of_oil || 'N/A'}</p>
//               </div>
//               <div>
//                 <label class="text-xs mb-1 block font-medium">Repair/Maintenance</label>
//                 <p class="view-field w-full">${selected.source_of_repair_maintenance || 'N/A'}</p>
//               </div>
//               <div>
//                 <label class="text-xs mb-1 block font-medium">Driver/Assistant Per Diem</label>
//                 <p class="view-field w-full">${selected.source_of_driver_assistant_per_diem || 'N/A'}</p>
//               </div>
//             </div>
//            