// filepath: /public/assets/js/shared/table-filters.js

export function initTableFilters({
    tableId,
    searchId,
    filterId = null,     // ✅ for role/status filtering
    sortId = null,       // ✅ for A-Z / Z-A sorting
    searchColumns = [],  // ✅ support multiple search columns
    filterAttr = null,   // ✅ optional: filter by <tr data-*>
    filterColumn = null  // ✅ fallback: filter by column index
  }) {
    const searchInput = document.getElementById(searchId);
    const filterSelect = filterId ? document.getElementById(filterId) : null;
    const sortSelect = sortId ? document.getElementById(sortId) : null;
    const tableBody = document.getElementById(tableId);
  
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
  
        // ✅ Filter by attribute (e.g., data-role) or column text
        let rowFilterValue = "all";
        if (filterAttr) {
          rowFilterValue = row.getAttribute(filterAttr)?.toLowerCase().trim() || "";
        } else if (filterColumn !== null) {
          rowFilterValue = row.children[filterColumn]?.textContent.toLowerCase().trim() || "";
        }
  
        const filterMatches = (filterValue === "all" || rowFilterValue === filterValue);
  
        row.style.display = (searchMatches && filterMatches) ? "" : "none";
      });
  
      // ✅ Sorting only visible rows
      const visibleRows = rows.filter(row => row.style.display !== "none");
  
      visibleRows.sort((a, b) => {
        const textA = a.children[searchColumns[0]]?.textContent.toLowerCase().trim() || "";
        const textB = b.children[searchColumns[0]]?.textContent.toLowerCase().trim() || "";
        return sortValue === "az" ? textA.localeCompare(textB) : textB.localeCompare(textA);
      });
  
      visibleRows.forEach(row => tableBody.appendChild(row));
    }
  
    // 🔗 Event bindings
    if (searchInput) searchInput.addEventListener("input", applyFilters);
    if (filterSelect) filterSelect.addEventListener("change", applyFilters);
    if (sortSelect) sortSelect.addEventListener("change", applyFilters);
  
    // Initial run
    applyFilters();
  }
  