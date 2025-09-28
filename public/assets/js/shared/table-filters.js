export function initTableFilters({
  tableId,
  searchId,
  filterId = null,
  sortId = null,
  searchColumns = [],
  filterAttr = null,
  filterColumn = null,
  sortColumn = null   // ✅ NEW: specify which column to sort
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
    const searchMatches = searchColumns.some(idx => {
      const text = row.children[idx]?.textContent.toLowerCase().trim() || "";
      return text.includes(searchValue);
    });

    let rowFilterValue = "all";
    if (filterAttr) {
      rowFilterValue = row.getAttribute(filterAttr)?.toLowerCase().trim() || "";
    } else if (filterColumn !== null) {
      rowFilterValue = row.children[filterColumn]?.textContent.toLowerCase().trim() || "";
    }

    const filterMatches = (filterValue === "all" || rowFilterValue === filterValue);

    row.style.display = (searchMatches && filterMatches) ? "" : "none";
  });

  // ✅ Sorting
  const visibleRows = rows.filter(row => row.style.display !== "none");

  visibleRows.sort((a, b) => {
    const colIndex = sortColumn !== null ? sortColumn : searchColumns[0]; // ✅ pick sort column
    const textA = a.children[colIndex]?.textContent.toLowerCase().trim() || "";
    const textB = b.children[colIndex]?.textContent.toLowerCase().trim() || "";
    return sortValue === "az" ? textA.localeCompare(textB) : textB.localeCompare(textA);
  });

  visibleRows.forEach(row => tableBody.appendChild(row));
}

if (searchInput) searchInput.addEventListener("input", applyFilters);
if (filterSelect) filterSelect.addEventListener("change", applyFilters);
if (sortSelect) sortSelect.addEventListener("change", applyFilters);

applyFilters();
}
