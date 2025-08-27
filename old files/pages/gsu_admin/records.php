<!DOCTYPE html>
<html lang="en">
<head>
    <title>GSU System</title>
    <link rel="icon" href="../../assets/icon/logo.png" type="icon">
    <link rel="stylesheet" type="text/css" href="../../css/shared/admin-menu.css">
    <link rel="stylesheet" type="text/css" href="../../css/shared/admin-global.css">
    <link rel="stylesheet" type="text/css" href="../../css/GSUAdmin/personnel.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<style>
    .upload-section {
    margin: 20px 0;
}

.file-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.file-item {
    padding: 10px;
    background-color: #f1f1f1;
    cursor: pointer;
    border-radius: 5px;
    transition: background-color 0.2s;
}

.file-item:hover {
    background-color: #e0e0e0;
}

.file-popup {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.7);
    z-index: 1000;
    justify-content: center;
    align-items: center;
}

.file-popup-content {
    background-color: white;
    padding: 20px;
    border-radius: 10px;
    position: relative;
    width: 80%;
    max-width: 800px;
}

.file-popup-content .close {
    position: absolute;
    top: 10px;
    right: 20px;
    font-size: 24px;
    cursor: pointer;
}

.file-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 12px;
    background-color: #f1f1f1;
    border-radius: 5px;
    margin-bottom: 5px;
}
.upload-section {
    margin-bottom: 15px;
    display: flex;
    gap: 10px;
    align-items: center;
}
.upload-section input[type="text"],
.upload-section input[type="file"],
.upload-section select {
    padding: 5px;
}
.upload-section button {
    padding: 6px 12px;
    cursor: pointer;
}
.folder-list {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 20px;
}

.folder-box {
    padding: 10px 15px;
    background: #f4f4f4;
    border: 1px solid #ccc;
    border-radius: 8px;
    cursor: pointer;
    transition: 0.2s;
}
.folder-box:hover {
    background: #e0e0e0;
}

.folder-box {
    padding: 8px;
    background: #f2f2f2;
    margin: 5px 0;
    cursor: pointer;
    border-radius: 5px;
}

.file-item {
    margin-left: 20px;
    padding: 4px;
    color: #333;
}


</style>
<body>
    <div id="admin-menu"></div>
    <script src="../../js/admin-menu.js"></script>

    
    <div class="main">
    <p class="type">RECORDS / FILES</p>

        <!-- Add Folder Section -->
    <div class="upload-section">
        <input type="text" id="folderName" placeholder="Folder Name" />
        <button onclick="createFolder()">➕ Add Folder</button>
    </div>

    <!-- Upload File Section -->
    <div class="upload-section">
        <select id="folderSelect">
            <option value="">-- No Folder --</option>
        </select>
        <input type="file" id="fileInput" />
        <button onclick="uploadFile()">📁 Upload File</button>
    </div>

    <!-- Folder List Section -->
    <h3>📂 Folders</h3>
    <div class="folder-list" id="folderList"></div>

    <!-- Search & Refresh Section -->
    <div class="upload-section" style="margin-top: 10px; display: flex; gap: 5px;">
        <input type="text" id="searchInput" placeholder="Search files..." style="flex: 1;" />
        <button onclick="searchFiles()">🔍 Search</button>
        <button onclick="refreshFiles()">🔄 Refresh</button>
    </div>


    <!-- Show All Files Button -->
    <div style="margin-top: 10px;">
        <button onclick="loadFiles('')">🔙 Show All Files</button>
    </div>

    <!-- All Files Section -->
    <h3 id="allFilesTitle">📄 All Files</h3>
    <div class="file-list" id="fileListContainer">
        <div id="fileList"></div>
    </div>


    </div>


    <!-- File Popup Modal -->
    <div id="filePopup" class="file-popup">
        <div class="file-popup-content">
            <span class="close" onclick="closePopup()">&times;</span>
            <iframe id="popupViewer" width="100%" height="500px"></iframe>
        </div>
    </div>

</body>

