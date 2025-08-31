// alerts.js

function showErrorAlert(message) {
    Swal.fire({
        icon: 'error',
        title: 'Login Failed',
        text: message,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Try Again'
    });
}

function showErrorA(msg) {
    Swal.fire({
        icon: 'error',
        title: 'Signup Failed',
        text: msg
    });
}
function showSuccessAlert(msg) {
    Swal.fire({
        icon: 'success',
        title: 'Signup Successful',
        text: msg
    });
}