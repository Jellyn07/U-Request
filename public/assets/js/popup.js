// WORK HISTORY PERSONNEL

function viewWorkHistory(staff_id) {
  if (!staff_id) {
    Swal.fire('No Staff ID', 'Please select a personnel first.', 'warning');
    return;
  }

  fetch('../../../controllers/PersonnelController.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: new URLSearchParams({ get_work_history: true, staff_id })
  })
  .then(res => res.json())
  .then(data => {
    if (data.length === 0) {
      Swal.fire('No Records Found', 'This staff has no work history yet.', 'info');
      return;
    }

    let tableRows = data.map(row => `
      <tr>
        <td class="border px-2 py-1 text-center">${row.request_id}</td>
        <td class="border px-2 py-1">${row.request_Type}</td>
       <td class="border px-2 py-1 text-center">
        ${new Date(row.date_finished).toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric'
        })}
        </td>

      </tr>
    `).join('');

    Swal.fire({
      title: 'Work History',
      html: `
        <div class="overflow-auto max-h-60">
          <table class="min-w-full text-xs text-center border border-gray-300">
            <thead class="bg-gray-100">
              <tr>
                <th class="border px-2 py-1 text-center">Request ID</th>
                <th class="border px-2 py-1">Request Type</th>
                <th class="border px-2 py-1 text-center">Date Finished</th>
              </tr>
            </thead>
            <tbody>${tableRows}</tbody>
          </table>
        </div>
      `,
       confirmButtonText: 'Close',
       confirmButtonColor: '#800000', // 🟤 maroon color
       width: 600,
       background: '#fff',
       customClass: {
         popup: 'border-t-4 border-red-500 shadow-lg rounded-lg'
      }
    });
  })
  .catch(err => {
    console.error('Error fetching work history:', err);
    Swal.fire('Error', 'Unable to fetch work history.', 'error');
  });
}