<script>
  window.addEventListener('DOMContentLoaded', () => {
        loadFolders();
        loadFiles();
    });

function searchFiles() {
    const query = document.getElementById("searchInput").value.trim().toLowerCase();
    const files = document.querySelectorAll("#fileList .file-item"); // Adjust selector if needed

    files.forEach(file => {
        const fileName = file.textContent.toLowerCase();
        file.style.display = fileName.includes(query) ? "" : "none";
    });
}

function refreshFiles() {
    document.getElementById("searchInput").value = ""; // Clear search
    loadFiles(''); // Reload all files (uses your existing function)
}


function createFolder() {
    const folder = document.getElementById("folderName").value.trim();
    if (!folder) return Swal.fire("Please enter a folder name.");

    fetch('create-folder.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ folder })
    })
    .then(r => r.json())
    .then(res => {
        Swal.fire(res.message);
        if (res.success) {
            document.getElementById("folderName").value = "";
            loadFolders(); // Refresh dropdown
        }
    });
}

function uploadFile() {
    const fileInput = document.getElementById("fileInput");
    const folder = document.getElementById("folderSelect").value;

    if (!fileInput.files.length) {
        return Swal.fire("Please choose a file.");
    }

    const formData = new FormData();
    formData.append("file", fileInput.files[0]);
    formData.append("folder", folder); // will be empty string if no folder

    fetch('upload-file.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(res => {
        Swal.fire(res.message);
        if (res.success) {
            fileInput.value = "";
            loadFolders(); // optional, if folders might change
            loadFiles();   // update file list
        }
    })
    .catch(err => {
        console.error("Upload failed", err);
        Swal.fire("Upload failed. Check your connection or try again.");
    });
}


function loadFolders() {
    fetch('get-folder.php')
    .then(r => r.json())
    .then(folders => {
        const select = document.getElementById('folderSelect');
        const folderList = document.getElementById('folderList');
        const fileList = document.getElementById('fileList');

        // Clear previous content
        select.innerHTML = '<option value="">-- No Folder --</option>';
        folderList.innerHTML = '';
        fileList.innerHTML = '';

        folders.forEach(folder => {
            // Add folder to dropdown
            const opt = document.createElement('option');
            opt.value = folder.name;
            opt.textContent = folder.name;
            select.appendChild(opt);

            // Create folder box
            const folderBox = document.createElement('div');
            folderBox.className = 'folder-box';

            // Main folder label (clickable)
            const folderLabel = document.createElement('span');
            folderLabel.textContent = '📁 ' + folder.name;
            folderLabel.style.cursor = 'pointer';
            folderLabel.onclick = () => loadFiles(folder.name);

            // Rename button
            const renameBtn = document.createElement('span');
            renameBtn.innerHTML = '✏️';
            renameBtn.style.marginLeft = '10px';
            renameBtn.style.cursor = 'pointer';
            renameBtn.onclick = (e) => {
                e.stopPropagation();
                renameFolder(folder.name);
            };

            // Delete button
            const deleteBtn = document.createElement('span');
            deleteBtn.innerHTML = '🗑️';
            deleteBtn.style.marginLeft = '10px';
            deleteBtn.style.cursor = 'pointer';
            deleteBtn.style.color = 'red';
            deleteBtn.onclick = (e) => {
                e.stopPropagation();
                deleteFolder(folder.name);
            };

            folderBox.appendChild(folderLabel);
            folderBox.appendChild(renameBtn);
            folderBox.appendChild(deleteBtn);

            folderList.appendChild(folderBox);
        });
    });
}



