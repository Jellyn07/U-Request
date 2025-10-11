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

// Automatically apply when typing or leaving the input field
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('input[name="first_name"], input[name="last_name"], input[name="first_name[]"], input[name="last_name[]"], input[name="material_desc"], textarea[name="description"], input[name="purpose_of_trip"], input[name="travel_destination"]'
    
  ).forEach(input => {
    input.addEventListener('blur', () => {
      input.value = formatNameJS(input.value);
    });
  });
});
