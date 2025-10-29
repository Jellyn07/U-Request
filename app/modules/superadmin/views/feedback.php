<?php
session_start();
require_once __DIR__ . '/../../../config/constants.php';
require_once __DIR__ . '/../../../controllers/UserController.php';
require_once __DIR__ . '/../../../controllers/AdminController.php';

// Example feedback data — replace with DB query
$feedbackData = [
    ['name' => 'Towhidur Rahman', 'rating' => 4, 'comment' => "My first and only mala ordered on Etsy, and I'm beyond delighted! I requested a custom mala based on two stones I was called to invite together in this kind of creation. The fun and genuine joy I invite together in this kind of creation.\nThe fun and genuine joy.", 'date' => '2022-10-24', 'total_request' => 200, 'total_review' => 14],
    ['name' => 'Jane Smith', 'rating' => 5, 'comment' => 'Amazing product and fast delivery!', 'date' => '2025-10-06', 'total_request' => 180, 'total_review' => 10],
    ['name' => 'Carlos Rivera', 'rating' => 3, 'comment' => 'Average experience.', 'date' => '2025-10-07', 'total_request' => 120, 'total_review' => 5],
    ['name' => 'Towhidur Rahman', 'rating' => 5, 'comment' => "My first and only mala ordered on Etsy, and I'm beyond delighted! I requested a custom mala based on two stones I was called to invite together in this kind of creation. The fun and genuine joy I invite together in this kind of creation.\nThe fun and genuine joy.", 'date' => '2022-10-24', 'total_request' => 200, 'total_review' => 14],
    ['name' => 'Jane Smith', 'rating' => 2, 'comment' => 'Amazing product and fast delivery!', 'date' => '2025-10-06', 'total_request' => 180, 'total_review' => 10],
    ['name' => 'Carlos Rivera', 'rating' => 1, 'comment' => 'Average experience.', 'date' => '2025-10-07', 'total_request' => 120, 'total_review' => 5],
    ['name' => 'One Rivera', 'rating' => 5, 'comment' => 'Average experience.', 'date' => '2025-10-07', 'total_request' => 120, 'total_review' => 5],
    ['name' => 'Twi Rivera', 'rating' => 4, 'comment' => 'Average experience.', 'date' => '2025-10-07', 'total_request' => 120, 'total_review' => 5],
    ['name' => 'Three Rivera', 'rating' => 3, 'comment' => 'Average experience.', 'date' => '2025-10-07', 'total_request' => 120, 'total_review' => 5],
    ['name' => 'Four Rivera', 'rating' => 2, 'comment' => 'Average experience.', 'date' => '2025-10-07', 'total_request' => 120, 'total_review' => 5],
    ['name' => 'Five Rivera', 'rating' => 3, 'comment' => 'Average experience.', 'date' => '2025-10-07', 'total_request' => 120, 'total_review' => 5],
    ['name' => 'Six Rivera', 'rating' => 4, 'comment' => 'Average experience.', 'date' => '2025-10-07', 'total_request' => 120, 'total_review' => 5],
    ['name' => 'Seven Rivera', 'rating' => 5, 'comment' => 'Average experience.', 'date' => '2025-10-07', 'total_request' => 120, 'total_review' => 5],
    ['name' => 'Eight Rivera', 'rating' => 4, 'comment' => 'Average experience.', 'date' => '2025-10-07', 'total_request' => 120, 'total_review' => 5],
    ['name' => 'Nine Rivera', 'rating' => 5, 'comment' => 'Average experience.', 'date' => '2025-10-07', 'total_request' => 120, 'total_review' => 5],



  ];

