// ---------- Request Status Chart (Pie) ----------
const requestStatusCtx = document.getElementById('requestStatusChart').getContext('2d');
const requestStatusChart = new Chart(requestStatusCtx, {
    type: 'pie',
    data: {
        labels: ['Pending', 'Approved', 'In Progress', 'Completed', 'Rejected/Cancelled'],
        datasets: [{
          data: [5, 10, 3, 12, 2], // Example numbers
          backgroundColor: [
            '#FFC845', //yellow
            '#1C7ED6', //blue
            '#F29C4C', //orange
            '#6B9A4F', //green
            '#D11100' //red
          ], 
          borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false, // Allow custom sizing
        aspectRatio: 1,             // Makes the pie circle smaller and compact
        plugins: {
            // title: { display: true, text: 'Request Status' },
            legend: { 
              position: 'right',
              labels: {
                generateLabels: chart => {
                        return chart.data.labels.map((label, i) => ({
                            text: label,
                            fillStyle: chart.data.datasets[0].backgroundColor[i],
                            strokeStyle: '#f0f0f0',  // border color
                            lineWidth: 1,            // border width
                            index: i,
                            // Custom property for rounded box
                            borderRadius: 4
                        }));
                    },
                    boxWidth: 15,
                    boxHeight: 8,
                    padding: 10
              }
            }
        },
        radius: '90%'
    }
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

