document.addEventListener("DOMContentLoaded", () => {
  const exportBtn = document.getElementById("export");
  if (!exportBtn) return;

  exportBtn.addEventListener("click", () => {
    const table = document.querySelector("table");
    if (!table) {
      alert("No table found to print!");
      return;
    }

    const tempTable = table.cloneNode(true);

    const activeStatus = window.currentExportStatus || "All";

    const rows = Array.from(tempTable.querySelectorAll("tbody tr"));

    rows.forEach(row => {
      const isHidden = row.style.display === "none";
      const status = row.getAttribute("data-status");

      if (
        isHidden ||
        (activeStatus !== "All" && status !== activeStatus)
      ) {
        row.remove();
      }
    });

    const allRows = Array.from(tempTable.rows);
    const removeIndexes = [];

    allRows.forEach(row => {
      Array.from(row.cells).forEach((cell, index) => {
        if (cell.querySelector("img")) {
          removeIndexes.push(index);
        }
      });
    });
    const uniqueIndexes = [...new Set(removeIndexes)].sort((a, b) => b - a);

    allRows.forEach(row => {
      uniqueIndexes.forEach(colIndex => {
        if (row.cells[colIndex]) row.deleteCell(colIndex);
      });
    });

    const { jsPDF } = window.jspdf;
    const doc = new jsPDF({ unit: "pt", format: "a4", orientation: "portrait" });

    const pageWidth = doc.internal.pageSize.getWidth();
    const pageHeight = doc.internal.pageSize.getHeight();

    const now = new Date();
    const formattedDate = now.toLocaleString();

    let currentY = 20;

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

    // ✅ University Title
    doc.setFont("times", "bold");
    doc.setFontSize(14);
    doc.text("University of Southeastern Philippines", pageWidth / 2, currentY, { align: "center" });
    currentY += 15;

    // ✅ Date
    doc.setFont("times", "italic");
    doc.setFontSize(10);
    doc.text(`Date: ${formattedDate}`, pageWidth / 2, currentY, { align: "center" });
    currentY += 20;

    // ✅ Page Title
    let pageTitle = document.title || "Export";

    if (pageTitle.includes("|")) {
      pageTitle = pageTitle.split("|")[1].trim();
    }

    pageTitle = pageTitle.replace(/[\\/:*?"<>|]/g, "").trim();

    doc.setFont("times", "normal");
    doc.setFontSize(12);
    doc.text(`${pageTitle} (${activeStatus})`, pageWidth / 2, currentY, { align: "center" });
    currentY += 10;

    // ✅ Export Cleaned Table
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

    // ✅ Open PDF
    const pdfBlob = doc.output("blob");
    const url = URL.createObjectURL(pdfBlob);
    window.open(url, "_blank");

  });
});
