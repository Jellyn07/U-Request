<?php
$name        = $_GET['name'] ?? '';
$office      = $_GET['office'] ?? '';
$tracking    = $_GET['tracking'] ?? '';
$date        = $_GET['date'] ?? '';
$finished    = $_GET['finished'] ?? '';
$location    = $_GET['location'] ?? '';
$type        = $_GET['type'] ?? '';
$unit        = $_GET['unit'] ?? '';
$priority    = $_GET['priority'] ?? '';
$desc        = $_GET['description'] ?? '';
$personnel   = $_GET['personnel'] ?? '';
$materials   = $_GET['materials'] ?? '';
$status      = $_GET['status'] ?? '';
$image       = $_GET['image'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>U-Request | Export Form</title>
  <link rel="stylesheet" href="/public/assets/css/output.css" />
  <link rel="icon" href="/public/assets/img/upper_logo.png"/>
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&display=swap" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
</head>
<style>
/* 1. Page Setup */
.request-page {
    width: 210mm !important;
    height: 297mm !important;
    max-width: 210mm;
    max-height: 297mm;
    box-sizing: border-box;
    padding: 10mm;
    margin: 0 auto;
    background-color: white;
    font-family: sans-serif; /* Ensure consistent font rendering */
    margin-bottom: 10px;
}

/* 2. TABLE STYLING - PERFECT CENTERING FIX */
.pdf-table {
    width: 100%;
    border-collapse: separate !important; 
    border-spacing: 0;
    border-top: 1px solid black; 
    border-left: 1px solid black;
    border-bottom: 0;
    border-right: 0;
}

.pdf-table td, 
.pdf-table th {
    /* Borders */
    border-bottom: 1px solid black;
    border-right: 1px solid black;
    border-top: 0;
    border-left: 0;
    
    /* CENTERING MAGIC: */
    /* 1. Equal top/bottom padding makes it balanced */
    padding: 6px 3px; 
    padding-bottom: 10px;
    
    /* 2. Force content to the vertical middle */
    vertical-align: middle; 
    
    /* 3. Ensure line-height is standard so text doesn't sit low */
    line-height: 1.4;
}

/* Helper: Reset P tags inside table so they don't push text around */
.pdf-table p {
    margin: 0;
    padding: 0;
}

/* Specific Helper: For cells that MUST start at the top (like signature boxes or big text areas) */
.align-top-cell {
    vertical-align: top !important;
   /* padding-top: 8px !important;  Ensure content doesn't hit the border immediately */
}

/* Header Text Specifics */
.header-text p {
    margin-bottom: 2px; /* Slight spacing between header lines */
}

/* Background color helper */
.bg-header {
    background-color: #f9fafb; /* gray-50 */
}
</style>

<body class="bg-gray-300">
    <div class="fixed flex gap-3 items-center justify-center w-full bg-white p-3 rounded-b-2xl shadow top-0 z-50">
        <a href="javascript:history.back()" title="Back" class="btn btn-secondary flex items-center text-sm">
            Back
        </a>
        <button title="Export" id="export" class="btn btn-primary flex items-center text-sm">
            <img src="/public/assets/img/export-white.png" alt="export icon" class="w-4 h-4 mr-2">
             Export
        </button>
    </div>
    
    <p class="h-24"></p>
    
    <div id="exportPages">
        
        <div class="request-page w-[210mm] h-[297mm] mx-auto my-10 relative">
            
            <table class="pdf-table">
                <tr>
                    <td rowspan="5" class="w-28 text-center">
                        <img src="/public/assets/img/usep.png" alt="USEP Logo" class="w-24 h-24 mx-auto object-contain">
                    </td>
                    <td rowspan="5" class="p-1 header-text">
                        <p class="text-center font-medium text-xs">Republic of the Philippines</p>
                        <h2 class="text-center font-semibold text-lg font-[cinzel]">University of Southeastern Philippines</h2>
                        <h4 class="text-center font-medium text-xs">Iñigo St., Obrero, Davao City 8000</h4>
                        <h4 class="text-center font-medium text-xs">Telephone: (082) 227-8192</h4>
                        <h4 class="text-center font-medium text-xs">Website: www.usep.edu.ph</h4>
                        <h4 class="text-center font-medium text-xs">Email: president@usep.edu.ph</h4>
                    </td>
                    <td class="w-24 text-xs pl-2 font-bold bg-header">Form No.</td>
                    <td class="text-xs pl-2">FM-USeP-HOR-03</td>
                </tr>
                <tr>
                    <td class="text-xs pl-2 font-bold bg-header">Issue Status</td>
                    <td class="text-xs pl-2">02</td>
                </tr>
                <tr>
                    <td class="text-xs pl-2 font-bold bg-header">Revision No.</td>
                    <td class="text-xs pl-2">01</td>
                </tr>
                <tr>
                    <td class="text-xs pl-2 font-bold bg-header">Date Effective</td>
                    <td class="text-xs pl-2">01 March 2018</td>
                </tr>
                <tr>
                    <td class="text-xs pl-2 font-bold bg-header">Approved by</td>
                    <td class="text-xs pl-2">President</td>
                </tr>
                <tr>
                    <td colspan="4" class="text-center font-bold text-lg bg-header">REPAIR REQUEST FORM</td>
                </tr>
            </table>

            <table class="pdf-table mt-4 w-full text-xs">
                <tr>
                    <td class="w-36 font-bold bg-header">Requesting Office/ Person:</td>
                    <td colspan="3">
                        <p><input type="text" name="requester" value="<?= htmlspecialchars($name) ?>" readonly></p>
                    </td>
                </tr>
                <tr>
                    <td class="font-bold bg-header">Mode of Request:</td>
                    <td colspan="3">U-Request System</td>
                </tr>
                <tr>
                    <td class="font-bold bg-header">Nature of Request:</td>
                    <td colspan="3">
                        <p><input type="text" name="nature" value="<?= htmlspecialchars($type) ?>" readonly></p>
                    </td>
                </tr>
                <tr>
                    <td colspan="4" class="text-left align-top-cell h-36">
                        <p class="font-bold mb-2">Request Details:</p>
                        <textarea name="description"><?= htmlspecialchars($desc) ?></textarea>
                        </td>
                </tr>
            </table>

            <table class="pdf-table w-full text-xs mt-[-1px]" style="border-top: none; margin-top: 0.5px;">
                <tr>
                    <td class="w-1/3 align-top-cell h-32 relative">
                        <p class="font-semibold absolute top-2 left-2">Prepared by:</p>
                        
                        <div class="absolute bottom-6 left-0 right-0 px-4">
                             <div class="border-b border-black w-full mb-1"></div>
                             <p class="text-center text-[10px]">Signature Over Printed Name</p>
                        </div>
                    </td>
                    <td class="w-1/3 align-top-cell h-32 relative">
                        <p class="font-semibold absolute top-2 left-2">Reviewed by:</p>
                        
                        <div class="absolute bottom-6 left-0 right-0 px-4">
                             <div class="border-b border-black w-full mb-1"></div>
                             <p class="text-center text-[10px]">Signature Over Printed Name</p>
                        </div>
                    </td>
                    <td class="w-1/3 align-top-cell h-32 relative">
                        <p class="font-semibold absolute top-2 left-2">Endorsed by:</p>
                        
                        <div class="absolute bottom-6 left-0 right-0 px-4">
                             <div class="border-b border-black w-full mb-1"></div>
                             <p class="text-center text-[10px]">Signature Over Printed Name</p>
                        </div>
                    </td>
                </tr>
            </table>

            <hr class="border-b border-gray-400 mt-8 border-dashed">

            <div class="mt-8">
                <p class="text-sm text-center mb-4 font-semibold text-gray-700">PHOTO EVIDENCE</p>
                <img src="<?= !empty($image) 
                        ? '/public/uploads/' . htmlspecialchars($image) 
                        : '/public/assets/img/default-img.png' ?>"
                        onerror="this.src='/public/assets/img/default-img.png'"
                        class="max-h-48 mx-auto block rounded-md border border-gray-200 p-1"
                        alt="Photo Evidence">
            </div>
            
        </div>

        <div class="request-page w-[210mm] h-[297mm] mx-auto my-10 relative">
            <table class="pdf-table">
                <tr>
                    <td rowspan="5" class="w-28 text-center">
                        <img src="/public/assets/img/usep.png" alt="USEP Logo" class="w-24 h-24 mx-auto object-contain">
                    </td>
                    <td rowspan="5" class="p-2 header-text">
                        <p class="text-center font-medium text-xs">Republic of the Philippines</p>
                        <h2 class="text-center font-semibold text-lg font-[cinzel]">University of Southeastern Philippines</h2>
                        <h4 class="text-center font-medium text-xs">Iñigo St., Obrero, Davao City 8000</h4>
                        <h4 class="text-center font-medium text-xs">Telephone: (082) 227-8192</h4>
                        <h4 class="text-center font-medium text-xs">Website: www.usep.edu.ph</h4>
                        <h4 class="text-center font-medium text-xs">Email: president@usep.edu.ph</h4>
                    </td>
                    <td class="w-24 text-xs pl-2 font-bold bg-header">Form No.</td>
                    <td class="text-xs pl-2">FM-USeP-HOR-03</td>
                </tr>
                <tr>
                    <td class="text-xs pl-2 font-bold bg-header">Issue Status</td>
                    <td class="text-xs pl-2">02</td>
                </tr>
                <tr>
                    <td class="text-xs pl-2 font-bold bg-header">Revision No.</td>
                    <td class="text-xs pl-2">01</td>
                </tr>
                <tr>
                    <td class="text-xs pl-2 font-bold bg-header">Date Effective</td>
                    <td class="text-xs pl-2">01 March 2018</td>
                </tr>
                <tr>
                    <td class="text-xs pl-2 font-bold bg-header">Approved by</td>
                    <td class="text-xs pl-2">President</td>
                </tr>
                <tr>
                    <td colspan="4" class="text-center font-bold text-lg bg-header">REPAIR JOB ORDER</td>
                </tr>
            </table>

            <div class="flex justify-between">
                <div class="mt-5 flex">
                    <div class="w-32">
                        <p class="text-xs">Date Requested</p>
                        <p class="text-xs">Date Needed</p>
                        <p class="text-xs">Requested by</p>
                    </div>
                    <div>
                        <p class="text-xs">: <strong><input type="text" name="requester" value="<?= !empty($date) ? date("F d, Y", strtotime($date)) : '' ?>" readonly></strong></p> 
                        <p class="text-xs">:</p>
                        <p class="text-xs">: <strong><input type="text" name="requester" value="<?= htmlspecialchars($name) ?>" readonly></strong></p> </p>
                    </div>
                </div>
                <div class="mt-5">
                    <p class="text-xs">JO No. <strong><?= htmlspecialchars($tracking ?? 'Not Available') ?></strong></p></p>
                    <p class="text-xs">For Maintenance In-charge:</p>
                </div>
            </div>

            <!-- In diagnose details use this if this ⧄ if the selected one and use this ◻ if not-->

            <table class="pdf-table mt-4 w-full text-xs">
                <tr>
                    <td rowspan="2" class="text-center">Diagnose Details</td>
                    <td colspan="2" class="text-center">Report Details</td>
                </tr>
                <tr>
                    <td class="text-center">Action Taken</td>
                    <td>
                        <div>
                            <p class="text-center">Repair Results</p>
                            <p class="text-center italic text-[10px]">To be filled by GSU Head</p>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="flex">
                        <div class="w-1/2 flex items-center gap-1">
                            <p class="ml-2"><?= ($type == 'Carpentry') ? '☑' : '◻' ?></p>
                            <p>Carpentry</p>
                        </div>
                        <div class="w-1/2 flex items-center gap-1">
                            <p><?= ($type == 'Masonry') ? '☑' : '◻' ?></p>
                            <p>Masonry</p>
                        </div>
                    </td>
                    <td rowspan="6"></td>
                    <td>OK&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
                </tr>
                <tr>
                    <td class="flex">
                        <div class="w-1/2 flex items-center gap-1">
                            <p class="ml-2"><?= ($type == 'Aircondition') ? '☑' : '◻' ?></p>
                            <p>Aircondition</p>
                        </div>
                        <div class="w-1/2 flex items-center gap-1">
                            <p><?= ($type == 'Electrical') ? '☑' : '◻' ?></p>
                            <p>Electrical</p>
                        </div>
                    </td>
                    <td rowspan="3" class="align-top-cell">Not OK : <br>Remarks :</td>
                </tr>
                <tr>
                    <td class="flex">
                        <div class="w-1/2 flex items-center gap-1">
                            <p class="ml-2"><?= ($type == 'Welding') ? '☑' : '◻' ?></p>
                            <p>Welding</p>
                        </div>
                        <div class="w-1/2 flex items-center gap-1">
                            <p><?= ($type == 'Plumbing') ? '☑' : '◻' ?></p>
                            <p>Plumbing</p>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="flex">
                        <div class="w-1/2 flex items-center gap-1">
                            <p class="ml-2"><?= ($type == 'Painting') ? '☑' : '◻' ?></p>
                            <p>Painting</p>
                        </div>
                        <div class="w-1/2">
                            <div class="flex items-center gap-1">
                                <p><?= (!in_array($type, ['Carpentry','Masonry','Aircondition','Electrical','Welding','Plumbing','Painting'])) ? '☑' : '◻' ?></p>
                                <p>Others (Please Specify)</p>
                            </div>
                            <p class="border-b border-black w-full mt-1">
                                <?= (!in_array($type, ['Carpentry','Masonry','Aircondition','Electrical','Welding','Plumbing','Painting'])) ? htmlspecialchars($type) : '&nbsp;' ?>
                            </p>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td rowspan="2" class="align-top-cell">Remarks:</td>
                    <td>Repair Started: </td>
                </tr>
                <tr>
             <td>
                Repair Ended:
                <strong>
                    <input type="text" name="date_finished"
                    value="<?=
                        !empty($finished)
                        ? date('F d, Y', strtotime($finished))
                        : htmlspecialchars($status ?? 'Not Available')
                    ?>"
                    readonly
                    >
                </strong>
                </td>
                </tr>
                <tr>
                    <td colspan="3">(*Note: Indicate parts number if applicable)</td>
                </tr>
            </table>

<table class="pdf-table w-full text-xs mt-[-1px]" id="materials-table" style="border-top: none; margin-top: 0.5px;">
    <tr class="text-center font-semibold">
        <td>MATERIALS NEEDED</td>
        <td>QUANTITY</td>
        <td>STOCK AVAILABLE</td>
        <td>TO BE PURCHASED</td>
    </tr>
    <!-- 8 empty rows -->
    <tr><td></td><td></td><td></td><td></td></tr>
    <tr><td></td><td></td><td></td><td></td></tr>
    <tr><td></td><td></td><td></td><td></td></tr>
    <tr><td></td><td></td><td></td><td></td></tr>
    <tr><td></td><td></td><td></td><td></td></tr>
    <tr><td></td><td></td><td></td><td></td></tr>
    <tr><td></td><td></td><td></td><td></td></tr>
    <tr><td></td><td></td><td></td><td></td></tr>
</table>

<script>
const table = document.getElementById("materials-table");

// Get all rows except header
const rows = Array.from(table.querySelectorAll("tr")).slice(1); 

// Pass PHP $materials safely
const materialsString = <?php echo json_encode($materials); ?> || '';
const materialsArray = materialsString.split(",").map(item => item.trim()).filter(Boolean);

// Fill each row with the material info
rows.forEach((row, index) => {
    const data = materialsArray[index];
    if(data){
        // Expect format: Material (Qty: 5, Stock: 3)
        const match = data.match(/^(.*)\s+\(Qty:\s*(\d+),\s*Stock:\s*(\d+)\)$/);
        if(match){
            const name = match[1];           // Material Name
            const qtyNeeded = parseInt(match[2]); // Quantity Needed
            const stock = parseInt(match[3]);     // Stock Available
            const toPurchase = Math.max(qtyNeeded - stock, 0); // To Be Purchased

            row.cells[0].textContent = name;
            row.cells[1].textContent = qtyNeeded;
            row.cells[2].textContent = stock;
            row.cells[3].textContent = toPurchase;
        } else {
            // fallback if format doesn't match
            row.cells[0].textContent = data;
            row.cells[1].textContent = '';
            row.cells[2].textContent = '';
            row.cells[3].textContent = '';
        }
    } else {
        // leave remaining rows blank
        row.cells[0].textContent = '';
        row.cells[1].textContent = '';
        row.cells[2].textContent = '';
        row.cells[3].textContent = '';
    }
});
</script>




            <p class="text-xs italic">Write N/A if not applicable.</p>

            <table class="w-full text-xs mt-[-1px]">
                <tr>
                    <td class="w-1/3 align-top-cell h-32 relative">
                        <p class="font-semibold absolute top-2 left-2">Diagnosed by: </p>
                        
                        <div class="absolute bottom-6 left-0 right-0 px-4">
                             <div class="border-b border-black w-full mb-1"></div>
                             <p class="text-center text-[10px]">Name Over Signature/ Date</p>
                        </div>
                    </td>
                    <td class="w-1/3 align-top-cell h-32 relative">
                        <p class="font-semibold absolute top-2 left-2">Repaired by:</p></p>
                        
                        <div class="absolute bottom-6 left-0 right-0 px-4">
                            <input type="text" name="requester" value="<?= htmlspecialchars($personnel) ?>" readonly>
                             <div class="border-b border-black w-full mb-1"></div>
                             <p class="text-center text-[10px]">Name Over Signature/ Date</p>
                        </div>
                    </td>
                    <td class="w-1/3 align-top-cell h-32 relative">
                        <p class="font-semibold absolute top-2 left-2">Verified by:</p>
                        
                        <div class="absolute bottom-6 left-0 right-0 px-4">
                            <input type="text" name="requester" value="Jilmar Montillano" readonly>
                             <div class="border-b border-black w-full mb-1"></div>
                             <p class="text-center text-[10px]">GSU Head/ Date</p>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td class="w-1/3 align-top-cell h-32 relative">
                        <p class="font-semibold absolute top-2 left-2">Accepted by:</p>
                        
                        <div class="absolute bottom-6 left-0 right-0 px-4">
                             <div class="border-b border-black w-full mb-1"></div>
                             <p class="text-center text-[10px]">Head of Office or Dept. / Date</p>
                        </div>
                    </td>
                    <td></td>
                </tr>
            </table>

        </div>
    </div>
</body>

<script>
document.getElementById("export").addEventListener("click", async () => {
    const { jsPDF } = window.jspdf;
    
    // Create PDF with A4 dimensions
    const pdf = new jsPDF('p', 'mm', 'a4');
    const pdfWidth = 210;
    const pdfHeight = 297;
    const pages = document.querySelectorAll(".request-page");

    for (let i = 0; i < pages.length; i++) {
        const page = pages[i];

        // Ensure text is rendered consistently
        page.style.textRendering = "geometricPrecision";

        const canvas = await html2canvas(page, {
            scale: 2, 
            useCORS: true,
            scrollY: -window.scrollY, 
            windowWidth: document.documentElement.offsetWidth,
            windowHeight: document.documentElement.offsetHeight,
            backgroundColor: '#ffffff',
            onclone: (clonedDoc) => {
                // Ensure no transformations mess up text pos
                const clonedPage = clonedDoc.querySelector(".request-page");
                clonedPage.style.transform = "none";
            }
        });
        
        page.style.textRendering = "auto";

        const imgData = canvas.toDataURL("image/png");

        if (i > 0) {
            pdf.addPage();
        }

        // Exact match mapping
        pdf.addImage(imgData, "PNG", 0, 0, pdfWidth, pdfHeight);
    }

    const pdfBlob = pdf.output("blob");
    const url = URL.createObjectURL(pdfBlob);
    window.open(url, "_blank");
});
</script>
</html>