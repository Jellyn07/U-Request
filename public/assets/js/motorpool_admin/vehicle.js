function previewImage(event) {
    const file = event.target.files[0];
    const previewContainer = document.getElementById("preview-container");
    const preview = document.getElementById("preview");
    const uploadArea = document.getElementById("upload-area");

    if (file) {
        const reader = new FileReader();
          reader.onload = function(e) {
            preview.src = e.target.result;
            previewContainer.classList.remove("hidden");
            uploadArea.classList.add("hidden"); // Hide drag-drop area when uploaded
          };
          reader.readAsDataURL(file);
        }
    }

      function removePreview(e) {
        e.stopPropagation();
        const fileInput = document.getElementById("img");
        const previewContainer = document.getElementById("preview-container");
        const uploadArea = document.getElementById("upload-area");

        fileInput.value = "";
        previewContainer.classList.add("hidden");
        uploadArea.classList.remove("hidden");
      }

      function handleDragOver(e) {
        e.preventDefault();
        e.currentTarget.classList.add("bg-gray-200");
      }

      function handleDragLeave(e) {
        e.preventDefault();
        e.currentTarget.classList.remove("bg-gray-200");
      }

      function handleDrop(e) {
        e.preventDefault();
        e.currentTarget.classList.remove("bg-gray-200");
        const fileInput = document.getElementById("img");
        const files = e.dataTransfer.files;
        if (files.length > 0) {
          fileInput.files = files;
          previewImage({ target: fileInput });
        }
      }

      document.addEventListener('DOMContentLoaded', () => {
      const searchInput = document.getElementById('searchUser');
      const sortSelect = document.getElementById('sortVehicle');
      const vehicleCards = document.querySelectorAll('.vehicle-card');

      function filterVehicles() {
        const searchText = searchInput.value.toLowerCase();
        const selectedType = sortSelect.value.toLowerCase();

        vehicleCards.forEach(card => {
        const name = card.dataset.name;
        const type = card.dataset.type;
        const matchesSearch = name.includes(searchText);
        const matchesType = selectedType === '' || type === selectedType;
        card.style.display = matchesSearch && matchesType ? 'block' : 'none';
        });
    }

  searchInput.addEventListener('input', filterVehicles);
  sortSelect.addEventListener('change', filterVehicles);
});

function previewProfile(event) {
    const output = document.getElementById('profile-preview');
    if (event.target.files.length > 0) {
        output.src = URL.createObjectURL(event.target.files[0]);
    }
}
function toggleHistory(vehicle_id, btn) {
    loadVehicleData(vehicle_id, btn, 'get_travel_history', 'No Travel History Found', 'travel-history');
}

function toggleSchedule(vehicle_id, btn) {
    loadVehicleData(vehicle_id, btn, 'get_scheduled_trips', 'No Scheduled Trips Found', 'scheduled-trips');
}

function loadVehicleData(vehicle_id, btn, action, emptyMsg, className) {
    const container = btn.nextElementSibling;
    if (!container) return;

    // If currently open, close it
    const isVisible = !container.classList.contains('hidden');
    if (isVisible) {
        container.classList.add('hidden');
        container.innerHTML = "";
        btn.classList.remove("active-dropdown");
        return;
    }

    // ✅ Close all other open sections
    document.querySelectorAll('.travel-history, .scheduled-trips').forEach(e => {
        e.classList.add('hidden');
        e.innerHTML = "";
    });
    document.querySelectorAll('.active-dropdown').forEach(b => b.classList.remove('active-dropdown'));

    btn.classList.add("active-dropdown");
    container.classList.remove('hidden');
    container.innerHTML = `<p class="text-sm text-gray-500 italic">Loading...</p>`;

    fetch('../../../controllers/VehicleController.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `${action}=1&vehicle_id=${vehicle_id}`
    })
    .then(res => res.json())
    .then(data => {
        if (!data || data.length === 0) {
            container.innerHTML = `<p class="text-sm text-gray-500 italic">${emptyMsg}</p>`;
            return;
        }

        // Build rows
        const tableRows = data.map(item => {
            const date = new Date(item.travel_date || item.sched_date);
            const formattedDate = date.toLocaleDateString('en-US', {
                month: 'short',
                day: 'numeric',
                year: 'numeric'
            });
            return `
                <tr>
                    <td class="border px-2 py-1 text-center">${formattedDate}</td>
                    <td class="border px-2 py-1">${item.trip_purpose || item.schedule_purpose}</td>
                    <td class="border px-2 py-1 text-center">${item.driver_name || '—'}</td>
                </tr>
            `;
        }).join('');

        container.innerHTML = `
            <div class="overflow-auto max-h-60 mt-1">
                <table class="min-w-full text-xs text-center border border-gray-300 rounded-lg">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-2 py-1 text-center">Date</th>
                            <th class="border px-2 py-1">Purpose</th>
                            <th class="border px-2 py-1 text-center">Driver</th>
                        </tr>
                    </thead>
                    <tbody>${tableRows}</tbody>
                </table>
            </div>
        `;
    })
    .catch(err => {
        console.error(err);
        container.innerHTML = `<p class="text-sm text-red-500">Failed to load data.</p>`;
    });
}

// ✅ Add this helper to reset both sections when switching vehicles
function resetVehicleSections() {
    document.querySelectorAll('.travel-history, .scheduled-trips').forEach(e => {
        e.classList.add('hidden');
        e.innerHTML = '';
    });
    document.querySelectorAll('.active-dropdown').forEach(b => b.classList.remove('active-dropdown'));
}

function openDetails(data) {
    this.selected = data;
    this.showDetails = true;
    this.editing = false;

 // Reset Travel History
   document.querySelectorAll('.travel-history').forEach(e => {
    e.innerHTML = '';
    e.classList.add('hidden'); // hide section
    });

    // Reset Scheduled Trips
    document.querySelectorAll('.scheduled-trips').forEach(e => {
        e.innerHTML = '';
        e.classList.add('hidden'); // hide section
    });

    // Reset any toggled button classes
    document.querySelectorAll('.history-open').forEach(btn => btn.classList.remove('history-open'));
    document.querySelectorAll('.schedule-open').forEach(btn => btn.classList.remove('schedule-open'));
}