function loadFiles(folderName = '') {
    const url = folderName 
        ? 'get-folder-file.php?folder=' + encodeURIComponent(folderName)
        : 'get-files.php';

    const allFilesTitle = document.getElementById('allFilesTitle');
    const fileListContainer = document.getElementById('fileListContainer');
    const fileList = document.getElementById('fileList');

    // Toggle visibility
    allFilesTitle.style.display = folderName ? 'none' : 'block';
    fileListContainer.style.display = 'block';

    fetch(url)
    .then(r => r.json())
    .then(files => {
        fileList.innerHTML = folderName 
            ? `<h3>📂 ${folderName}</h3>`
            : '';

        if (files.length === 0) {
            fileList.innerHTML += '<p>No files found.</p>';
            return;
        }

        files.forEach(file => {
            const fileRow = document.createElement('div');
            fileRow.className = 'file-item';

            const nameSpan = document.createElement('span');
            nameSpan.textContent = file.filename || file;
            nameSpan.style.cursor = 'pointer';
            nameSpan.onclick = () => openPopup(file.filepath || 'uploads/' + file);

            // Check for file ID (structured object)
            if (file.id && file.filename && file.filepath) {
                const renameBtn = document.createElement('span');
                renameBtn.innerHTML = '✏️';
                renameBtn.style.cursor = 'pointer';
                renameBtn.style.marginLeft = '10px';
                renameBtn.onclick = (e) => {
                    e.stopPropagation();
                    renameFile(file.id, file.filename);
                };

                const deleteBtn = document.createElement('span');
                deleteBtn.innerHTML = '🗑️';
                deleteBtn.style.color = 'red';
                deleteBtn.style.cursor = 'pointer';
                deleteBtn.style.marginLeft = '10px';
                deleteBtn.onclick = (e) => {
                    e.stopPropagation();
                    deleteFile(file.id, file.filepath);
                };

                fileRow.appendChild(nameSpan);
                fileRow.appendChild(renameBtn);
                fileRow.appendChild(deleteBtn);
            } else {
                // Fallback for raw file strings (if any)
                fileRow.appendChild(nameSpan);
            }

            fileList.appendChild(fileRow);
        });
    });
}



function renameFile(id, currentName) {
    Swal.fire({
        title: 'Rename File',
        input: 'text',
        inputValue: currentName,
        showCancelButton: true,
        confirmButtonText: 'Rename',
        preConfirm: (newName) => {
            if (!newName || newName.trim() === '') {
                Swal.showValidationMessage('Filename cannot be empty');
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const newName = result.value;
            fetch('rename-file.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id, newName })
            })
            .then(r => r.json())
            .then(res => {
                Swal.fire(res.message);
                if (res.success) loadFiles();
            });
        }
    });
}

function deleteFile(id, filepath) {
    Swal.fire({
        title: "Are you sure?",
        text: "This will delete the file permanently.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('delete-file.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({ id: id, filepath: filepath })
            })
            .then(r => r.json())
            .then(res => {
                Swal.fire(res.message);
                if (res.success) loadFiles();
            });
        }
    });
}

function renameFolder(currentName) {
    Swal.fire({
        title: 'Rename Folder',
        input: 'text',
        inputValue: currentName,
        showCancelButton: true,
        confirmButtonText: 'Rename',
        preConfirm: (newName) => {
            if (!newName.trim()) {
                Swal.showValidationMessage('Folder name cannot be empty');
            }
        }
    }).then(result => {
        if (result.isConfirmed) {
            fetch('rename-folder.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ oldName: currentName, newName: result.value.trim() })
            })
            .then(r => r.json())
            .then(res => {
                Swal.fire(res.message);
                if (res.success) loadFolders();
            });
        }
    });
}

function deleteFolder(folderName) {
    Swal.fire({
        title: 'Delete Folder?',
        text: `Are you sure you want to delete "${folderName}" and all its files?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!'
    }).then(result => {
        if (result.isConfirmed) {
            fetch('delete-folder.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ folder: folderName })
            })
            .then(r => r.json())
            .then(res => {
                Swal.fire(res.message);
                if (res.success) loadFolders();
            });
        }
    });
}


function openPopup(url) {
    document.getElementById('popupViewer').src = url;
    document.getElementById('filePopup').style.display = 'flex';
}

function closePopup() {
    document.getElementById('popupViewer').src = '';
    document.getElementById('filePopup').style.display = 'none';
}
</script>
