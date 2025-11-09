// Run backup every 6 hours
setInterval(() => {
    fetch('/app/controllers/BackupController.php?action=backup')
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                console.log('✅ Database backed up:', data.file);
            } else {
                console.error('❌ Backup failed:', data.message);
            }
        })
        .catch(err => console.error('⚠️ Backup request error:', err));
}, 6 * 60 * 60 * 1000); // 6 hours (21,600,000 ms)
//6 * 60 * 60 * 1000; // 6 hours (21,600,000 ms)             1 * 60 * 10000); // 1 minute (60,000 ms)
// Optional: Run once immediately when page loads
fetch('/app/controllers/BackupController.php?action=backup')
    .then(res => res.json())
    .then(data => console.log('Initial backup:', data))
    .catch(err => console.error('Initial backup error:', err));
