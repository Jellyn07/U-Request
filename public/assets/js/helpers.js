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