// 🕒 Auto backup every 6 hours (21,600,000 ms)
const SIX_HOURS = 6 * 60 * 60 * 1000;

function runBackup() {
  fetch('/app/controllers/BackupController.php?action=backup')
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        console.log('✅ Auto Backup Successful:', data.file);
      } else {
        console.error('❌ Auto Backup Failed:', data.message);
      }
    })
    .catch(err => console.error('⚠️ Backup request error:', err));
}

// 🔁 Schedule backups every 6 hours (do NOT run immediately on page load)
setInterval(runBackup, SIX_HOURS);

// 🧩 Optional: manual trigger when clicking button
document.getElementById('enableAutoBackup')?.addEventListener('click', () => {
  Swal.fire({
    title: 'Auto Backup Enabled',
    text: 'The system will automatically back up every 6 hours.',
    icon: 'success'
  });
});
