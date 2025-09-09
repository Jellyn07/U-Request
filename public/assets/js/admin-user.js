document.addEventListener("keydown", function(event) {
    if (event.ctrlKey && event.altKey) {
        const key = event.key.toLowerCase();

        if (key === 'a') {
            window.location.href = ADMIN_LOGIN;
        } else if (key === 'u') {
            window.location.href = USER_LOGIN;
        }
    }
});
