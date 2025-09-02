<?php
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}
require_once __DIR__ . '/../config/constants.php';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>U-Request</title>
    <link rel="stylesheet" href="<?php echo PUBLIC_URL; ?>/assets/css/output.css" />
    <link rel="icon" href="<?php echo PUBLIC_URL; ?>/assets/img/upper_logo.png"/>
  </head>
  <body class="bg-gray-50">
    <?php include COMPONENTS_PATH . '/header.php'; ?>
    <main class="container mx-auto px-4 py-8">
      <div class="max-w-6xl mx-auto">
        
        <div style="display: flex; flex-direction: row; gap: 2rem;">
          <!-- Left Side - Profile Picture (Smaller) -->
          <div style="width: 25%;">
            <div class="bg-white rounded-lg shadow-md p-6">
              <div class="flex flex-col items-center">
                <div class="relative mb-4">
                  <img 
                    src="/public/assets/img/user-default.png" 
                    alt="User Profile" 
                    class="w-32 h-32 rounded-full object-cover border-4 border-red-600 shadow-lg" 
                  />
                  <button class="absolute bottom-0 right-0 bg-red-600 text-white rounded-full p-2 hover:bg-red-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                  </button>
                </div>
                <div class="text-center mb-4">
                  <p class="text-sm text-gray-500 mb-1">Email Address</p>
                  <p class="font-medium text-gray-700">user@example.com</p>
                </div>
                
                <!-- Change Profile Button -->
                <button class="bg-primary text-white py-2 px-4 rounded-md hover:bg-red-700 transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                  Change Profile
                </button>
              </div>
            </div>
          </div>

          <!-- Right Side - User Details (Larger) -->
          <div style="width: 75%;">
            <div class="bg-white rounded-lg shadow-md p-6">
              <h2 class="text-xl font-semibold mb-6 text-gray-800">Personal Information</h2>
              
              <form class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label for="firstName" class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                    <input 
                      type="text" 
                      id="firstName" 
                      name="firstName" 
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                      placeholder="Enter your first name"
                    />
                  </div>
                  
                  <div>
                    <label for="lastName" class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                    <input 
                      type="text" 
                      id="lastName" 
                      name="lastName" 
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                      placeholder="Enter your last name"
                    />
                  </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                    <input 
                      type="tel" 
                      id="phone" 
                      name="phone" 
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                      placeholder="Enter your phone number"
                    />
                  </div>
                  
                  <div>
                    <label for="department" class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                    <select 
                      id="department" 
                      name="department" 
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                    >
                      <option value="">Select department</option>
                      <option value="it">Information Technology</option>
                      <option value="hr">Human Resources</option>
                      <option value="finance">Finance</option>
                      <option value="operations">Operations</option>
                      <option value="marketing">Marketing</option>
                    </select>
                  </div>
                </div>

                <div>
                  <label for="position" class="block text-sm font-medium text-gray-700 mb-2">Position/Title</label>
                  <input 
                    type="text" 
                    id="position" 
                    name="position" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                    placeholder="Enter your position or title"
                  />
                </div>

                <div>
                  <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                  <textarea 
                    id="address" 
                    name="address" 
                    rows="3"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                    placeholder="Enter your address"
                  ></textarea>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200">
                  <button 
                    type="submit" 
                    class="flex-1 bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                  >
                    Save Changes
                  </button>
                  
                  <button 
                    type="button"
                    onclick="window.location.href='logout.php';"
                    class="flex-1 bg-gray-500 text-white py-2 px-4 rounded-md hover:bg-gray-600 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                  >
                    Logout
                </button>
                  
                  <button 
                    type="button" 
                    class="flex-1 bg-red-800 text-white py-2 px-4 rounded-md hover:bg-red-900 transition-colors focus:outline-none focus:ring-2 focus:ring-red-800 focus:ring-offset-2"
                  >
                    Delete Account
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <style>
          @media (min-width: 768px) {
            .profile-container {
              display: flex !important;
              flex-direction: row !important;
            }
            .profile-left {
              width: 25% !important;
            }
            .profile-right {
              width: 75% !important;
            }
          }
        </style>

        <script>
          // Add responsive classes to the container
          document.addEventListener('DOMContentLoaded', function() {
            const container = document.querySelector('div[style*="display: flex"]');
            if (container) {
              container.classList.add('profile-container');
            }
            
            const leftSection = container.querySelector('div:first-child');
            const rightSection = container.querySelector('div:last-child');
            
            if (leftSection) leftSection.classList.add('profile-left');
            if (rightSection) rightSection.classList.add('profile-right');
          });
        </script>
      </div>
    </main>
    <?php include COMPONENTS_PATH . '/footer.php'; ?>
  </body>
</html>