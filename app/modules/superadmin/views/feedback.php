<?php
// if (!isset($_SESSION['email'])) {
//     header("Location: /app/modules/shared/views/admin_login.php");
//     exit;
// }
// require_once __DIR__ . '/../../../config/auth-admin.php';
require_once __DIR__ . '/../../../controllers/AdminController.php';
$controller = new AdminController();
$feedbackData = $controller->getoverallFeedbacks();

// calculate average rating if needed
$total = 0;
foreach ($feedbackData as $fb) $total += $fb['overall_rating'];
$averageRating = count($feedbackData) > 0 ? round($total / count($feedbackData), 2) : 0;
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
        <a href="../../gsu_admin/views/average_feedback.php" class="btn btn-primar">
          <div class="border-r-2 border-gray-300">
            <h2 class="font-medium mb-3">Average Rating</h2>
            <div class="flex items-center space-x-2 mt-2">
              <span class="text-4xl font-bold"><?= $averageRating ?></span>
              <div id="averageStars" class="flex"></div>
            </div>
            <p class="text-xs text-gray-500 font-medium mt-2">Average rating this year</p>
          </div>
        </a>
        <div class="border-r-2 border-gray-300">
          <h2 class="font-medium mb-3">Total Feedback</h2>
          <p class="text-4xl font-bold text-primary mt-2"><?= count($feedbackData) ?></p>
          <p class="text-xs text-gray-500 font-medium mt-2">Total feedbacks this year</p>
        </div>
        <div>
          <canvas id="ratingChart" height="110"></canvas>
        </div>
      </div>

      <div class="bg-white p-6 rounded-2xl shadow">
      <!-- <div class="mb-5 flex items-center justify-end gap-2">
        <select id="sortSelect" class="input-field">
          <option value="desc">GSU Feedback</option>
          <option value="asc">Motorpool Feedback</option>
        </select>
      </div> -->

        <!-- <hr class="mb-2 border-b border-gray-300 mx-10"> -->

        <!-- Feedback Cards -->
        <div id="feedbackCards" class="grid grid-cols-1 mx-10">
          <?php foreach ($feedbackData as $feedback): ?>
            <div class="feedback-card border-b border-gray-400 p-6" data-rating="<?= htmlspecialchars($feedback['overall_rating']) ?>">
              <div class="flex items-start gap-4">
                <!-- Profile -->
                <img src="<?= !empty($feedback['profile_pic']) ? htmlspecialchars($feedback['profile_pic']) : '/public/assets/img/user-default.png' ?>"
                  alt="User photo"
                  class="w-14 h-14 rounded-full object-cover">
                <div class="flex flex-col">
                  <p class="font-semibold text-sm mb-1">
                    <?= htmlspecialchars($feedback['tracking_id'] ?? 'Anonymous User') ?>
                  </p>
                  <p class="text-xs text-gray-600">
                    <span class="font-medium text-xs">Total Request: </span><?= htmlspecialchars($feedback['total_requests'] ?? 0) ?><br>
                    <span class="font-medium text-xs">Total Feedback:</span> <?= htmlspecialchars($feedback['total_feedback'] ?? 0) ?>
                  </p>
                </div>

                <!-- Main Content -->
                <div class="flex-1 pl-5">
                  <div class="flex flex-wrap items-center justify-between mb-1">
                    <!-- Stars and Date -->
                    <div class="flex items-center text-sm text-gray-500 mb-3">
                      <div class="stars flex mr-2" data-rating="<?= htmlspecialchars($feedback['overall_rating']) ?>">
                        <?php
                        $stars = round($feedback['overall_rating']);
                        for ($i = 1; $i <= 5; $i++) {
                          if ($i <= $stars) {
                            echo '<span class="text-yellow-400 text-base">★</span>';
                          } else {
                            echo '<span class="text-gray-300 text-base">★</span>';
                          }
                        }
                        ?>
                      </div>
                      <span><?= htmlspecialchars(date('Y-m-d', strtotime($feedback['submitted_at']))) ?></span>
                    </div>
                  </div>

                  <p class="text-sm leading-relaxed text-justify">
                    <?= htmlspecialchars($feedback['suggest_overall'] ?? 'No feedback comment provided.') ?>
                  </p>

                  <div class="flex items-center gap-3 mt-5 justify-end">
                    <a href="../../user/views/feedback.php?tracking_id=<?= urlencode($feedback['tracking_id']) ?>"
                      class="btn btn-primary">
                      Detailed Feedback
                    </a>
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
            <?= count(array_filter($feedbackData, fn($f) => $f['overall_rating'] == 5)); ?>,
            <?= count(array_filter($feedbackData, fn($f) => $f['overall_rating'] == 4)); ?>,
            <?= count(array_filter($feedbackData, fn($f) => $f['overall_rating'] == 3)); ?>,
            <?= count(array_filter($feedbackData, fn($f) => $f['overall_rating'] == 2)); ?>,
            <?= count(array_filter($feedbackData, fn($f) => $f['overall_rating'] == 1)); ?>
          ],
          backgroundColor: ['#81c784', '#fbc02d', '#f57c00', '#d32f2f', '#b71c1c'],
          borderRadius: 20,
          barThickness: 6,
        }]
      },
      options: {
        indexAxis: 'y',
        plugins: {
          legend: {
            display: false
          }
        },
        scales: {
          x: {
            display: false
          },
          y: {
            grid: {
              display: false
            },
            ticks: {
              color: '#000'
            }
          }
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
      const sorted = cards.sort((a, b) => {
        const ratingA = parseInt(a.dataset.rating);
        const ratingB = parseInt(b.dataset.rating);
        return order === 'asc' ? ratingA - ratingB : ratingB - ratingA;
      });
      feedbackContainer.innerHTML = '';
      sorted.forEach(card => feedbackContainer.appendChild(card));
    });

  </script>
</body>

</html>