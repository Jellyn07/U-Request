<!DOCTYPE html>
<html lang="en">
<head>
    <title>UTRMS</title>
    <link rel="icon" href="../../assets/icon/logo.png" type="icon">
    <link rel="stylesheet" type="text/css" href="../../css/GSUAdmin/dashboard.css">
    <link rel="stylesheet" type="text/css" href="../../css/shared/admin-menu.css">
    <link rel="stylesheet" type="text/css" href="../../css/shared/admin-global.css">
    <link rel="stylesheet" type="text/css" href="../../css/MotorpoolAdmin/vehicle.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div id="adminMotorpool-menu"></div>
    <script src="../../js/admin-menu.js"></script>
    <p class="type">VEHICLE</p>
    
    <div class="toolbar">
    <form method="GET" id="vehicleSearchForm" style="display: flex; gap: 10px; align-items: center;">
        <input type="search" name="vsearch" id="vehiclesearch" placeholder="Search Vehicle"
               value="<?= htmlspecialchars($_GET['vsearch'] ?? '') ?>" oninput="handleVehicleSearch()">

        <select class="sorting" name="vsort" id="vehiclesort" onchange="handleVehicleSearch()">
            <option value="id" <?= ($_GET['vsort'] ?? '') === 'id' ? 'selected' : '' ?>>Sort by ID</option>
            <option value="az" <?= ($_GET['vsort'] ?? '') === 'az' ? 'selected' : '' ?>>Sort A-Z</option>
            <option value="za" <?= ($_GET['vsort'] ?? '') === 'za' ? 'selected' : '' ?>>Sort Z-A</option>
        </select>
    </form>

    <div style="display: flex; gap: 10px;">
        <button onclick="printSection('vehicle')" class="print">
            <img src="../../assets/icon/printing.png" alt="Print">&nbsp;Print
        </button>
        <button class="addVehicle" id="addvehicle">
            <img src="../../assets/icon/add.png" alt="Add" style="width: 1.2vw; height: 1.2vw;">&nbsp;Add Vehicle
        </button>
    </div>
</div>
