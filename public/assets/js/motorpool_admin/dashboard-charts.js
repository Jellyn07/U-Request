// ---------- Request Status Chart (Pie) ----------
document.addEventListener('DOMContentLoaded', () => {
  fetch('../../../controllers/DashboardController.php?request_status=1')
    .then(response => response.json())
    .then(data => {
      const ctx = document.getElementById('requestStatusChart').getContext('2d');

      new Chart(ctx, {
        type: 'pie',
        data: {
          labels: ['Pending', 'Approved', 'On Going', 'Completed', 'Rejected/Cancelled'],
          datasets: [{
            data: [
              data.pending,
              data.approved,
              data.on_going,
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
fetch('../../../controllers/DashboardController.php?vehicle_usage=1')
  .then(res => res.json())
  .then(data => {
    const labels = data.map(d => d.vehicle_name);
    const values = data.map(d => d.trips);

    const colors = ['#FFC845', '#F29C4C', '#1C7ED6', '#6B9A4F', '#D11100'];

    new Chart(document.getElementById('vehicleUsageChart'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Trips Completed',
                data: values,
                backgroundColor: labels.map((_, i) => colors[i % colors.length]),
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true, title: { display: true, text: 'Number of Trips' } },
                x: { title: { display: false } }
            }
        }
    });
  })
  .catch(err => console.error('Vehicle Usage chart error:', err));
