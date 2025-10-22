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

const searchInput = document.getElementById("searchInput");
const unitFilter = document.getElementById("unitFilter");
const tableBody = document.getElementById("table");

function applyFilters() {
    const searchValue = searchInput.value.toLowerCase().trim();
    const unitValue = unitFilter.value.toLowerCase().trim();

    const rows = Array.from(tableBody.querySelectorAll("tr"));

    rows.forEach(row => {
        const unitText = row.children[2]?.textContent.toLowerCase().trim() || ""; // Unit column
        const buildingText = row.children[3]?.textContent.toLowerCase().trim() || ""; // Building column
        const exactLocationText = row.children[4]?.textContent.toLowerCase().trim() || ""; // Exact Location column

        const searchMatches = unitText.includes(searchValue) || buildingText.includes(searchValue) || exactLocationText.includes(searchValue);
        const unitMatches = unitValue === "all" || unitText === unitValue;

        row.style.display = (searchMatches && unitMatches) ? "" : "none";
    });
}

// Event listeners
searchInput.addEventListener("input", applyFilters);
unitFilter.addEventListener("change", applyFilters);

// Initial filter on page load
applyFilters();