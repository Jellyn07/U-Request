// Dark mode toggle logic (dropdown and mobile)
function setDarkModeUI(isDark) {
  var dropdownSlider = document.getElementById('dropdown-dark-toggle-slider');
  var dropdownLabel = document.getElementById('dropdown-dark-toggle-label');
  var mobileSlider = document.getElementById('mobile-dark-toggle-slider');
  var mobileLabel = document.getElementById('mobile-dark-toggle-label');
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
  var isDark = isDarkMode();
  var logoImg = document.getElementById('logo-img');
  var footerLogoImg = document.getElementById('footer-logo-img');
  if (logoImg) {
    logoImg.src = isDark ? '/public/assets/img/logo_dark.png' : '/public/assets/img/logo_light.png';
  }
  if (footerLogoImg) {
    footerLogoImg.src = isDark ? '/public/assets/img/logo_dark.png' : '/public/assets/img/logo_light.png';
  }
}

function toggleDarkMode() {
  document.documentElement.classList.toggle('dark');
  setDarkModeUI(isDarkMode());
  updateLogoForMode();
}

document.addEventListener("DOMContentLoaded", function () {
  var dropdownToggle = document.getElementById('dropdown-toggle-dark');
  if (dropdownToggle) dropdownToggle.onclick = toggleDarkMode;

  var mobileToggle = document.getElementById('mobile-toggle-dark');
  if (mobileToggle) mobileToggle.onclick = toggleDarkMode;

  setDarkModeUI(isDarkMode());
  updateLogoForMode();

  // Profile dropdown logic
  var profileBtn = document.getElementById('profile-btn');
  var profileDropdown = document.getElementById('profile-dropdown');
  var dropdownOpen = false;
  if (profileBtn && profileDropdown) {
    profileBtn.onclick = function(e) {
      e.stopPropagation();
      dropdownOpen = !dropdownOpen;
      profileDropdown.classList.toggle('hidden', !dropdownOpen);
    };
    document.addEventListener('click', function(e) {
      if (dropdownOpen && !profileDropdown.contains(e.target) && e.target !== profileBtn) {
        profileDropdown.classList.add('hidden');
        dropdownOpen = false;
      }
    });
  }

  // Mobile menu logic
  var mobileMenuBtn = document.getElementById('mobile-menu-btn');
  var mobileMenu = document.getElementById('mobile-menu');
  var mobileMenuOpen = false;
  if (mobileMenuBtn && mobileMenu) {
    mobileMenuBtn.onclick = function(e) {
      e.stopPropagation();
      mobileMenuOpen = !mobileMenuOpen;
      mobileMenu.classList.toggle('hidden', !mobileMenuOpen);
    };
    document.addEventListener('click', function(e) {
      if (mobileMenuOpen && !mobileMenu.contains(e.target) && e.target !== mobileMenuBtn) {
        mobileMenu.classList.add('hidden');
        mobileMenuOpen = false;
      }
    });
  }
});
