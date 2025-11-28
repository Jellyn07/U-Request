document.getElementById("add_request").addEventListener("click", function () {
    Swal.fire({
        width: "700px",
        buttonsStyling: false,
        customClass: {
          popup: 'swal-custom-popup text-black m-10',
          confirmButton: 'btn btn-primary font-normal',
          cancelButton: 'btn btn-secondary font-normal ml-4',
          textColor: 'text-sm'
        },
        showCancelButton: true,
        confirmButtonText: "Submit",
        cancelButtonText: "Cancel",
        html: `
        <form 
            id="vehicle-form" 
            action="../../../controllers/VehicleRequestController.php" 
            method="POST" 
            class="w-full transition"
            >
            <input type="hidden" name="form_action" value="submitRequest">
            <!-- HEADER -->
            <div class="flex flex-col items-center justify-center mb-6">
                <img src="${BASE_URL}/assets/img/usep.png" alt="USeP Logo" class="w-20 h-20 mb-2 mt-3">
                <h2 class="text-center text-2xl font-semibold">
                VEHICLE REQUEST FORM
                </h2>
                <p class="text-xs text-gray-500 mt-1">Fields marked with <span class="text-red-500">*</span> are required.</p>
            </div>

            <h4 class="text-base font-semibold mb-2">Requester</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                <label class="text-sm mb-1 block text-left">First Name<span class="text-red-500">*</span></label>
                <input type="text" required class="input-field w-full ">
                </div>
                <div>
                <label class="text-sm mb-1 block text-left">Last Name<span class="text-red-500">*</span></label>
                <input type="text" required class="input-field w-full ">
                </div>
            </div>

            <hr class="my-6 border-gray-400">
            <!-- TRIP DETAILS -->
            <h4 class="text-base font-semibold mb-2">Trip Information</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                <label class="text-sm mb-1 block text-left">Purpose of Trip <span class="text-red-500">*</span></label>
                <input type="text" name="purpose_of_trip" placeholder="Ex. Field Trip" required class="input-field w-full ">
                </div>
                <div>
                <label class="text-sm mb-1 block text-left">Travel Destination <span class="text-red-500">*</span></label>
                <input type="text" name="travel_destination" required placeholder="Ex. Davao City" class="input-field w-full ">
                </div>
                <div>
                <label class="text-sm mb-1 block text-left">Date of Travel <span class="text-red-500">*</span></label>
                <input type="date" id="date_of_travel" name="date_of_travel" required class="input-field w-full">
                </div>
                <div>
                <label class="text-sm mb-1 block text-left">Date of Return <span class="text-red-500">*</span></label>
                <input type="date" id="date_of_return" name="date_of_return" required class="input-field w-full">
                </div>
                <div>
                <label class="text-sm mb-1 block text-left">Time of Departure <span class="text-red-500">*</span></label>
                <input type="time" name="time_of_departure" required class="input-field w-full">
                </div>
                <div>
                <label class="text-sm mb-1 block text-left">Time of Return <span class="text-red-500">*</span></label>
                <input type="time" name="time_of_return" required class="input-field w-full">
                </div>
            </div>

            <hr class="my-6 border-gray-400">

            <!-- PASSENGERS -->
            <h4 class="text-base font-semibold mb-2">Passenger Information</h4>
            <div id="passenger-fields" class="space-y-3 mb-6">
                <div class="flex gap-2 passenger-row items-end">
                <div class="w-1/2">
                    <label class="text-sm mb-1 block text-left">First Name <span class="text-red-500">*</span></label>
                    <input type="text" name="first_name[]" required class="input-field w-full">
                </div>
                <div class="w-1/2">
                    <label class="text-sm mb-1 block text-left">Last Name <span class="text-red-500">*</span></label>
                    <input type="text" name="last_name[]" required class="input-field w-full">
                </div>
                <button type="button" id="add-passenger" onclick="addPassengerField()" title="Add Passenger"
                    class="bg-primary hover:bg-secondary text-white rounded-full w-9 h-9 flex justify-center shadow-md items-center"
                >
                    <img src="${BASE_URL}/assets/img/add_white.png" alt="Add" class="w-3 h-3">
                </button>
                </div>
            </div>

            <hr class="my-6 border-gray-400">

            <!-- SOURCE OF FUND -->
            <h4 class="text-base font-semibold mb-2">Source of Fund</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                <label class="text-sm mb-1 block text-left">Fuel <span class="text-red-500">*</span></label>
                <input type="text" name="source_of_fuel" placeholder="Ex. Donation" required class="input-field w-full">
                </div>
                <div>
                <label class="text-sm mb-1 block text-left">Oil <span class="text-red-500">*</span></label>
                <input type="text" name="source_of_oil" placeholder="Ex. Collection" required class="input-field w-full">
                </div>
                <div>
                <label class="text-sm mb-1 block text-left">Repair/Maintenance <span class="text-red-500">*</span></label>
                <input type="text" name="source_of_repair_maintenance" placeholder="Ex. Own Money" required class="input-field w-full">
                </div>
                <div>
                <label class="text-sm mb-1 block text-left">Driver/Assistant Per Diem <span class="text-red-500">*</span></label>
                <input type="text" name="source_of_driver_assistant_per_diem" placeholder="Ex. Collection" required class="input-field w-full">
                </div>
            </div>

            <hr class="my-6 border-gray-400">

            <!-- CONTACT & CERTIFICATION -->
            <!-- <div class="mb-6">
                <label class="text-sm mb-1 block text-left">Contact No <span class="text-red-500">*</span></label>
                <input type="text" name="contactNo" required class="input-field w-full">
            </div> -->

            <div class="flex items-start mb-6">
                <input type="checkbox" name="certify" required class="mt-0.5 mr-2 h-4 w-4 text-red-600 border-gray-300 rounded focus:ring-red-600">
                <p class="text-sm">
                I hereby certify that all information provided in this form is true and correct.
                </p>
            </div>

            <!-- FOOTER -->
            <p class="text-xs text-gray-500 text-center mt-6">
                © 2025 University of Southeastern Philippines — U-Request System
            </p>
        </form>
        `
    });
});
