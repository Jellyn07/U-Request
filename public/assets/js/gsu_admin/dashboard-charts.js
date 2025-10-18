// --- Building Chart ---
new Chart(document.getElementById('buildingChart'), {
    type: 'bar',
    data: {
    labels: ['SOM', 'PECC', 'Admin', 'Lib', 'Eng', 'Sci', 'Gym', 'CCS', 'CHS'],
    datasets: [{
        label: 'Requests',
        data: [30, 25, 20, 15, 10, 5, 8, 12, 18],
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
    maintainAspectRatio: false,
    plugins: { legend: { display: false } }
    },
});

// --- Personnel Workload Chart ---
const requestTypes = ['Carpentry/Masonry','Welding','Hauling','Plumbing','Landscaping','Electrical','Air-Condition','Others'];
const personnelLabels = ['John','Maria','Alex','Lara','Mike'];

// Example: random data for each personnel per request type
const colors = [
    // '#750000', '#d11100', '#f2a65a', '#6a994e', '#3d405b'
    '#FFC845', //yellow
    '#F29C4C', //orange
    '#1C7ED6', //blue
    '#6B9A4F', //green
    '#D11100' //red
];

const datasets = personnelLabels.map((person, idx) => ({
    label: person,
    data: requestTypes.map(() => Math.floor(Math.random() * 10) + 1), // replace with real data
    backgroundColor: colors[idx % colors.length],
    maxBarThickness: 13,
    borderRadius: 4
}));

new Chart(document.getElementById('workloadChart'), {
    type: 'bar',
    data: { labels: requestTypes, datasets: datasets },
    options: {
    indexAxis: 'y',
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { display: false }, // hides personnel labels
        tooltip: {
        enabled: true,
        mode: 'nearest',
        intersect: false,
        }
    },
    scales: {
        x: { stacked: true, beginAtZero: true, grid: { display: false } },
        y: { stacked: true, ticks: { font: { size: 12 } }, grid: { display: false } }
    }
    }
});

