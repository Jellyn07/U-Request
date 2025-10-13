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
    <link rel="icon" href="<?php echo PUBLIC_URL; ?>/assets/img/upper_logo.png"/>
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
        fill: #facc15; /* yellow-400 */
      }
      .star.empty {
        fill: #d1d5db; /* gray-300 */
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
            <p class="text-sm mt-4 mb-1">What would you suggest we do to further improve the processes?</p>
            <textarea name="suggest_process" placeholder="" class="w-full input-field"></textarea>
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
            <p class="text-sm mt-4 mb-1">What would you suggest we do to further improve the Customer Service?</p>
            <textarea name="suggest_frontline" placeholder="" class="w-full input-field"></textarea>
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
            <p class="text-sm mt-4 mb-1">What would you suggest we do to further improve the facilities?</p>
            <textarea name="suggest_facility" placeholder="" class="w-full input-field"></textarea>
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
                  <td class='text-center'><div class='star-group' id='overallStars'></div></td>
                </tr>
              </tbody>
            </table>
            <p class="text-sm mt-4 mb-1">Comments & Suggestions:</p>
            <textarea name="suggest_overall" placeholder="" class="w-full input-field"></textarea>
          </section>

          <div class="text-center mt-5">
            <button type="button" class="btn btn-secondary mr-2">Back</button>
            <button type="submit" class="btn btn-primary">Submit Feedback</button>
          </div>
        </form>
      </div>
    </main>

    <script>
      // Render 5 stars for each .star-group
      document.querySelectorAll('.star-group').forEach(group => {
        for (let i = 1; i <= 5; i++) {
          const star = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
          star.setAttribute('viewBox', '0 0 24 24');
          star.classList.add('star', 'empty');
          star.dataset.value = i;
          star.innerHTML = '<path d="M12 .587l3.668 7.431L24 9.753l-6 5.847L19.335 24 12 19.897 4.665 24 6 15.6 0 9.753l8.332-1.735z"/>';
          group.appendChild(star);
        }
      });

      // Ratings storage
      const ratings = { A: {}, B: {}, C: {} };

      // Click behavior for sections A–C
      document.querySelectorAll('.star-group').forEach(group => {
        if (group.id === 'overallStars') return; // skip D (auto)
        const section = group.dataset.section;
        const index = group.dataset.index;
        const stars = group.querySelectorAll('.star');

        stars.forEach(star => {
          star.addEventListener('click', () => {
            const value = parseInt(star.dataset.value);
            ratings[section][index] = value;

            // fill stars
            stars.forEach(s => {
              s.classList.toggle('filled', parseInt(s.dataset.value) <= value);
              s.classList.toggle('empty', parseInt(s.dataset.value) > value);
            });

            updateOverall();
          });
        });
      });

      // Update D automatically
      function updateOverall() {
        let allRatings = [];
        for (let s in ratings) {
          for (let i in ratings[s]) {
            allRatings.push(ratings[s][i]);
          }
        }
        const avg = allRatings.length ? (allRatings.reduce((a,b) => a+b) / allRatings.length) : 0;
        renderOverall(avg);
      }

      function renderOverall(rating) {
        const group = document.getElementById('overallStars');
        group.innerHTML = '';
        for (let i = 1; i <= 5; i++) {
          const star = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
          star.setAttribute('viewBox', '0 0 24 24');
          star.classList.add('star', rating >= i ? 'filled' : 'empty');
          star.innerHTML = '<path d="M12 .587l3.668 7.431L24 9.753l-6 5.847L19.335 24 12 19.897 4.665 24 6 15.6 0 9.753l8.332-1.735z"/>';
          group.appendChild(star);
        }
      }
    </script>
    <?php include COMPONENTS_PATH . '/footer.php'; ?>
  </body>
</html>
