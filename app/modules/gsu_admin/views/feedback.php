<?php
//  if (!isset($_SESSION['email'])) {
//      header("Location: /app/modules/shared/views/admin_login.php");
//     exit;
// }
require_once __DIR__ . '/../../../config/auth-admin.php';
require_once __DIR__ . '/../../../controllers/AdminController.php';
$controller = new AdminController();
$feedbackData = $controller->getAllFeedbacks();

$ratingCounts = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
foreach ($feedbackData as $fb) {
  $rating = (int)$fb['overall_rating'];
  if ($rating >= 1 && $rating <= 5) {
    $ratingCounts[$rating]++;
  }
}

// Make it JS-friendly (ordered from 5 to 1 for chart)
$chartData = [
  $ratingCounts[5],
  $ratingCounts[4],
  $ratingCounts[3],
  $ratingCounts[2],
  $ratingCounts[1]
];

// calculate average rating if needed
$total = 0;
foreach ($feedbackData as $fb) $total += $fb['overall_rating'];
$averageRating = count($feedbackData) > 0 ? round($total / count($feedbackData), 2) : 0;
$profile = $controller->getProfile($_SESSION['email']);
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
  <?php include COMPONENTS_PATH . '/gsu_menu.php'; ?>
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
          <canvas id="ratingChart" style="width:100%; height:150px;"></canvas>
        </div>
      </div>

      <!-- Feedback Cards Section -->
      <div class="bg-white rounded-2xl shadow">
        <div class="flex items-center justify-end gap-2">
          <!-- <input type="text" id="searchUser" placeholder="Search by name" class="flex-1 min-w-[200px] input-field">
          <select id="sortSelect" class="input-field">
            <option value="desc">Sort by Rating Desc</option>
            <option value="asc">Sort by Rating Asc</option>
          </select> -->
        </div>

        <!-- <hr class="mb-2 border-b border-gray-300 mx-10"> -->

        <!-- Feedback Cards -->
        <div id="feedbackCards" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2 p-4">

          <?php if (empty($feedbackData)): ?>
            <div class="col-span-4 text-center py-10 font-medium">
              <p class="text-gray-300">No comments added.</p>
            </div>
          <?php endif; ?>

          <?php foreach ($feedbackData as $feedback): ?>
            <div class="feedback-card p-2" data-rating="<?= htmlspecialchars($feedback['overall_rating']) ?>">
              <div class="flex flex-col min-h-[210px] gap-2 border border-gray-300 rounded-lg p-4 bg-gray-50 shadow-sm hover:shadow-md transition-shadow duration-300">

                <!-- Header (Tracking ID + Stars + Date) -->
                <div class="flex flex-col">
                  <!-- <p class="font-semibold text-sm mb-1 text-left">
                    <?= htmlspecialchars($feedback['tracking_id'] ?? 'Anonymous User') ?>
                  </p> -->

                  <div class="flex flex-wrap items-center justify-between mb-0">
                    <div class="flex items-center text-sm text-gray-500 mb-2 text-center">
                      <div class="stars flex mr-2" data-rating="<?= htmlspecialchars($feedback['overall_rating']) ?>">
                        <?php
                        // $stars = round($feedback['overall_rating']);
                        // for ($i = 1; $i <= 5; $i++) {
                        //   if ($i <= $stars) {
                        //     echo '<span class="text-yellow-400 text-base">★</span>';
                        //   } else {
                        //     echo '<span class="text-gray-300 text-base">★</span>';
                        //   }
                        // }
                        ?>
                        <!-- <span class="ml-2 text-gray-600"><?= htmlspecialchars($feedback['overall_rating']) ?></span> -->
                      </div>
                      <span><?= htmlspecialchars(date('Y-m-d', strtotime($feedback['submitted_at']))) ?></span>
                    </div>
                  </div>
                </div>

                <!-- Comment Section -->
                <!-- <div class="flex-1">
                  <p class="text-xs leading-relaxed text-justify text-gray-700">
                    <?= htmlspecialchars($feedback['suggest_overall'] ?: 'No comment added.') ?>
                  </p>
                </div> -->
                <?php
                $comment = $feedback['suggest_overall'] ?: 'No comment added.';
                $shortComment = strlen($comment) > 240
                  ? substr($comment, 0, 240) . "..."
                  : $comment;
                ?>
                <p class="text-xs leading-relaxed text-justify text-gray-700">
                  <?= htmlspecialchars($shortComment) ?>
                </p>

                <!-- Button at Bottom -->
                <div class="flex mt-auto">
                  <a href="../../user/views/feedback.php?tracking_id=<?= urlencode($feedback['tracking_id']) ?>"
                    class="btn-tertiary">
                    Read Full Feedback →
                  </a>
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
    const ctx = document.getElementById('ratingChart').getContext('2d');
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['⭐ 5', '⭐ 4', '⭐ 3', '⭐ 2', '⭐ 1'],
        datasets: [{
          data: <?= json_encode($chartData) ?>,
          backgroundColor: ['#81c784', '#fbc02d', '#f57c00', '#d32f2f', '#b71c1c'],
          borderRadius: 15,
          barThickness: 15
        }]
      },
      options: {
        indexAxis: 'y', // horizontal bars
        plugins: {
          legend: {
            display: false
          }
        },
        scales: {
          x: {
            display: false
          }, // hide numbers below
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
<script src="/public/assets/js/shared/menus.js"></script>

</html>