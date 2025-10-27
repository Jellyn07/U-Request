// ---------- Request Status Chart (Pie) ----------
document.addEventListener('DOMContentLoaded', () => {
  fetch('../../../controllers/DashboardController.php')
    .then(response => response.json())
    .then(data => {
      const ctx = document.getElementById('requestStatusChart').getContext('2d');

      new Chart(ctx, {
        type: 'pie',
        data: {
          labels: ['Pending', 'Approved', 'In Progress', 'Completed', 'Rejected/Cancelled'],
          datasets: [{
            data: [
              data.pending,
              data.approved,
              data.in_progress,
              data.completed,
              data.rejected_cancelled
            ],
            backgroundColor: [
              '#FFC845', // Pending
              '#1C7ED6', // Approved
              '#F29C4C', // In Progress
              '#6B9A4F', // Completed
              '#D11100'  // Rejected/Cancelled
            ],
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          layout: {
            padding: {
              top: 10,
              bottom: 10,
              left: 10,
              right: 10
            }
          },
          plugins: {
            legend: {
              position: 'right',
              labels: {
                boxWidth: 15,
                boxHeight: 8,
                padding: 10
              }
            }
          },
          radius: '90%' // smaller circle to add spacing inside the div
        }
      });
    })
    .catch(error => console.error('Error loading chart data:', error));
});


// ---------- Vehicle Usage Chart (Bar) ----------
const vehicleUsageCtx = document.getElementById('vehicleUsageChart').getContext('2d');
const vehicleUsageChart = new Chart(vehicleUsageCtx, {
    type: 'bar',
    data: {
        labels: ['Van 1', 'Van 2', 'Car 1', 'Car 2', 'Truck 1'], // Vehicle names
        datasets: [{
            label: 'Trips Completed',
            data: [12, 8, 15, 6, 10], // Example numbers
            backgroundColor: [
              '#FFC845', //yellow
              '#F29C4C', //orange
              '#1C7ED6', //blue
              '#6B9A4F', //green
              '#D11100' //red
            ],
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        plugins: {
            // title: { display: true, text: 'Vehicle Usage' },
            legend: { display: false }
        },
        scales: {
            y: { beginAtZero: true, title: { display: true, text: 'Number of Trips' } },
            x: { title: { display: false} }
        }
    }
});

