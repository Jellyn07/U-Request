
function addPassengerField() {
const container = document.getElementById('passenger-fields');
const row = document.createElement('div');
row.className = 'flex gap-2 w-full passenger-row mt-2';
row.innerHTML = `
    <div class="flex flex-col w-1/2">
    <input type="text" class="px-3 py-2 rounded-lg border border-gray-700  text-text focus:border-secondary outline-none transition" name="first_name[]">
    </div>
    <div class="flex flex-col w-1/2">
    <input type="text" class="px-3 py-2 rounded-lg border border-gray-700  text-text focus:border-secondary outline-none transition" name="last_name[]">
    </div>
    <button type="button" id="add-passenger" onclick="addPassengerField()">
        <p class="text-xl">+</p>
    </button>
`;
container.appendChild(row);
}

document.getElementById("dateNoticed").value = new Date().toISOString().split('T')[0];