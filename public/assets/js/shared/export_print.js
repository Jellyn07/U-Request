document.addEventListener("DOMContentLoaded", () => {
  const printBtn = document.getElementById("print");
  const exportBtn = document.getElementById("export");

  // ✅ PRINT FUNCTION
  if (printBtn) {
    printBtn.addEventListener("click", () => {
      const table = document.querySelector("table");
      if (!table) {
        alert("No table found to print!");
        return;
      }

      // Clone table to print
      const clonedTable = table.cloneNode(true);

      // Clean up styles for printing
      clonedTable.style.width = "100%";
      clonedTable.style.borderCollapse = "collapse";
      clonedTable.style.boxShadow = "none";

      clonedTable.querySelectorAll("th, td").forEach((cell) => {
        cell.style.border = "1px solid black";
        cell.style.padding = "6px";
        cell.style.fontSize = "13px";
        cell.style.whiteSpace = "nowrap";
      });

      // ✅ Use correct absolute image path for local XAMPP
      const logoSrc = window.location.origin + "/public/assets/img/usep.png";

      const printWindow = window.open("", "_blank");
      printWindow.document.open();
      printWindow.document.write(`
        <html>
          <head>
            <title>Print Table</title>
            <style>
              * { box-sizing: border-box; }
              body {
                font-family: Arial, sans-serif;
                background: white;
                color: black;
                margin: 40px;
              }
              img {
                display: block;
                margin: 0 auto 10px auto;
                width: 80px;
                height: auto;
              }
              h2 {
                text-align: center;
                margin-bottom: 20px;
                font-size: 18px;
              }
              table {
                width: 100%;
                border-collapse: collapse;
                border: 1px solid black;
                box-shadow: none;
              }
              th {
                background: #f2f2f2;
                font-weight: bold;
              }
              th, td {
                border: 1px solid black;
                padding: 8px;
                text-align: left;
              }
              tr {
                page-break-inside: avoid;
                page-break-after: auto;
              }
              @media print {
                body {
                  -webkit-print-color-adjust: exact;
                  print-color-adjust: exact;
                }
              }
            </style>
          </head>
          <body>
            <img src="${logoSrc}" alt="USeP Logo">
            <h2>University of Southeastern Philippines - U-Request</h2>
            ${clonedTable.outerHTML}
          </body>
        </html>
      `);
      printWindow.document.close();

      // Wait for image and content to load
      printWindow.onload = () => {
        setTimeout(() => {
          printWindow.focus();
          printWindow.print();
          printWindow.close();
        }, 500);
      };
    });
  }

  // ✅ EXPORT FUNCTION (CSV)
  if (exportBtn) {
    exportBtn.addEventListener("click", () => {
      const table = document.querySelector("table");
      if (!table) {
        alert("No table found to print!");
        return;
      }

      // ✅ Determine title
      let pageTitle = document.title || "Export";

      if (pageTitle.includes("|")) {
        pageTitle = pageTitle.split("|")[1].trim();
      }

      pageTitle = pageTitle.replace(/[\\/:*?"<>|]/g, "").trim();

      // ✅ Generate PDF
      const { jsPDF } = window.jspdf;
      const doc = new jsPDF({
        unit: "pt",
        format: "a4",
        orientation: "portrait"
      });

      // ✅ Title
      doc.setFontSize(14);
      doc.text(`University of Southeastern Philippines`, 40, 40);
      doc.setFontSize(12);
      doc.text(pageTitle, 40, 60);

      // ✅ Convert table
      doc.autoTable({
        html: table,
        startY: 80,
        styles: { fontSize: 9 },
        headStyles: { fillColor: [255, 204, 204], halign: "center", fontStyle: "bold" },
        tableLineWidth: 0.2,
        borderColor: 200,
        margin: { left: 40, right: 40 }
      });

      // ✅ Output into new browser tab
      const pdfBlob = doc.output("blob");
      const pdfUrl = URL.createObjectURL(pdfBlob);

      window.open(pdfUrl, "_blank"); // <-- preview without download dialog
    });
  }



    // ✅ Helper: Fetch logo
    function getBase64FromURL(url) {
      return fetch(url)
        .then(res => res.blob())
        .then(
          blob =>
            new Promise((resolve) => {
              const reader = new FileReader();
              reader.onloadend = () => resolve(reader.result.replace(/^data:image\/(png|jpg);base64,/, ""));
              reader.readAsDataURL(blob);
            })
        );
  }




  // ✅ EXPORT FUNCTION (CSV)
  // if (exportBtn) {
  //   exportBtn.addEventListener("click", () => {
  //     const table = document.querySelector("table");
  //     if (!table) {
  //       alert("No table found to export!");
  //       return;
  //     }

  //     let csv = [];
  //     const rows = table.querySelectorAll("tr");
  //     for (let i = 0; i < rows.length; i++) {
  //       const cols = rows[i].querySelectorAll("th, td");
  //       let row = [];
  //       cols.forEach((col) => {
  //         row.push('"' + col.innerText.replace(/"/g, '""') + '"');
  //       });
  //       csv.push(row.join(","));
  //     }

  //     const csvFile = new Blob([csv.join("\n")], { type: "text/csv" });
  //     const downloadLink = document.createElement("a");
  //     downloadLink.download = "URequest_Table_Export.csv";
  //     downloadLink.href = window.URL.createObjectURL(csvFile);
  //     downloadLink.style.display = "none";
  //     document.body.appendChild(downloadLink);
  //     downloadLink.click();
  //     document.body.removeChild(downloadLink);
  //   });
  // }
});
