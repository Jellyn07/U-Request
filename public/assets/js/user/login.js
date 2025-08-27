document.addEventListener('keydown', function(e) {
  if (e.ctrlKey && e.altKey && e.key === 'a') {
    window.location.href = '../superadmin/admin_login.php';
  }
});
