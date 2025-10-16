// password-visibility.js
document.addEventListener("DOMContentLoaded", () => {
  console.log("Password visibility script loaded");

  document.querySelectorAll("[data-password-toggle]").forEach(toggle => {
    toggle.addEventListener("click", () => {
      const targetId = toggle.getAttribute("data-password-toggle");
      const input = document.getElementById(targetId);
      if (!input) return;

      const eyeOpen = toggle.querySelector(".eye-open");
      const eyeClosed = toggle.querySelector(".eye-closed");

      if (input.type === "password") {
        input.type = "text";
        eyeOpen.classList.add("hidden");
        eyeClosed.classList.remove("hidden");
      } else {
        input.type = "password";
        eyeOpen.classList.remove("hidden");
        eyeClosed.classList.add("hidden");
      }
    });
  });
});
