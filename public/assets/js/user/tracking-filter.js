document.addEventListener('DOMContentLoaded', () => {
  const repairStatuses = ['To Inspect', 'In Progress', 'Completed'];
  const vehicleStatuses = ['Pending', 'Approved', 'Disapproved'];

  const statusSelect = document.getElementById('statusSelect');
  const form = document.getElementById('filterForm');
  const typeInput = document.getElementById('typeInput');

  if (!form || !statusSelect || !typeInput) return;

  // 🔄 When clicking "Repair" or "Vehicle" tab
  form.querySelectorAll('button[data-type]').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      const newType = e.target.getAttribute('data-type');
      typeInput.value = newType;
      updateStatusOptions(newType);
      form.submit();
    });
  });

  // 🔄 Update status dropdown dynamically
  function updateStatusOptions(type) {
    const options = type === 'vehicle' ? vehicleStatuses : repairStatuses;
    const currentStatus = statusSelect.value;
    statusSelect.innerHTML = '<option value="">All Status</option>';
    options.forEach(opt => {
      const option = document.createElement('option');
      option.value = opt;
      option.textContent = opt;
      if (opt === currentStatus) option.selected = true;
      statusSelect.appendChild(option);
    });
  }

  // 🔄 Auto-submit on status or sort change
  statusSelect.addEventListener('change', () => form.submit());
  document.querySelector('select[name="sort"]').addEventListener('change', () => form.submit());
});
