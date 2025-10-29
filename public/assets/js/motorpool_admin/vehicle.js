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
    const container = btn.nextElementSibling; // travel-history div
    if (!container) return;

    const isVisible = !container.classList.contains('hidden');
    if (isVisible) {
        container.classList.add('hidden');
        return;
    }

    container.classList.remove('hidden');
    container.innerHTML = '<p class="text-sm text-gray-500">Loading...</p>';

    fetch('../../../controllers/VehicleController.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `get_travel_history=1&vehicle_id=${vehicle_id}`
    })
    .then(res => res.text()) // temporarily as text
    .then(text => {
        let data;
        try {
            data = JSON.parse(text);
        } catch (err) {
            console.error('Invalid JSON:', text);
            container.innerHTML = '<p class="text-sm text-red-500">Failed to load travel history</p>';
            return;
        }

        if (!data || data.length === 0) {
            container.innerHTML = '<p class="text-sm text-gray-500">No Travel History</p>';
        } else {
            const listItems = data.map(h => {
                const date = new Date(h.travel_date).toLocaleDateString('en-US', { year:'numeric', month:'short', day:'numeric' });
                return `<li class="text-sm p-1 border-b border-gray-200">${date} - ${h.trip_purpose} - Driver: ${h.driver_name}</li>`;
            }).join('');
            container.innerHTML = `<ul class="space-y-1">${listItems}</ul>`;
        }
    })
    .catch(err => {
        console.error(err);
        container.innerHTML = '<p class="text-sm text-red-500">Failed to load travel history</p>';
    });
}
