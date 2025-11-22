function toggleAdminMenuAccess(event, staffId) {
    const checked = event.target.checked;

    Swal.fire({
        title: 'Are you sure?',
        text: checked 
            ? 'Enable Admin Management for this account?' 
            : 'Disable Admin Management for this account?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'Cancel'
    }).then(result => {
        if (!result.isConfirmed) {
            event.target.checked = !checked;
            return;
        }

        fetch("/app/controllers/AdminController.php?action=toggleAdminMenu", {
            method: "POST",
            body: new URLSearchParams({
                staff_id: staffId,
                enabled: checked ? 1 : 0
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire('Success!', `Admin Management ${checked ? 'enabled' : 'disabled'} for this account.`, 'success');
            } else {
                Swal.fire('Error!', data.message || 'Failed to update menu access.', 'error');
                event.target.checked = !checked;
            }
        })
        .catch(() => {
            Swal.fire('Error!', 'Network error. Try again.', 'error');
            event.target.checked = !checked;
        });
    });
}

function updateStatusText(checkbox) {
    const staffId = checkbox.id.split('-')[1];
    const statusText = document.getElementById(`status-text-${staffId}`);
    if (checkbox.checked) {
        statusText.textContent = "Enabled";
        statusText.style.display = "block";
    } else {
        statusText.textContent = "Disabled";
        statusText.style.display = "block"; // or "none" if you want it hidden
    }
}
