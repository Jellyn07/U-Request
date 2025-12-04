document.addEventListener("DOMContentLoaded", () => {
  const exportBtn = document.getElementById("export");
  if (!exportBtn) return;

  exportBtn.addEventListener("click", () => {
    const originalTable = document.querySelector("table");
    if (!originalTable) {
      alert("No table found to print!");
      return;
    }

    // 1. Clone the table to manipulate it without affecting the UI
    const tempTable = originalTable.cloneNode(true);
    
    // Get rows from both tables to compare visibility
    const originalRows = Array.from(originalTable.querySelectorAll("tbody tr"));
    const tempRows = Array.from(tempTable.querySelectorAll("tbody tr"));

    // 2. Filter Rows based on what is actually visible on screen
    tempRows.forEach((row, index) => {
      // We check the ORIGINAL row's computed style. 
      // If it's hidden (display: none) via CSS class or inline style, remove it from PDF.
      const originalRow = originalRows[index];
      const style = window.getComputedStyle(originalRow);
      
      if (style.display === "none" || style.visibility === "hidden") {
        row.remove();
      } else {
        // 3. Fix the "All Options" issue: Flatten <select> elements
        // Find any dropdowns in this visible row
        const selects = row.querySelectorAll("select");
        selects.forEach(select => {
            // In the clone, the 'value' might not reflect user changes. 
            // We need to get the selected text from the ORIGINAL table's corresponding select.
            const originalSelect = originalRow.querySelectorAll("select")[0]; // Assuming 1 select per cell or matching index
            
            let textToDisplay = "";
            
            if (originalSelect) {
                // Get the text of the currently selected option in the live table
                textToDisplay = originalSelect.options[originalSelect.selectedIndex].text;
            } else {
                // Fallback if original not found (rare)
                textToDisplay = select.options[select.selectedIndex].text;
            }

            // Create a simple text node to replace the dropdown
            const textNode = document.createTextNode(textToDisplay);
            select.parentNode.replaceChild(textNode, select);
        });
      }
    });

    // 4. Clean up columns (remove images or unwanted columns)
    const allRows = Array.from(tempTable.rows);
    const removeIndexes = [];

    // Identify columns with images (like icons) to remove
    if (allRows.length > 0) {
        Array.from(allRows[0].cells).forEach((cell, index) => {
            // You can add specific logic here if you want to remove specific columns by index
            // For now, keeping your logic regarding images, though usually headers don't have images
        });
    }
    
    // (Optional) If you specifically want to remove the 'Action' column or similar:
    // removeIndexes.push(allRows[0].cells.length - 1); // Removes last column

    allRows.forEach(row => {
      // Remove specific cells if needed (logic from your original code)
       Array.from(row.cells).forEach((cell, index) => {
        if (cell.querySelector("img")) {
           // Note: This removes the CELL if it has an image. 
           // If your status column has an image, it might get removed. 
           // If you just want to remove the image tag but keep text, do that instead.
           // cell.innerHTML = cell.innerText; 
        }
      });
    });

    // 5. PDF Generation
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF({ unit: "pt", format: "a4", orientation: "portrait" });

    const pageWidth = doc.internal.pageSize.getWidth();
    const pageHeight = doc.internal.pageSize.getHeight();
    const now = new Date();
    const formattedDate = now.toLocaleString();
    let currentY = 20;

    // --- Header Rendering ---
    const logo = document.getElementById("logo");
    if (logo && logo.complete && logo.naturalWidth > 0) {
      const imgProps = doc.getImageProperties(logo);
      const logoWidth = 60;
      const logoHeight = (imgProps.height * logoWidth) / imgProps.width;
      const logoX = (pageWidth - logoWidth) / 2;
      doc.addImage(logo, "PNG", logoX, currentY, logoWidth, logoHeight);
      currentY += logoHeight + 10;
    } else {
      currentY += 60;
    }

    doc.setFont("times", "bold");
    doc.setFontSize(14);
    doc.text("University of Southeastern Philippines", pageWidth / 2, currentY, { align: "center" });
    currentY += 15;

    doc.setFont("times", "italic");
    doc.setFontSize(10);
    doc.text(`Date: ${formattedDate}`, pageWidth / 2, currentY, { align: "center" });
    currentY += 20;

    let pageTitle = document.title || "Export";
    if (pageTitle.includes("|")) {
      pageTitle = pageTitle.split("|")[1].trim();
    }
    pageTitle = pageTitle.replace(/[\\/:*?"<>|]/g, "").trim();

    doc.setFont("times", "normal");
    doc.setFontSize(12);
    // Removed activeStatus variable dependence since we filter by visibility now
    doc.text(`${pageTitle}`, pageWidth / 2, currentY, { align: "center" });
    currentY += 10;

    // --- Table Generation ---
    doc.autoTable({
      html: tempTable,
      startY: currentY,
      styles: {
        fontSize: 9,
        textColor: [0, 0, 0],
        lineWidth: 0.5,
        lineColor: [0, 0, 0]
      },
      headStyles: {
        fillColor: [255, 204, 204],
        halign: "left",
        fontStyle: "bold",
        textColor: [0, 0, 0]
      },
      margin: { top: 40, left: 40, right: 40 },
      didDrawPage: () => {
        doc.setFontSize(9);
        doc.setFont("times", "italic");
        const footer = "*This is a system-generated report";
        const textWidth = doc.getTextWidth(footer);
        doc.text(footer, pageWidth - 40 - textWidth, pageHeight - 20);
      }
    });

    const pdfBlob = doc.output("blob");
    const url = URL.createObjectURL(pdfBlob);
    window.open(url, "_blank");
  });
});