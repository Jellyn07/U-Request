// ----------------------
// Dark mode toggle logic
// ----------------------
function setDarkModeUI(isDark) {
  const dropdownSlider = document.getElementById('dropdown-dark-toggle-slider');
  const dropdownLabel = document.getElementById('dropdown-dark-toggle-label');
  const mobileSlider = document.getElementById('mobile-dark-toggle-slider');
  const mobileLabel = document.getElementById('mobile-dark-toggle-label');

  if (isDark) {
    if (dropdownSlider) dropdownSlider.style.transform = 'translateX(1.25rem)';
    if (dropdownLabel) dropdownLabel.textContent = 'Light Mode';
    if (mobileSlider) mobileSlider.style.transform = 'translateX(1.25rem)';
    if (mobileLabel) mobileLabel.textContent = 'Light Mode';
  } else {
    if (dropdownSlider) dropdownSlider.style.transform = 'translateX(0)';
    if (dropdownLabel) dropdownLabel.textContent = 'Dark Mode';
    if (mobileSlider) mobileSlider.style.transform = 'translateX(0)';
    if (mobileLabel) mobileLabel.textContent = 'Dark Mode';
  }
}

function isDarkMode() {
  return document.documentElement.classList.contains('dark');
}

function updateLogoForMode() {
  const isDark = isDarkMode();
  const logoImg = document.getElementById('logo-img');
  const footerLogoImg = document.getElementById('footer-logo-img');

  if (logoImg) logoImg.src = isDark ? '/public/assets/img/logo_dark.png' : '/public/assets/img/logo_light.png';
  if (footerLogoImg) footerLogoImg.src = isDark ? '/public/assets/img/logo_dark.png' : '/public/assets/img/logo_light.png';
}

function toggleDarkMode() {
  document.documentElement.classList.toggle('dark');
  setDarkModeUI(isDarkMode());
  updateLogoForMode();
}

// ----------------------
// DOMContentLoaded setup
// ----------------------
document.addEventListener("DOMContentLoaded", function () {
  // Dark mode toggles
  const dropdownToggle = document.getElementById('dropdown-toggle-dark');
  if (dropdownToggle) dropdownToggle.onclick = toggleDarkMode;

  const mobileToggle = document.getElementById('mobile-toggle-dark');
  if (mobileToggle) mobileToggle.onclick = toggleDarkMode;

  setDarkModeUI(isDarkMode());
  updateLogoForMode();

  // Profile dropdown logic
  const profileBtn = document.getElementById('profile-btn');
  const profileDropdown = document.getElementById('profile-dropdown');
  let dropdownOpen = false;

  if (profileBtn && profileDropdown) {
    profileBtn.onclick = function (e) {
      e.stopPropagation();
      dropdownOpen = !dropdownOpen;
      profileDropdown.classList.toggle('hidden', !dropdownOpen);
    };

    document.addEventListener('click', function (e) {
      if (dropdownOpen && !profileDropdown.contains(e.target) && e.target !== profileBtn) {
        profileDropdown.classList.add('hidden');
        dropdownOpen = false;
      }
    });
  }

  // ----------------------
  // Mobile menu logic
  // ----------------------
  const mobileMenuBtn = document.getElementById('mobile-menu-btn');
  const mobileMenu = document.getElementById('mobile-menu');
  const mobileOverlay = document.getElementById('mobile-overlay');

  mobileMenuBtn.addEventListener('click', () => {
    // Open menu
    mobileMenu.classList.remove('translate-x-full');
    mobileMenu.classList.add('translate-x-0');

    mobileOverlay.classList.remove('hidden', 'opacity-0');
    mobileOverlay.classList.add('opacity-100');

    document.body.classList.add('overflow-hidden'); // Disable scroll
  });

  mobileOverlay.addEventListener('click', () => {
    // Close menu
    mobileMenu.classList.remove('translate-x-0');
    mobileMenu.classList.add('translate-x-full');

    mobileOverlay.classList.remove('opacity-100');
    mobileOverlay.classList.add('opacity-0');

    setTimeout(() => mobileOverlay.classList.add('hidden'), 300); // Match transition

    document.body.classList.remove('overflow-hidden'); // Enable scroll
  });

  // Close menu when clicking a mobile menu item (optional UX)
  const mobileMenuLinks = mobileMenu.querySelectorAll('a, button');
  mobileMenuLinks.forEach(link => {
    link.addEventListener('click', () => {
      mobileMenu.classList.remove('translate-x-0');
      mobileMenu.classList.add('translate-x-full');

      mobileOverlay.classList.remove('opacity-100');
      mobileOverlay.classList.add('opacity-0');

      setTimeout(() => mobileOverlay.classList.add('hidden'), 300);

      document.body.classList.remove('overflow-hidden');
    });
  });
});
