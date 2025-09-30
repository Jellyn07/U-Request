export function initTableFilters({
  tableId,
  searchId,
  filterId = null,
  sortId = null,
  searchColumns = [],
  filterAttr = null,
  filterColumn = null,
  statusTabs = null,   // ✅ NEW
  dateColumnIndex = null // ✅ NEW: column index for request_date
}) {
  const searchInput = document.getElementById(searchId);
  const filterSelect = filterId ? document.getElementById(filterId) : null;
  const sortSelect = sortId ? document.getElementById(sortId) : null;
  const tableBody = document.getElementById(tableId);
  const tabs = statusTabs ? document.querySelectorAll(statusTabs) : null;

  let activeStatus = "All"; // ✅ track which tab is active

  function applyFilters() {
    const searchValue = searchInput ? searchInput.value.toLowerCase().trim() : "";
    const filterValue = filterSelect ? filterSelect.value.toLowerCase().trim() : "all";
    const sortValue = sortSelect ? sortSelect.value : "az";

    const rows = Array.from(tableBody.querySelectorAll("tr"));

    rows.forEach(row => {
      // ✅ Search across multiple columns
      const searchMatches = searchColumns.some(idx => {
        const text = row.children[idx]?.textContent.toLowerCase().trim() || "";
        return text.includes(searchValue);
      });

      // ✅ Filter by category (dropdown)
      let rowFilterValue = "all";
      if (filterAttr) {
        rowFilterValue = row.getAttribute(filterAttr)?.toLowerCase().trim() || "";
      } else if (filterColumn !== null) {
        rowFilterValue = row.children[filterColumn]?.textContent.toLowerCase().trim() || "";
      }
      const filterMatches = (filterValue === "all" || rowFilterValue === filterValue);

      // ✅ Filter by Status tab
      const rowStatus = row.getAttribute("data-status");
      const statusMatches = (activeStatus === "All" || rowStatus === activeStatus);

      row.style.display = (searchMatches && filterMatches && statusMatches) ? "" : "none";
    });

    // ✅ Sorting visible rows
    const visibleRows = rows.filter(row => row.style.display !== "none");

    if (dateColumnIndex !== null) {
      // Always sort by request_date newest first
      visibleRows.sort((a, b) => {
        const dateA = new Date(a.children[dateColumnIndex]?.textContent.trim() || 0);
        const dateB = new Date(b.children[dateColumnIndex]?.textContent.trim() || 0);
        return dateB - dateA; // ✅ Newest first
      });
    } else {
      // fallback to alphabetical sort
      visibleRows.sort((a, b) => {
        const textA = a.children[searchColumns[0]]?.textContent.toLowerCase().trim() || "";
        const textB = b.children[searchColumns[0]]?.textContent.toLowerCase().trim() || "";
        return sortValue === "az" ? textA.localeCompare(textB) : textB.localeCompare(textA);
      });
    }

    visibleRows.forEach(row => tableBody.appendChild(row));
  }

  // 🔗 Event bindings
  if (searchInput) searchInput.addEventListener("input", applyFilters);
  if (filterSelect) filterSelect.addEventListener("change", applyFilters);
  if (sortSelect) sortSelect.addEventListener("change", applyFilters);

  // ✅ Status tab click binding
  if (tabs) {
    tabs.forEach(tab => {
      tab.addEventListener("click", () => {
        activeStatus = tab.innerText.trim(); // e.g., "In Progress"
        tabs.forEach(t => t.classList.remove("bg-red-100", "shadow-lg"));
        tab.classList.add("bg-red-100", "shadow-lg");
        applyFilters();
      });
    });
  }

  // Initial run
  applyFilters();
}
