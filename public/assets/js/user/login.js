document.addEventListener('keydown', function(e) {
  if (e.ctrlKey && e.altKey && e.key === 'a') {
    window.location.href = '../superadmin/admin_login.php';
  }
});

// Pass PHP variable to JS
let loginError = json_encode($login_error);
if (loginError) {
    showErrorAlert(loginError);
}