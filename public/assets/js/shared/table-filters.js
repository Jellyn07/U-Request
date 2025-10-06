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
    const sortValue = sortSelect ? sortSelect.value : "az";

    const rows = Array.from(tableBody.querySelectorAll("tr"));

    rows.forEach(row => {
      // 🔍 Search
      const searchMatches = searchColumns.some(idx => {
        const text = row.children[idx]?.textContent.toLowerCase().trim() || "";
        return text.includes(searchValue);
      });

      // 📂 Filter by category
      let rowFilterValue = "all";
      if (filterAttr) {
        rowFilterValue = row.getAttribute(filterAttr)?.toLowerCase().trim() || "";
      } else if (filterColumn !== null) {
        rowFilterValue = row.children[filterColumn]?.textContent.toLowerCase().trim() || "";
      }
      const filterMatches = (filterValue === "all" || rowFilterValue === filterValue);

      // 🏷️ Filter by status
      const rowStatus = row.getAttribute("data-status");
      const statusMatches = (activeStatus === "All" || rowStatus === activeStatus);

      row.style.display = (searchMatches && filterMatches && statusMatches) ? "" : "none";
    });

    // 🔃 Sort visible rows
    const visibleRows = rows.filter(row => row.style.display !== "none");

    if (dateColumnIndex !== null) {
      visibleRows.sort((a, b) => {
        const dateA = new Date(a.children[dateColumnIndex]?.textContent.trim() || 0);
        const dateB = new Date(b.children[dateColumnIndex]?.textContent.trim() || 0);
        return dateB - dateA; // Newest first
      });
    } else {
      visibleRows.sort((a, b) => {
        const textA = a.children[searchColumns[0]]?.textContent.toLowerCase().trim() || "";
        const textB = b.children[searchColumns[0]]?.textContent.toLowerCase().trim() || "";
        return sortValue === "az" ? textA.localeCompare(textB) : textB.localeCompare(textA);
      });
    }

    visibleRows.forEach(row => tableBody.appendChild(row));
  }

  // 🎚️ Events
  if (searchInput) searchInput.addEventListener("input", applyFilters);
  if (filterSelect) filterSelect.addEventListener("change", applyFilters);
  if (sortSelect) sortSelect.addEventListener("change", applyFilters);

  // 🧩 Tabs integration (moved here)
  if (tabs) {
    function setActiveTab(activeTab) {
      tabs.forEach((tab, index) => {
        const isAllTab = index === 0;
        if (tab === activeTab) {
          tab.className = isAllTab
            ? "ml-5 btn bg-white hover:bg-gray-100 border border-gray-200 border-b-0 rounded-t-lg shadow-lg"
            : "btn bg-white hover:bg-gray-100 border border-gray-200 border-b-0 rounded-t-lg shadow-lg";
        } else {
          tab.className = isAllTab ? "ml-5 btn" : "btn";
        }
      });
    }

    tabs.forEach(tab => {
      tab.addEventListener("click", () => {
        activeStatus = tab.textContent.trim();
        setActiveTab(tab);
        applyFilters();
      });
    });

    // Trigger "All" tab by default
    if (tabs.length > 0) {
      tabs[0].click();
    }
  }

  // Initial filter run
  applyFilters();
}
