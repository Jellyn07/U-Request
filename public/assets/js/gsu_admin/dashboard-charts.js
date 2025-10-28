// --- Building Chart ---
fetch('../../../controllers/DashboardController.php?building_requests=1')
  .then(res => res.json())
  .then(data => {
    const labels = data.map(item => item.building);
    const values = data.map(item => parseInt(item.total_requests));

    // Optional: generate colors dynamically if more buildings appear
    const colors = labels.map((_, i) => {
        const palette = ['#FFC845', '#F29C4C', '#1C7ED6', '#6B9A4F', '#D11100'];
        return palette[i % palette.length]; 
    });

    new Chart(document.getElementById('buildingChart'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Requests',
                data: values,
                backgroundColor: colors,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } }
        }
    });
  })
  .catch(err => console.error(err));
// --- Personnel Workload Chart ---
const requestTypes = ['Carpentry/Masonry','Welding','Hauling','Plumbing','Landscaping','Electrical','Air-Condition','Others'];
fetch('../../../controllers/DashboardController.php?workload_data=1')
  .then(res => res.json())
  .then(data => {
    // Get unique personnel names
    const personnelLabels = [...new Set(data.map(d => d.firstName + ' ' + d.lastName))];

    const colors = ['#FFC845', '#F29C4C', '#1C7ED6', '#6B9A4F', '#D11100'];

    // Build datasets
    const datasets = personnelLabels.map((person, idx) => {
        return {
            label: person,
            data: requestTypes.map(rt => {
                // Find matching record; if none exists, return 0
                const record = data.find(d => (d.firstName + ' ' + d.lastName) === person && d.request_Type === rt);
                return record ? parseInt(record.total) : 0;
            }),
            backgroundColor: colors[idx % colors.length],
            maxBarThickness: 13,
            borderRadius: 4
        };
    });

    new Chart(document.getElementById('workloadChart'), {
        type: 'bar',
        data: { labels: requestTypes, datasets: datasets },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: true },
                tooltip: { enabled: true, mode: 'nearest', intersect: false }
            },
            scales: {
                x: { stacked: true, beginAtZero: true, grid: { display: false } },
                y: { stacked: true, ticks: { font: { size: 12 } }, grid: { display: false } }
            }
        }
    });
  })
  .catch(err => console.error('Workload chart error:', err));
