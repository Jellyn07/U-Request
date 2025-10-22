function updatePersonnelOptions() {
  const selects = document.querySelectorAll('.staff-select');
  const selectedValues = Array.from(selects).map(s => s.value).filter(v => v !== '');

  selects.forEach(select => {
    Array.from(select.options).forEach(option => {
      if (option.value === '') return;
      option.hidden = selectedValues.includes(option.value) && option.value !== select.value;
    });
  });
}

function updateMaterialOptions() {
  const selects = document.querySelectorAll('.material-select');
  const selectedValues = Array.from(selects).map(s => s.value).filter(v => v !== '');

  selects.forEach(select => {
    Array.from(select.options).forEach(option => {
      if (option.value === '') return;
      option.hidden = selectedValues.includes(option.value) && option.value !== select.value;
    });
  });
}

 document.addEventListener('alpine:init', () => {
    Alpine.data('materialManager', () => ({
      materials: [{ material_code: '', quantity: 1 }],
      selectedMaterialCodes: [],

      updateMaterialOptions() {
        // Update the list of selected material codes
        this.selectedMaterialCodes = this.materials
          .map(m => m.material_code)
          .filter(code => code !== '');
      }
    }));
  });

  document.getElementById('assignmentForm').addEventListener('submit', function(e) {
  e.preventDefault(); // stop immediate submit
  
  Swal.fire({
    title: 'Save Changes?',
    text: "Do you really want to save your changes?",
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#aaa',
    confirmButtonText: 'Yes, save it!',
    cancelButtonText: 'Cancel'
  }).then((result) => {
    if (result.isConfirmed) {
      e.target.submit(); // proceed with form submission
    }
  });
});