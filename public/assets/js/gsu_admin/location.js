async function handleLocationAction(formData) {
  try {
    const res = await fetch("../../../controllers/LocationController.php", {
      method: "POST",
      body: formData
    });

    const data = await res.json();

    Swal.fire({
      icon: data.status,
      title: data.message,
      showConfirmButton: false,
      timer: 1500
    }).then(() => {
      if (data.status === "success") {
        location.reload(); // Refresh the page automatically
      }
    });

  } catch (err) {
    console.error(err);
    Swal.fire({
      icon: "error",
      title: "Request failed",
      text: "Something went wrong."
    });
  }
}
// Add
document.getElementById("addLocationForm").addEventListener("submit", function(e) {
  e.preventDefault();
  const formData = new FormData(this);
  formData.append("action", "add");
  handleLocationAction(formData);
});

// Update
function confirmUpdate() {
  const form = document.getElementById('locationForm');
  const formData = new FormData(form);
  formData.append('action', 'update');

  Swal.fire({
    title: 'Confirm Update?',
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: 'Yes, save changes'
  }).then(result => {
    if (result.isConfirmed) handleLocationAction(formData);
  });
}

// Delete
function deleteLocation(id) {
  if (!id) return;
  const formData = new FormData();
  formData.append('action', 'delete');
  formData.append('location_id', id);

  Swal.fire({
    title: 'Delete Location?',
    text: 'This action cannot be undone.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, delete it'
  }).then(result => {
    if (result.isConfirmed) handleLocationAction(formData);
  });
}

function toggleBuildingOption(option) {
    document.getElementById("existingBuilding").classList.toggle("hidden", option !== "existing");
    document.getElementById("newBuilding").classList.toggle("hidden", option !== "new");
}

document.addEventListener("DOMContentLoaded", function () {
  const searchInput = document.getElementById("searchInput");
  const sortSelect = document.getElementById("sortFilter");
  const sortUnit = document.getElementById("sortUnit");
  const tableBody = document.getElementById("body_table");

  if (!searchInput || !sortSelect || !tableBody || !sortUnit) return;

  function applyFilters() {
    const searchValue = searchInput.value.toLowerCase().trim();
    const sortValue = sortSelect.value;
    const unitValue = sortUnit.value;
    const rows = Array.from(tableBody.querySelectorAll("tr"));

    // 🔍 SEARCH & UNIT FILTER
    rows.forEach(row => {
      const unit = row.children[2]?.textContent.toLowerCase() || "";
      const building = row.children[3]?.textContent.toLowerCase() || "";
      const exact = row.children[4]?.textContent.toLowerCase() || "";

      const searchMatch = [unit, building, exact].some(val => val.includes(searchValue));
      const unitMatch = unitValue === "All" || unit === unitValue.toLowerCase();

      row.style.display = (searchMatch && unitMatch) ? "" : "none";
    });

    // 🧩 SORTING FUNCTION
    const visibleRows = rows.filter(row => row.style.display !== "none");

    if (sortValue === "id") {
      visibleRows.sort((a, b) => {
        const idA = parseInt(a.children[0]?.textContent.trim()) || 0;
        const idB = parseInt(b.children[0]?.textContent.trim()) || 0;
        return idA - idB;
      });
    } else if (sortValue === "az" || sortValue === "za") {
      const colIndex = 3; // Sort by Building column
      visibleRows.sort((a, b) => {
        const textA = a.children[colIndex]?.textContent.toLowerCase().trim() || "";
        const textB = b.children[colIndex]?.textContent.toLowerCase().trim() || "";
        return sortValue === "az"
          ? textA.localeCompare(textB)
          : textB.localeCompare(textA);
      });
    }

    // Re-append sorted rows
    visibleRows.forEach(row => tableBody.appendChild(row));
  }

  // 🔄 Event bindings
  searchInput.addEventListener("input", applyFilters);
  sortSelect.addEventListener("change", applyFilters);
  sortUnit.addEventListener("change", applyFilters);

  // Run once initially
  applyFilters();
});

function locationModal() {
    return {
        showDetails: false,
        addLocation: false,
        selected: {},
        buildingOption: 'existing',

        async fetchBuildings(unit) {
            if (!unit) {
                this.$refs.existingBuilding.innerHTML = '<option value="">Select Building</option>';
                return;
            }
            
            try {
                const formData = new FormData();
                formData.append('action', 'get_buildings');
                formData.append('unit', unit);

                const res = await fetch("../../../controllers/LocationController.php", {
                    method: "POST",
                    body: formData
                });
                const data = await res.json();

                // Clear and populate the building dropdown
                this.$refs.existingBuilding.innerHTML = '<option value="">Select Building</option>';
                data.forEach(b => {
                    const option = document.createElement('option');
                    option.value = b.building;
                    option.textContent = b.building;
                    this.$refs.existingBuilding.appendChild(option);
                });
            } catch (err) {
                console.error("Failed to fetch buildings:", err);
            }
        }
    }
}