<?php
session_start();
require_once __DIR__ . '/../../../config/constants.php';

// Sample trips data
$trips = [
    '2025-10-21' => [
        ['vehicle' => 'Van 1', 'purpose' => 'Field Trip'],
        ['vehicle' => 'Van 1', 'purpose' => 'Field Trip'],
        ['vehicle' => 'Van 1', 'purpose' => 'Field Trip'],
        ['vehicle' => 'Truck 2', 'purpose' => 'Delivery']
    ],
    '2025-10-25' => [
        ['vehicle' => 'Van 2', 'purpose' => 'Conference'],
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>U-Request | Motorpool Schedule</title>
  <link rel="stylesheet" href="/public/assets/css/output.css">
  <link rel="icon" href="/public/assets/img/upper_logo.png"/>
</head>
<body class="bg-gray-100">
  <?php include COMPONENTS_PATH . '/motorpool_menu.php'; ?>
  <main class="ml-16 md:ml-64 p-6 flex flex-col min-h-screen">
    <h1 class="text-2xl font-bold mb-4">Schedules</h1>
    <!-- Header Controls -->
    <div class="flex justify-center items-center mb-4 gap-5">
      <button id="prevMonth">
        <img src="/public/assets/img/left-arrow.png" alt="Previous" class="w-4 h-4">
      </button>
      <h2 id="monthYear" class="text-lg font-semibold text-center"></h2>
      <button id="nextMonth">
        <img src="/public/assets/img/right-arrow.png" alt="Next" class="w-4 h-4">
      </button>
    </div>

    <div class="bg-white rounded-lg p-4 shadow-md h-[595px]">
      <!-- Calendar Grid -->
      <div id="calendar" class="grid grid-cols-7 gap-2 text-sm p-1">
        <!-- Weekday Headers -->
        <div class="text-center font-semibold bg-red-200 py-2 rounded-lg">Sun</div>
        <div class="text-center font-semibold bg-red-200 py-2 rounded-lg">Mon</div>
        <div class="text-center font-semibold bg-red-200 py-2 rounded-lg">Tue</div>
        <div class="text-center font-semibold bg-red-200 py-2 rounded-lg">Wed</div>
        <div class="text-center font-semibold bg-red-200 py-2 rounded-lg">Thu</div>
        <div class="text-center font-semibold bg-red-200 py-2 rounded-lg">Fri</div>
        <div class="text-center font-semibold bg-red-200 py-2 rounded-lg">Sat</div>
      </div>
    </div>
  </main>

  <!-- Tailwind Modal -->
  <div id="tripModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-11/12 max-w-md p-6 relative">
      <button id="closeModal" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">&times;</button>
      <h3 class="font-bold text-lg mb-4">Trip Details</h3>
      <div id="modalBody" class="flex flex-col gap-2"></div>
    </div>
  </div>

  <script>
    const trips = <?php echo json_encode($trips); ?>;
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

    function renderCalendar(month, year){
      calendarEl.querySelectorAll('.day-cell').forEach(e => e.remove());

      const firstDay = new Date(year, month, 1).getDay();
      const lastDate = new Date(year, month + 1, 0).getDate();
      const monthNames = ["January","February","March","April","May","June","July","August","September","October","November","December"];
      monthYearEl.textContent = `${monthNames[month]} ${year}`;

      for(let i=0; i<firstDay; i++){
        const blankCell = document.createElement('div');
        blankCell.className = 'day-cell p-2 h-24';
        calendarEl.appendChild(blankCell);
      }

      for(let day=1; day<=lastDate; day++){
        const dateStr = `${year}-${String(month+1).padStart(2,'0')}-${String(day).padStart(2,'0')}`;
        const dayCell = document.createElement('div');
        dayCell.className = 'day-cell border border-gray-400 p-2 h-24 flex flex-col rounded-lg overflow-auto';
        const dayNum = document.createElement('span');
        dayNum.className = 'ml-0.5 mr-auto p-1 text-sm font-semibold top-0 sticky bg-white rounded-lg';
        dayNum.textContent = day;
        dayCell.appendChild(dayNum);

        if(trips[dateStr]){
          const tripsDiv = document.createElement('div');
          tripsDiv.className = 'flex flex-col gap-1 mt-1';
          trips[dateStr].forEach(trip => {
            const tripSpan = document.createElement('span');
            tripSpan.className = 'bg-green-200 text-green-800 font-bold rounded px-2 py-0.5 text-xs break-words cursor-pointer hover:bg-green-300';
            tripSpan.textContent = `${trip.vehicle} - ${trip.purpose}`;
            tripSpan.addEventListener('click', () => {
              modalBody.innerHTML = `<p><strong>Vehicle:</strong> ${trip.vehicle}</p>
                                     <p><strong>Purpose:</strong> ${trip.purpose}</p>
                                     <p><strong>Date:</strong> ${dateStr}</p>`;
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
      if(currentMonth < 0){
        currentMonth = 11;
        currentYear--;
      }
      renderCalendar(currentMonth, currentYear);
    });

    nextBtn.addEventListener('click', () => {
      currentMonth++;
      if(currentMonth > 11){
        currentMonth = 0;
        currentYear++;
      }
      renderCalendar(currentMonth, currentYear);
    });

    closeModal.addEventListener('click', () => modal.classList.add('hidden'));
    window.addEventListener('click', (e) => { if(e.target == modal) modal.classList.add('hidden'); });

    renderCalendar(currentMonth, currentYear);
  </script>
</body>
</html>
