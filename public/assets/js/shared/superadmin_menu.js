document.querySelectorAll(".accordion").forEach(button => {
button.addEventListener("click", () => {
    const submenu = button.nextElementSibling;

    // Close all other submenus
    document.querySelectorAll(".submenu").forEach(menu => {
    if (menu !== submenu) {
        menu.classList.add("hidden");
    }
    });

    // Toggle the clicked submenu
    submenu.classList.toggle("hidden");
});
});