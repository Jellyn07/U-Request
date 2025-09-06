function goToOtp() {
document.getElementById("step-email").classList.add("hidden");
document.getElementById("step-otp").classList.remove("hidden");
}

function goToReset() {
// In real case, verify OTP with backend first before showing reset
document.getElementById("step-otp").classList.add("hidden");
document.getElementById("step-reset").classList.remove("hidden");
}