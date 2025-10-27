export function initTableFilters({
  tableId,
  searchId,
  filterId = null,
  sortId = null,
  searchColumns = [],
  filterAttr = null,
  filterColumn = null,
  statusTabs = null,
  dateColumnIndex = null
}) {
  const searchInput = document.getElementById(searchId);
  const filterSelect = filterId ? document.getElementById(filterId) : null;
  const sortSelect = sortId ? document.getElementById(sortId) : null;
  const tableBody = document.getElementById(tableId);
  const tabs = statusTabs ? document.querySelectorAll(statusTabs) : null;

  let activeStatus = "All";

  function applyFilters() {
    const searchValue = searchInput ? searchInput.value.toLowerCase().trim() : "";
    const filterValue = filterSelect ? filterSelect.value.toLowerCase().trim() : "all";
    const sortValue = sortSelect ? sortSelect.value.toLowerCase().trim() : "all";
    const rows = Array.from(tableBody.querySelectorAll("tr"));
    const now = new Date();

    // Loop all rows for search, filter, and date
    rows.forEach(row => {
      // 🔍 Search filter
      const searchMatches = searchColumns.some(idx => {
        const text = row.children[idx]?.textContent.toLowerCase().trim() || "";
        return text.includes(searchValue);
      });

      // 📂 Category / Status filter
      let rowFilterValue = "all";
      if (filterAttr) {
        rowFilterValue = row.getAttribute(filterAttr)?.toLowerCase().trim() || "";
      } else if (filterColumn !== null) {
        rowFilterValue = row.children[filterColumn]?.textContent.toLowerCase().trim() || "";
      }
      const filterMatches = (filterValue === "all" || rowFilterValue === filterValue);

      // 🏷️ Status tab filter
      const rowStatus = row.getAttribute("data-status");
      const statusMatches = (activeStatus === "All" || rowStatus === activeStatus);

      // 📅 Date range filter
      let dateMatches = true;
      if (dateColumnIndex !== null) {
        const dateText = row.getAttribute("data-date") || row.children[dateColumnIndex]?.textContent.trim() || "";
        const rowDate = new Date(dateText);
        if (!isNaN(rowDate)) {
          const diffDays = Math.floor((now - rowDate) / (1000 * 60 * 60 * 24));
          switch (sortValue) {
            case "today":
              dateMatches = diffDays === 0;
              break;
            case "yesterday":
              dateMatches = diffDays === 1;
              break;
            case "7":
              dateMatches = diffDays <= 7;
              break;
            case "14":
              dateMatches = diffDays <= 14;
              break;
            case "30":
              dateMatches = diffDays <= 30;
              break;
            default:
              dateMatches = true;
          }
        }
      }

      // ✅ Show or hide row
      row.style.display = (searchMatches && filterMatches && statusMatches && dateMatches) ? "" : "none";
    });

    // 🧩 Sorting logic
    const visibleRows = rows.filter(row => row.style.display !== "none");

    // Case 1: Sort by Date (if column specified)
    if (dateColumnIndex !== null && ["today", "yesterday", "7", "14", "30"].includes(sortValue)) {
      visibleRows.sort((a, b) => {
        const dateA = new Date(a.children[dateColumnIndex]?.textContent.trim() || 0);
        const dateB = new Date(b.children[dateColumnIndex]?.textContent.trim() || 0);
        return dateB - dateA; // newest first
      });
    }
    // Case 2: Sort A–Z or Z–A
    else if (sortValue === "az" || sortValue === "za") {
      const colIndex = searchColumns[0] ?? 0; // default column for sorting
      visibleRows.sort((a, b) => {
        const textA = a.children[colIndex]?.textContent.toLowerCase().trim() || "";
        const textB = b.children[colIndex]?.textContent.toLowerCase().trim() || "";
        return sortValue === "az"
          ? textA.localeCompare(textB)
          : textB.localeCompare(textA);
      });
    }

    // Re-append sorted rows
    visibleRows.forEach(row => tableBody.appendChild(row));
  }

  // 🎚️ Event bindings
  if (searchInput) searchInput.addEventListener("input", applyFilters);
  if (filterSelect) filterSelect.addEventListener("change", applyFilters);
  if (sortSelect) sortSelect.addEventListener("change", applyFilters);

  // 🧩 Tabs integration
  if (tabs) {
    function setActiveTab(activeTab) {
      tabs.forEach((tab, index) => {
        const isAllTab = index === 0;
        tab.className = (tab === activeTab)
          ? (isAllTab
              ? "ml-5 btn bg-white hover:bg-gray-100 border border-gray-200 border-b-0 rounded-t-lg shadow-lg"
              : "btn bg-white hover:bg-gray-100 border border-gray-200 border-b-0 rounded-t-lg shadow-lg")
          : (isAllTab ? "ml-5 btn" : "btn");
      });
    }

    tabs.forEach(tab => {
      tab.addEventListener("click", () => {
        activeStatus = tab.textContent.trim();
        setActiveTab(tab);
        applyFilters();
      });
    });

    // Activate first tab by default
    if (tabs.length > 0) tabs[0].click();
  }

  // Initial load
  applyFilters();
}
