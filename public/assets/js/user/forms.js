// MOTOTPOOL FORM JAVASCRIPT
function addPassengerField() {
  const container = document.getElementById('passenger-fields');
  const row = document.createElement('div');
  row.className = 'flex gap-2 w-full passenger-row mt-2';
  row.innerHTML = `
      <div class="flex flex-col w-1/2">
        <input type="text" required class="input-field" name="first_name[]">
      </div>
      <div class="flex flex-col w-1/2">
        <input type="text" required class="input-field" name="last_name[]">
      </div>
      <button type="button" onclick="addPassengerField()">
        <p class="text-xl">+</p>
      </button>
  `;
  container.appendChild(row);
}

// ✅ Attach to the form with the right ID
document.getElementById("vehicle-form").addEventListener("submit", function(e) {
  e.preventDefault();

  const dateTravel = new Date(document.querySelector("[name='date_of_travel']").value);
  const dateReturn = new Date(document.querySelector("[name='date_of_return']").value);
  const timeDeparture = document.querySelector("[name='time_of_departure']").value;
  const timeReturn = document.querySelector("[name='time_of_return']").value;

  // Validation
  if (dateReturn < dateTravel) {
    Swal.fire("Invalid Date", "Return date cannot be earlier than travel date.", "error");
    return;
  }

  if (dateTravel.getTime() === dateReturn.getTime() && timeReturn <= timeDeparture) {
    Swal.fire("Invalid Time", "Return time must be later than departure time.", "error");
    return;
  }

  // SweetAlert confirmation
  Swal.fire({
    title: "Confirm Submission",
    text: "Please confirm that all the details entered are correct.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, submit",
    cancelButtonText: "Review again"
  }).then((result) => {
    if (result.isConfirmed) {
      e.target.submit();
    }
  });
});


// GSU FORM JAVASCRIPT
document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector("form[name='gsu-request']");

  form.addEventListener("submit", function (e) {
    e.preventDefault(); // stop normal submit

    Swal.fire({
      title: "Submit Request?",
      text: "Please confirm before submitting your repair request.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#d33",
      cancelButtonColor: "#6c757d",
      confirmButtonText: "Yes, submit",
    }).then((result) => {
      if (result.isConfirmed) {
        form.submit(); // send to RequestController.php
      }
    });
  });
});