  // Accordion
  // document.querySelectorAll(".accordion").forEach(button => {
  //   button.addEventListener("click", () => {
  //     const submenu = button.nextElementSibling;
  //     const arrow = button.querySelector("img.arrows");

  //     // Close all other submenus and reset their arrows
  //     document.querySelectorAll(".submenu").forEach(menu => {
  //       if (menu !== submenu) {
  //         menu.classList.add("hidden");
  //       }
  //     });
  //     document.querySelectorAll(".accordion img.arrows").forEach(img => {
  //       if (img !== arrow) {
  //         img.classList.remove("rotate-180");
  //       }
  //     });

  //     // Toggle the clicked one
  //     submenu.classList.toggle("hidden");
  //     arrow.classList.toggle("rotate-180");
  //   });
  // });


  // Sidebar collapse
  // const sidebar = document.getElementById("sidebar");
  // const toggleSidebar = document.getElementById("toggleSidebar");
  // const logoText = document.getElementById("logo-text");
  // const sidebarTexts = document.querySelectorAll(".sidebar-text");

  // toggleSidebar.addEventListener("click", () => {
  //   sidebar.classList.toggle("w-64");
  //   sidebar.classList.toggle("w-15");
  //   logoText.classList.toggle("hidden");
  //   sidebarTexts.forEach(el => el.classList.toggle("hidden"));
  // });

  // Profile dropdown
  const profileButton = document.getElementById("profileButton");
  const profileMenu = document.getElementById("profileMenu");

  profileButton.addEventListener("click", () => {
    profileMenu.classList.toggle("hidden");
  });

  // Close dropdown on click outside
  document.addEventListener("click", (e) => {
    if (!profileButton.contains(e.target) && !profileMenu.contains(e.target)) {
      profileMenu.classList.add("hidden");
    }
  });