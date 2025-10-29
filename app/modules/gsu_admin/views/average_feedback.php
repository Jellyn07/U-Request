<?php
session_start();
require_once __DIR__ . '/../../../config/constants.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>U-Request</title>
  <link rel="stylesheet" href="<?php echo PUBLIC_URL; ?>/assets/css/output.css" />
  <link rel="icon" href="<?php echo PUBLIC_URL; ?>/assets/img/upper_logo.png" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    .star {
      width: 22px;
      height: 22px;
      cursor: pointer;
      transition: transform 0.2s ease, fill 0.2s ease;
    }

    .star:hover {
      transform: scale(1.1);
    }

    .star.filled {
      fill: #facc15;
      /* yellow-400 */
    }

    .star.empty {
      fill: #d1d5db;
      /* gray-300 */
    }

    .star-group {
      display: flex;
      justify-content: center;
      gap: 3px;
    }
  </style>
</head>

<body class="bg-gray-100 min-h-screen flex flex-col">
  <main class="md:w-1/2 w-full container mx-auto px-4 py-10 flex-1">
    <div class="bg-white shadow-lg rounded-xl p-8 border border-gray-300">
      <div class="text-center">
        <img src="<?php echo PUBLIC_URL; ?>/assets/img/usep.png" alt="USeP Logo" class="w-20 mx-auto mb-2">
        <h1 class="font-bold text-xl uppercase">University of Southeastern Philippines</h1>
        <h2 class="text-lg font-semibold text-center uppercase">Customer’s Feedback Form</h2>
      </div>

      <form id="feedbackForm" action="#" method="POST" class="mt-5">
        <input type="hidden" name="tracking_id" value="<?php echo htmlspecialchars($tracking_id); ?>">
        <p class="text-sm">
          INSTRUCTION: Kindly evaluate the service rendered based on the level of your satisfaction by clicking
          <b>5-Very Satisfied</b> to <b>1-Very Dissatisfied</b>.
        </p>

        <!-- A. Process/Transaction -->
        <section class="pb-0 p-4 rounded-lg mt-3">
          <table class="w-full text-sm">
            <thead class="">
              <tr>
                <th class="text-left p-2 w-3/4">A. PROCESS / TRANSACTION</th>
                <!-- <th class="p-2 text-center">RATING</th> -->
              </tr>
            </thead>
            <tbody class="">
              <?php
              $processItems = [
                'Process/Transaction is completed within the prescribed time.',
                'Costs and fees are fair given the quality of service provided.',
                'Pre-requisite documents are consistent with published requirements.',
                'Process/procedure is clear and simple to understand.'
              ];
              foreach ($processItems as $index => $item) {
                echo "
                    <tr>
                      <td class='p-2'>$item</td>
                      <td class='text-center'><div class='star-group' data-section='A' data-index='$index'></div></td>
                    </tr>";
              }
              ?>
            </tbody>
          </table>
        </section>

        <!-- B. Frontline Personnel -->
        <section class="pb-0 p-4 rounded-lg">
          <table class="w-full text-sm mt-5">
            <thead class="bg-gray-50">
              <tr>
                <th class="text-left p-2 w-3/4">B. FRONTLINE PERSONNEL</th>
                <!-- <th class="p-2 text-center">RATING</th> -->
              </tr>
            </thead>
            <tbody class="">
              <?php
              $frontlineItems = [
                'Attends to my needs and concerns promptly, whether in face-to-face or online transactions.',
                'Knowledgeable and competent about the office’s processes and policies.',
                'Performs duties with professionalism.',
                'Treats clients with utmost friendliness and politeness.',
                'Gives updates if there were service delays.',
                'Provides clear, complete, and accurate information.',
                'Resolves issues and complaints quickly and completely.'
              ];
              foreach ($frontlineItems as $index => $item) {
                echo "
                    <tr>
                      <td class='p-2'>$item</td>
                      <td class='text-center'><div class='star-group' data-section='B' data-index='$index'></div></td>
                    </tr>";
              }
              ?>
            </tbody>
          </table>
        </section>

        <!-- C. Facilities -->
        <section class="pb-0 p-4 rounded-lg">
          <table class="w-full text-sm mt-5">
            <thead class="bg-gray-50">
              <tr>
                <th class="text-left p-2 w-3/4">C. FACILITIES</th>
                <!-- <th class="p-2 text-center">RATING</th> -->
              </tr>
            </thead>
            <tbody class="">
              <?php
              $facilityItems = [
                'The customer service area is clean and organized.',
                'The customer service area is well-ventilated.',
                'The customer service area has adequate seats and space for queuing.',
                'Online facilities (e.g., email, offsite payment) are reliable.'
              ];
              foreach ($facilityItems as $index => $item) {
                echo "
                    <tr>
                      <td class='p-2'>$item</td>
                      <td class='text-center'><div class='star-group' data-section='C' data-index='$index'></div></td>
                    </tr>";
              }
              ?>
            </tbody>
          </table>
        </section>

        <!-- D. Overall Performance -->
        <section class="p-4 rounded-lg">
          <table class="w-full text-sm mt-5">
            <thead class="bg-gray-50">
              <tr>
                <th class="text-left p-2 w-3/4">D. OVERALL PERFORMANCE (auto-generated) </th>
                <!-- <th class="p-2 text-center">RATING</th> -->
              </tr>
            </thead>
            <tbody class="">
              <tr>
                <td class='p-2'>Your overall satisfaction with the services provided by the office.</td>
                <td class='text-center'>
                  <div class='star-group' id='overallStars'></div>
                </td>
              </tr>
            </tbody>
          </table>
        </section>

        <div class="text-center mt-5">
          <a href="feedback.php" class="btn btn-secondary mr-2">Back</a>
        </div>
      </form>
    </div>
  </main>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      fetch('../../../controllers/FeedbackController.php')
        .then(res => res.json())
        .then(res => {
          if (res.status === 'success' && res.data) {
            const data = res.data;

            // ✅ Display averages per section (A, B, C)
            displayAverageRatings('A', data.avgA);
            displayAverageRatings('B', data.avgB);
            displayAverageRatings('C', data.avgC);

            // ✅ Compute overall rating (average of all section averages)
            displayOverallAverage(data);
          } else {
            console.warn('No feedback data available yet.');
            displayNoDataMessage();
          }
        })
        .catch(err => {
          console.error('Error fetching averages:', err);
          displayNoDataMessage();
        });
    });

    // ✅ Displays per-section averages inline
    function displayAverageRatings(section, averages) {
      if (!averages || Object.keys(averages).length === 0) return;

      Object.entries(averages).forEach(([index, avg]) => {
        const group = document.querySelector(`.star-group[data-section="${section}"][data-index="${index}"]`);
        if (!group) return;

        // Clear any previous placeholders
        group.innerHTML = '';

        // Append inline average number (right side of row)
        const avgSpan = document.createElement('span');
        avgSpan.textContent = parseFloat(avg).toFixed(2);
        avgSpan.classList.add('text-sm', 'font-semibold', 'text-gray-700');

        group.appendChild(avgSpan);
      });
    }

    // ✅ Compute and display overall rating average (section D)
    function displayOverallAverage(data) {
      const allAverages = [
        ...Object.values(data.avgA || {}),
        ...Object.values(data.avgB || {}),
        ...Object.values(data.avgC || {})
      ];

      if (allAverages.length === 0) {
        const group = document.querySelector('#overallStars');
        group.innerHTML = `<span class="text-xs text-gray-400 italic">No data yet</span>`;
        return;
      }

      const sum = allAverages.reduce((a, b) => a + parseFloat(b), 0);
      const overallAvg = sum / allAverages.length;

      const group = document.querySelector('#overallStars');
      group.innerHTML = ''; // clear
      const avgSpan = document.createElement('span');
      avgSpan.textContent = overallAvg.toFixed(2);
      avgSpan.classList.add('text-base', 'font-bold', 'text-blue-700');
      group.appendChild(avgSpan);
    }

    // ✅ Show "No data yet" if no averages found
    function displayNoDataMessage() {
      document.querySelectorAll('.star-group').forEach(group => {
        group.innerHTML = `
      <span class="text-xs text-gray-400 italic">No data yet</span>
    `;
      });
    }
  </script>
  <script>
    document.getElementById('feedbackForm').addEventListener('submit', function(e) {
      e.preventDefault();

      // Prepare data
      const form = e.target;
      const data = new FormData(form);
      data.append('ratings_A', JSON.stringify(ratings.A));
      data.append('ratings_B', JSON.stringify(ratings.B));
      data.append('ratings_C', JSON.stringify(ratings.C));

      // Compute average for overall rating
      let all = [];
      for (let s in ratings)
        for (let i in ratings[s]) all.push(ratings[s][i]);
      const overall = all.length ? (all.reduce((a, b) => a + b) / all.length).toFixed(2) : 0;
      data.append('overall_rating', overall);

      fetch('../../../controllers/FeedbackController.php', {
          method: 'POST',
          body: data
        })
        .then(res => res.json())
        .then(res => {
          if (res.status === 'success') {
            Swal.fire({
              title: 'Feedback Submitted!',
              text: res.message,
              icon: 'success',
              confirmButtonText: 'OK'
            }).then(() => {
              window.location.href = 'tracking.php';
            });
          } else {
            Swal.fire({
              title: 'Error!',
              text: res.message,
              icon: 'error',
              confirmButtonText: 'Try Again'
            });
          }
        })
        .catch(() => {
          Swal.fire({
            title: 'Submission Failed!',
            text: 'Failed to submit feedback. Please try again later.',
            icon: 'error',
            confirmButtonText: 'OK'
          });
        });
    });
  </script>
  <?php include COMPONENTS_PATH . '/footer.php'; ?>
</body>

</html>