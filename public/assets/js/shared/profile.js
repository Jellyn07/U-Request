document.getElementById("profile_picture_input").addEventListener("change", function(event) {
    const file = event.target.files[0];
    if (!file) return;

    const previewImg = document.getElementById("profile-preview");

    // Show preview immediately
    const reader = new FileReader();
    reader.onload = e => previewImg.src = e.target.result;
    reader.readAsDataURL(file);

    // Confirmation dialog
    Swal.fire({
        title: "Change Profile Picture?",
        text: "Do you want to save this new profile picture?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, save it",
        cancelButtonText: "Cancel"
    }).then((result) => {
        if (!result.isConfirmed) {
            event.target.value = "";
            return;
        }

        const form = document.getElementById("profilePicForm");
        const formData = new FormData(form);

        // Send POST request
        fetch(form.action, {
            method: "POST",
            body: formData,
            credentials: "same-origin"
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    toast: true,
                    position: "top-end",
                    icon: "success",
                    title: "Profile Picture Updated Successfully!",
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true
                }).then(() => {
                    // Refresh page after success
                    location.reload();
                });
            } else {
                // If upload fails, just refresh silently
                location.reload();
            }
        })
        .catch(() => {
            // On network error, refresh silently
            location.reload();
        });
    });
});