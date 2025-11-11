<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: /app/modules/shared/views/admin_login.php");
    exit;
}
require_once __DIR__ . '/../../../config/constants.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>U-Request | Backup & Restore</title>
  <link rel="stylesheet" href="/public/assets/css/output.css" />
  <link rel="icon" href="/public/assets/img/upper_logo.png"/>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gray-200">
  <!-- Superadmin Menu -->
  <?php include COMPONENTS_PATH . '/superadmin_menu.php'; ?>

  <main class="ml-16 md:ml-64 flex flex-col min-h-screen transition-all duration-300">
    <div class="p-6">
      <h1 class="text-2xl font-bold mb-6">Backup & Restore Database</h1>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- 🔹 BACKUP SECTION -->
        <div class="bg-white p-6 rounded-lg shadow">
          <h2 class="text-lg font-semibold mb-4">Backup Database</h2>
          <p class="text-sm text-gray-600 mb-6">
            Create a secure backup of the U-Request database. You can also enable automatic backup every 6 hours.
          </p>

          <form method="POST" action="../../../controllers/BackupController.php">
            <div class="space-y-3">
              <button type="submit" name="backup_now" class="btn btn-primary w-full py-2">
                <img src="/public/assets/img/backup.png" alt="Backup" class="size-4 inline mr-2">
                Backup Now
              </button>

              <button type="button" id="enableAutoBackup" class="btn btn-secondary w-full py-2">
                <!-- <img src="/public/assets/img/refresh.png" alt="Auto" class="size-4 inline mr-2"> -->
                Enable Auto Backup (6 hrs)
              </button>
            </div>
          </form>

          <!-- Available backups -->
          <div class="mt-6">
            <h3 class="text-sm font-semibold text-gray-700 mb-2">Recent Backups</h3>
            <ul class="max-h-40 overflow-y-auto text-sm text-gray-600 border rounded p-2">
            <?php
              $backupDir = __DIR__ . '/../../../../backups/';
              $webPath = '/../../backups/'; // URL for browser
              if (is_dir($backupDir)) {
                  $files = array_diff(scandir($backupDir), ['.', '..']);
                  if (count($files) > 0) {
                      foreach (array_reverse($files) as $file) {
                          echo "<li class='flex justify-between border-b py-1'>
                                  <span>" . htmlspecialchars($file) . "</span>
                                  <a href='/app/handlers/download_backup.php?file=" . urlencode($file) . "' download class='text-primary hover:underline'>Download</a>
                                </li>";
                                        // <img src='/public/assets/img/export.png' alt='User' class='size-4 my-0.5'>
                      }
                  } else {
                      echo "<li>No backups available yet.</li>";
                  }
              } else {
                  echo "<li>Backup folder not found.</li>";
              }
            ?>
            </ul>
          </div>
        </div>

        <!-- 🔹 RESTORE SECTION -->
        <div class="bg-white p-6 rounded-lg shadow">
          <h2 class="text-lg font-semibold mb-4">Restore Database</h2>
          <p class="text-sm text-gray-600 mb-6">
            Restore the database from a .sql backup file. Make sure you’re restoring the correct version.
          </p>

          <form method="POST" action="../../../controllers/BackupController.php" enctype="multipart/form-data">
            <div class="space-y-4">
              <input type="file" name="restore_file" accept=".sql" class="w-full input-field" required>

              <button type="submit" name="restore_now" class="btn btn-primary w-full py-2">
                <!-- <img src="/public/assets/img/import.png" alt="Restore" class="size-4 inline mr-2"> -->
                Restore Database
              </button>
            </div>
          </form>

          <p class="text-xs text-red-500 mt-3">
            ⚠️ Warning: Restoring will overwrite the current database.
          </p>
        </div>

      </div>
    </div>
  </main>

  <script src="/public/assets/js/backup-scheduler.js"></script>

<script>
  // Session-based alerts for manual backup/restore
  <?php if (isset($_SESSION['backup_status'])): ?>
    document.addEventListener('DOMContentLoaded', () => {
      const status = "<?php echo $_SESSION['backup_status']; ?>";
      if (status === 'backup_success') {
        Swal.fire('Backup Successful', 'Database has been backed up successfully. 📧 Email notification sent.', 'success');
      } else if (status === 'restore_success') {
        Swal.fire('Restore Successful', 'Database has been restored successfully.', 'success');
      } else {
        Swal.fire('Error', 'An error occurred while processing the backup/restore.', 'error');
      }
    });
  <?php unset($_SESSION['backup_status']); endif; ?>
</script>

</body>
</html>
