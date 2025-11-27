// document.addEventListener("DOMContentLoaded", () => {
//   const exportBtn = document.getElementById("export");

//   if (!exportBtn) return;

//   exportBtn.addEventListener("click", () => {
//     const table = document.querySelector("table");
//     if (!table) {
//       alert("No table found to print!");
//       return;
//     }

//     const { jsPDF } = window.jspdf;
//     const doc = new jsPDF({ unit: "pt", format: "a4", orientation: "portrait" });
//     const pageWidth = doc.internal.pageSize.getWidth();
//     const pageHeight = doc.internal.pageSize.getHeight();
//     const now = new Date();
//     const formattedDate = now.toLocaleString();

//     // --- Add logo from hidden img ---
//     const logo = document.getElementById("logo");
//     let currentY = 20; // start top margin
//     if (logo.complete && logo.naturalWidth !== 0) {
//       const imgProps = doc.getImageProperties(logo);
//       const logoWidth = 60;
//       const logoHeight = (imgProps.height * logoWidth) / imgProps.width;
//       const logoX = (pageWidth - logoWidth) / 2;
//       doc.addImage(logo, "PNG", logoX, currentY, logoWidth, logoHeight);
//       currentY += logoHeight + 10; // leave some space after logo
//     } else {
//       console.warn("Logo not loaded properly.");
//       currentY += 70; // fallback space if logo fails
//     }
//     currentY += 5;

//     // --- University Name ---
//     doc.setFont("times", "bold");
//     doc.setFontSize(14);
//     doc.text("University of Southeastern Philippines", pageWidth / 2, currentY, { align: "center" });
//     currentY += 20;

//     // --- Date & Time ---
//     doc.setFont("times", "bolditalic");
//     doc.setFontSize(10);
//     doc.text(`Date: ${formattedDate}`, pageWidth / 2, currentY, { align: "center" });
//     currentY += 20;

//     // --- Page / Table Title ---
//     let pageTitle = document.title || "Export";
//     if (pageTitle.includes("|")) pageTitle = pageTitle.split("|")[1].trim();
//     pageTitle = pageTitle.replace(/[\\/:*?"<>|]/g, "").trim();
//     doc.setFont("times", "normal");
//     doc.setFontSize(12);
//     doc.text(pageTitle, pageWidth / 2, currentY, { align: "center" });
//     currentY += 10;

//     // --- Draw table using autoTable ---
//     doc.autoTable({
//       html: table,
//       startY: currentY,
//       styles: { fontSize: 9, textColor: [0, 0, 0], lineWidth: 0.5, lineColor: [0, 0, 0] },
//       headStyles: { fillColor: [255, 204, 204], halign: "left", fontStyle: "bold", textColor: [0, 0, 0] },
//       margin: { left: 40, right: 40 },
//       didDrawPage: function (data) {
//         doc.setFontSize(9);
//         doc.setFont("times", "italic");
//         const footerText = "*This is a system-generated report";
//         const textWidth = doc.getTextWidth(footerText);
//         doc.text(footerText, pageWidth - 40 - textWidth, pageHeight - 20);
//       }
//     });

//     // --- Open PDF in new tab ---
//     const pdfBlob = doc.output("blob");
//     const pdfUrl = URL.createObjectURL(pdfBlob);
//     window.open(pdfUrl, "_blank");
//   });
// });
document.addEventListener("DOMContentLoaded", () => {
  const exportBtn = document.getElementById("export");

  if (!exportBtn) return;

  exportBtn.addEventListener("click", () => {
    const table = document.querySelector("table");
    if (!table) {
      alert("No table found to print!");
      return;
    }

    // ✅ NEW — clone table and remove columns with img
    const tempTable = table.cloneNode(true);

    // ✅ Get active tab from global variable (set in tab script)
    const activeStatus = window.currentExportStatus || "All";

    // ✅ Remove rows not matching active tab OR hidden by filters
    const rows = Array.from(tempTable.querySelectorAll("tbody tr"));

    rows.forEach(row => {
      const status = row.getAttribute("data-status");
      const isHidden = row.style.display === "none";

      if (
        isHidden ||
        (activeStatus !== "All" && status !== activeStatus)
      ) {
        row.remove();
      }
    });

    // // Detect columns containing images
    // const rows = Array.from(tempTable.rows);
    const removeColumns = new Set();

    rows.forEach(row => {
      [...row.cells].forEach((cell, index) => {
        if (cell.querySelector("img")) {
          removeColumns.add(index);
        }
      });
    });

    // Remove those columns
    rows.forEach(row => {
      let offset = 0;
      removeColumns.forEach(idx => {
        row.deleteCell(idx - offset);
        offset++;
      });
    });

    // ✅ Use cleaned table (tempTable) for export

    const { jsPDF } = window.jspdf;
    const doc = new jsPDF({ unit: "pt", format: "a4", orientation: "portrait" });
    const pageWidth = doc.internal.pageSize.getWidth();
    const pageHeight = doc.internal.pageSize.getHeight();
    const now = new Date();
    const formattedDate = now.toLocaleString();

    // --- Add logo from hidden img ---
    const logo = document.getElementById("logo");
    let currentY = 20; // start top margin
    if (logo.complete && logo.naturalWidth !== 0) {
      const imgProps = doc.getImageProperties(logo);
      const logoWidth = 60;
      const logoHeight = (imgProps.height * logoWidth) / imgProps.width;
      const logoX = (pageWidth - logoWidth) / 2;
      doc.addImage(logo, "PNG", logoX, currentY, logoWidth, logoHeight);
      currentY += logoHeight + 10;
    } else {
      console.warn("Logo not loaded properly.");
      currentY += 70;
    }

    currentY += 5;

    // --- University Name ---
    doc.setFont("times", "bold");
    doc.setFontSize(14);
    doc.text("University of Southeastern Philippines", pageWidth / 2, currentY, { align: "center" });
    currentY += 20;

    // --- Date & Time ---
    doc.setFont("times", "bolditalic");
    doc.setFontSize(10);
    doc.text(`Date: ${formattedDate}`, pageWidth / 2, currentY, { align: "center" });
    currentY += 20;

    // --- Page / Table Title ---
    let pageTitle = document.title || "Export";
    if (pageTitle.includes("|")) pageTitle = pageTitle.split("|")[1].trim();
    pageTitle = pageTitle.replace(/[\\/:*?"<>|]/g, "").trim();
    doc.setFont("times", "normal");
    doc.setFontSize(12);
    doc.text(`${pageTitle} (${activeStatus})`, pageWidth / 2, currentY, { align: "center" });
    currentY += 10;

    // ✅ Use the cleaned table (tempTable) instead of original
    doc.autoTable({
      html: tempTable,
      startY: currentY,
      styles: { fontSize: 9, textColor: [0, 0, 0], lineWidth: 0.5, lineColor: [0, 0, 0] },
      headStyles: { fillColor: [255, 204, 204], halign: "left", fontStyle: "bold", textColor: [0, 0, 0] },
      margin: { left: 40, right: 40 },
      didDrawPage: function (data) {
        doc.setFontSize(9);
        doc.setFont("times", "italic");
        const footerText = "*This is a system-generated report";
        const textWidth = doc.getTextWidth(footerText);
        doc.text(footerText, pageWidth - 40 - textWidth, pageHeight - 20);
      }
    });

    // --- Open PDF in new tab ---
    const pdfBlob = doc.output("blob");
    const pdfUrl = URL.createObjectURL(pdfBlob);
    window.open(pdfUrl, "_blank");
  });
});
