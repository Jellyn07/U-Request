  // Accordion
  document.querySelectorAll(".accordion").forEach(button => {
    button.addEventListener("click", () => {
      const submenu = button.nextElementSibling;
      const arrow = button.querySelector("img.arrows");

      // Close all other submenus and reset their arrows
      document.querySelectorAll(".submenu").forEach(menu => {
        if (menu !== submenu) {
          menu.classList.add("hidden");
        }
      });
      document.querySelectorAll(".accordion img.arrows").forEach(img => {
        if (img !== arrow) {
          img.classList.remove("rotate-180");
        }
      });

      // Toggle the clicked one
      submenu.classList.toggle("hidden");
      arrow.classList.toggle("rotate-180");
    });
  });
