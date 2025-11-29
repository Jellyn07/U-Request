document.addEventListener('DOMContentLoaded', () => {
    const sortSelect = document.getElementById('sortUsers'); // adjust selector if multiple
    const tableBody = document.getElementById('usersTable');
  
    sortSelect.addEventListener('change', () => {
      const rows = Array.from(tableBody.querySelectorAll('tr'));
      const direction = sortSelect.value; // 'az' or 'za'
  
      rows.sort((a, b) => {
        const nameA = (a.dataset.firstname + ' ' + a.dataset.lastname).toLowerCase();
        const nameB = (b.dataset.firstname + ' ' + b.dataset.lastname).toLowerCase();

  
        if (nameA < nameB) return direction === 'az' ? -1 : 1;
        if (nameA > nameB) return direction === 'az' ? 1 : -1;
        return 0;
      });
  
      // Remove old rows
      tableBody.innerHTML = '';
  
      // Append sorted rows
      rows.forEach(row => tableBody.appendChild(row));
    });
  });

// Capitalize each word in a name
function formatNameJS(name) {
  return name
    .trim()
    .toLowerCase()
    .replace(/\b\w/g, c => c.toUpperCase());
}

// Automatically apply formatting on blur for current and future inputs
document.addEventListener('blur', (e) => {
  const el = e.target;

  if (
    el.matches(
      'input[name="first_name"], input[name="last_name"], input[name="first_name[]"], input[name="last_name[]"], input[name="fn"], input[name="ln"], input[name="material_desc"], textarea[name="description"], input[name="purpose_of_trip"], input[name="travel_destination"], input[name="source_of_fuel"], input[name="source_of_oil"], input[name="source_of_repair_maintenance"], input[name="source_of_driver_assistant_per_diem"], input[name="new_building"], input[name="exact_location"], input[name="vehicle_name"], input[name="vehicle_type"], input[name="approved_by"]'
    )
  ) {
    el.value = formatNameJS(el.value);
  }
}, true); // useCapture true so blur event is captured during capturing phase
