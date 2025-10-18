document.addEventListener("DOMContentLoaded", () => {
const form = document.getElementById("loginForm");
// Trigger the entrance animation
requestAnimationFrame(() => {
    form.classList.remove("opacity-0", "translate-y-5");
    form.classList.add("opacity-100", "translate-y-0");
});
});