// Compute average rating
$total = 0;
foreach ($feedbackData as $fb) $total += $fb['rating'];
$averageRating = round($total / count($feedbackData), 1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>U-Request | Feedbacks</title>
<link rel="stylesheet" href="/public/assets/css/output.css" />
<link rel="icon" href="/public/assets/img/upper_logo.png" />
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">
<?php include COMPONENTS_PATH . '/superadmin_menu.php'; ?>
<main class="ml-16 md:ml-64 flex flex-col min-h-screen transition-all duration-300">
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Feedback Insights</h1>

    <!-- Feedback Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-5 bg-white p-6 rounded-2xl shadow">
      <div class="border-r-2 border-gray-300">
        <h2 class="font-medium mb-3">Average Rating</h2>
        <div class="flex items-center space-x-2 mt-2">
          <span class="text-4xl font-bold"><?= $averageRating ?></span>
          <div id="averageStars" class="flex"></div>
        </div>
        <p class="text-xs text-gray-500 font-medium mt-2">Average rating this year</p>
      </div>
      <div class="border-r-2 border-gray-300">
        <h2 class="font-medium mb-3">Total Feedback</h2>
        <p class="text-4xl font-bold text-primary mt-2"><?= count($feedbackData) ?></p>
        <p class="text-xs text-gray-500 font-medium mt-2">Total feedbacks this year</p>
      </div>
      <div>
        <canvas id="ratingChart" height="110"></canvas>
      </div>
    </div>

    <!-- Feedback Cards Section -->
    <div class="bg-white p-6 rounded-2xl shadow">
      <div class="mb-5 flex items-center justify-end gap-2">
        <input type="text" id="searchUser" placeholder="Search by name" class="flex-1 min-w-[200px] input-field">
        <select id="sortSelect" class="input-field">
          <option value="desc">Sort by Rating Desc</option>
          <option value="asc">Sort by Rating Asc</option>
        </select>
      </div>

      <!-- <hr class="mb-2 border-b border-gray-300 mx-10"> -->

      <!-- Feedback Cards -->
      <div id="feedbackCards" class="grid grid-cols-1 mx-10">
        <?php foreach ($feedbackData as $feedback): ?>
          <div class="feedback-card border-b border-gray-400 p-6" data-rating="<?= $feedback['rating'] ?>">
            <div class="flex items-start gap-4">
              <!-- Profile -->
              <img src="/public/assets/img/user-default.png" alt="User photo" class="w-14 h-14 rounded-full object-cover">
              <div class="flex flex-col">
                <p class="font-semibold text-sm mb-1"><?= htmlspecialchars($feedback['name']) ?></p>
                <p class="text-xs text-gray-600">
                  <span class="font-medium text-xs">Total Repair Request: </span><?= htmlspecialchars($feedback['total_request']) ?><br>
                  <span class="font-medium text-xs">Total Feedback:</span> <?= htmlspecialchars($feedback['total_review']) ?>
                </p>
              </div>
              

              <!-- Main Content -->
              <div class="flex-1 pl-5">
                <div class="flex flex-wrap items-center justify-between mb-1">
                  <!-- Stars and Date -->
                  <div class="flex items-center text-sm text-gray-500 mb-3">
                    <div class="stars flex mr-2" data-rating="<?= $feedback['rating'] ?>"></div>
                    <span><?= htmlspecialchars($feedback['date']) ?></span>
                  </div>
                </div>


                <p class=" text-sm leading-relaxed">
                  <?= htmlspecialchars($feedback['comment']) ?>
                </p>

                <div class="flex items-center gap-3 mt-5 justify-end">
                  <button class="btn btn-primary">Detailed Feedback</button>
                  <button class="btn btn-secondary">View Request</button>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</main>

<script>
// --- JS Star Rendering ---
function renderStars(rating) {
  let stars = '';
  for (let i = 1; i <= 5; i++) {
    if (rating >= i) {
      stars += `<svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.431L24 9.753l-6 5.847L19.335 24 12 19.897 4.665 24 6 15.6 0 9.753l8.332-1.735z"/></svg>`;
    } else if (rating >= i - 0.5) {
      stars += `<svg class="w-4 h-4 text-yellow-400" viewBox="0 0 24 24"><defs><linearGradient id="half-${i}"><stop offset="50%" stop-color="currentColor"/><stop offset="50%" stop-color="lightgray"/></linearGradient></defs><path fill="url(#half-${i})" d="M12 .587l3.668 7.431L24 9.753l-6 5.847L19.335 24 12 19.897 4.665 24 6 15.6 0 9.753l8.332-1.735z"/></svg>`;
    } else {
      stars += `<svg class="w-4 h-4 text-gray-300 fill-current" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.431L24 9.753l-6 5.847L19.335 24 12 19.897 4.665 24 6 15.6 0 9.753l8.332-1.735z"/></svg>`;
    }
  }
  return stars;
}

// Render stars for all feedback cards
document.querySelectorAll('.stars').forEach(el => {
  const rating = parseFloat(el.dataset.rating);
  el.innerHTML = renderStars(rating);
});
document.getElementById('averageStars').innerHTML = renderStars(<?= $averageRating ?>);

// --- Chart.js Horizontal Bar ---
const ctx = document.getElementById('ratingChart');
new Chart(ctx, {
  type: 'bar',
  data: {
    labels: ['⭐ 5', '⭐ 4', '⭐ 3', '⭐ 2', '⭐ 1'],
    datasets: [{
      data: [
        <?= count(array_filter($feedbackData, fn($f) => $f['rating'] === 5)); ?>,
        <?= count(array_filter($feedbackData, fn($f) => $f['rating'] === 4)); ?>,
        <?= count(array_filter($feedbackData, fn($f) => $f['rating'] === 3)); ?>,
        <?= count(array_filter($feedbackData, fn($f) => $f['rating'] === 2)); ?>,
        <?= count(array_filter($feedbackData, fn($f) => $f['rating'] === 1)); ?>
      ],
      backgroundColor: ['#81c784','#fbc02d','#f57c00','#d32f2f','#b71c1c'],
      borderRadius: 20,
      barThickness: 6,
    }]
  },
  options: {
    indexAxis: 'y',
    plugins: { legend: { display: false } },
    scales: {
      x: { display: false },
      y: { grid: { display: false }, ticks: { color: '#000' } }
    },
    responsive: true,
    maintainAspectRatio: false
  }
});

// --- Sorting ---
const sortSelect = document.getElementById('sortSelect');
const feedbackContainer = document.getElementById('feedbackCards');
const cards = Array.from(feedbackContainer.children);
sortSelect.addEventListener('change', () => {
  const order = sortSelect.value;
  const sorted = cards.sort((a,b) => {
    const ratingA = parseInt(a.dataset.rating);
    const ratingB = parseInt(b.dataset.rating);
    return order === 'asc' ? ratingA - ratingB : ratingB - ratingA;
  });
  feedbackContainer.innerHTML = '';
  sorted.forEach(card => feedbackContainer.appendChild(card));
});
</script>
</body>
<script src="/public/assets/js/shared/menuS.js"></script>
</html>
