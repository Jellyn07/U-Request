// ---------- Monthly Requests Chart (Line) ----------
document.addEventListener('DOMContentLoaded', () => {
  fetch('../../../controllers/DashboardController.php?monthly_requests=1')
    .then(response => response.json())
    .then(data => {
      const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
      const vehicleData = Object.values(data.vehicle);
      const repairData = Object.values(data.repair);

      const lineCtx = document.getElementById('monthlyLineChart').getContext('2d');

      new Chart(lineCtx, {
        type: 'line',
        data: {
          labels: months,
          datasets: [
            {
              label: 'Vehicle Requests',
              data: vehicleData,
              borderColor: '#2563eb',
              backgroundColor: 'rgba(37,99,235,0.2)',
              tension: 0.3,
              fill: true,
              borderWidth: 2,
              pointRadius: 4,
              pointBackgroundColor: '#2563eb',
              pointBorderColor: '#fff'
            },
            {
              label: 'Repair Requests',
              data: repairData,
              borderColor: '#16a34a',
              backgroundColor: 'rgba(22,163,74,0.2)',
              tension: 0.3,
              fill: true,
              borderWidth: 2,
              pointRadius: 4,
              pointBackgroundColor: '#16a34a',
              pointBorderColor: '#fff'
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { position: 'bottom' },
  
          },
          scales: {
            x: {
              ticks: {
                autoSkip: false, // ✅ show all month labels
                maxRotation: 0,
                minRotation: 0
              },
              grid: {
                display: false
              }
            },
            y: {
              beginAtZero: true,
              ticks: {
                stepSize: 1 // optional: to show whole numbers
              }
            }
          },
          layout: {
            padding: { top: 10, bottom: 10, left: 10, right: 10 }
          }
        }
      });
    })
    .catch(error => console.error('Error loading monthly chart data:', error));
});


    // ---------- Request Type Chart (Vehicle vs Repair) ----------
document.addEventListener('DOMContentLoaded', () => {
  fetch('../../../controllers/DashboardController.php?request_type=1')
    .then(response => response.json())
    .then(data => {
      const ctx = document.getElementById('requestTypeChart').getContext('2d');

      new Chart(ctx, {
        type: 'pie',
        data: {
          labels: ['Vehicle Requests', 'Repair Requests'],
          datasets: [{
            data: [
              data.total_vrequests || 0,
              data.total_rrequests || 0
            ],
            backgroundColor: [
              '#2563eb',
              '#16a34a'
            ],
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          layout: {
            padding: { top: 10, bottom: 10, left: 10, right: 10 }
          },
          plugins: {
            legend: {
              position: 'right',
              labels: { boxWidth: 15, boxHeight: 8, padding: 10 }
            }
          },
          radius: '90%'
        }
      });
    })
    .catch(error => console.error('Error loading request type chart data:', error));
});
