document.addEventListener('DOMContentLoaded', () => {
  const calendarEl = document.getElementById('calendar');
  const monthYearEl = document.getElementById('monthYear');
  const prevBtn = document.getElementById('prevMonth');
  const nextBtn = document.getElementById('nextMonth');
  const modal = document.getElementById('tripModal');
  const closeModal = document.getElementById('closeModal');
  const modalBody = document.getElementById('modalBody');

  let today = new Date();
  let currentMonth = today.getMonth();
  let currentYear = today.getFullYear();

  const statusColors = {
    "Pending": "bg-yellow-200 text-yellow-800 hover:bg-yellow-300",
    "Approved": "bg-blue-200 text-blue-800 hover:bg-blue-300",
    "On Going": "bg-orange-100 text-orange-700 hover:bg-orange-300",
    "Rejected/Cancelled": "bg-red-200 text-red-800 hover:bg-red-300",
    "Cancelled": "bg-red-200 text-red-800 hover:bg-red-300",
    "Rejected": "bg-red-200 text-red-800 hover:bg-red-300",
    "Completed": "bg-green-200 text-green-800 hover:bg-green-300"
  };

  function renderCalendar(month, year) {
    // Clear existing day cells
    calendarEl.querySelectorAll('.day-cell').forEach(e => e.remove());

    const firstDay = new Date(year, month, 1).getDay();
    const lastDate = new Date(year, month + 1, 0).getDate();
    const monthNames = ["January","February","March","April","May","June","July","August","September","October","November","December"];
    monthYearEl.textContent = `${monthNames[month]} ${year}`;

    for (let i = 0; i < firstDay; i++) {
      calendarEl.appendChild(document.createElement('div')).className = 'day-cell p-2 h-24';
    }

    for (let day = 1; day <= lastDate; day++) {
      const dateStr = `${year}-${String(month+1).padStart(2,'0')}-${String(day).padStart(2,'0')}`;
      const dayCell = document.createElement('div');
      dayCell.className = 'day-cell border border-gray-400 p-2 h-24 flex flex-col rounded-lg overflow-auto';

      const dayNum = document.createElement('span');
      dayNum.className = 'ml-0.5 mr-auto p-1 text-sm font-semibold bg-white rounded-lg';
      dayNum.textContent = day;
      dayCell.appendChild(dayNum);

      if (trips[dateStr]) {
        const tripsDiv = document.createElement('div');
        tripsDiv.className = 'flex flex-col gap-1 mt-1';
        trips[dateStr].forEach(trip => {
          const tripSpan = document.createElement('span');
          const colorClass = statusColors[trip.status] || "bg-gray-200 text-gray-800";
          tripSpan.className = `${colorClass} font-bold rounded px-2 py-0.5 text-xs cursor-pointer`;
          tripSpan.textContent = `${trip.vehicle} - ${trip.purpose}`;
          tripSpan.addEventListener('click', () => {
            modalBody.innerHTML = `
              <div>
                <label class="text-xs text-gray-600 font-semibold">Vehicle</label>
                <p type="text" disabled class="w-full view-field cursor-not-allowed mt-0">${trip.vehicle}</p>
              </div>
              <div>
                <label class="text-xs text-gray-600 font-semibold">Purpose</label>
                <p type="text" disabled class="w-full view-field cursor-not-allowed mt-0">${trip.purpose}</p>
              </div>
              <div>
                <label class="text-xs text-gray-600 font-semibold">Destination</label>
                <p type="text" disabled class="w-full view-field cursor-not-allowed mt-0">${trip.destination}</p>
              </div>
              <div>
                <label class="text-xs text-gray-600 font-semibold">Status</label>
                <p type="text" disabled class="w-full view-field cursor-not-allowed mt-0">${trip.status}</p>
              </div>
              ${trip.status === 'Rejected/Cancelled' && trip.reason ? `
              <div>
                <label class="text-xs text-gray-600 font-semibold">Reason</label>
                <p type="text" disabled class="w-full view-field cursor-not-allowed mt-0">${trip.reason}</p>
              </div>
              ` : ''}
              <div>
                <label class="text-xs text-gray-600 font-semibold">Time</label>
                <p type="text" disabled class="w-full view-field cursor-not-allowed mt-0">${trip.time}</p>
              </div>
              <div>
                <label class="text-xs text-gray-600 font-semibold">Return Date</label>
                <p type="text" disabled class="w-full view-field cursor-not-allowed mt-0">${trip.return_date_formatted}</p>
              </div>
            `;
            modal.classList.remove('hidden');
          });
          tripsDiv.appendChild(tripSpan);
        });
        dayCell.appendChild(tripsDiv);
      }
      calendarEl.appendChild(dayCell);
    }
  }

  prevBtn.addEventListener('click', () => {
    currentMonth--;
    if (currentMonth < 0) { currentMonth = 11; currentYear--; }
    renderCalendar(currentMonth, currentYear);
  });

  nextBtn.addEventListener('click', () => {
    currentMonth++;
    if (currentMonth > 11) { currentMonth = 0; currentYear++; }
    renderCalendar(currentMonth, currentYear);
  });

  closeModal.addEventListener('click', () => modal.classList.add('hidden'));
  window.addEventListener('click', e => { if (e.target === modal) modal.classList.add('hidden'); });

  renderCalendar(currentMonth, currentYear);
});
