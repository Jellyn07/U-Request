function addPassengerField() {
const container = document.getElementById('passenger-fields');
const row = document.createElement('div');
row.className = 'flex gap-2 w-full passenger-row mt-2';
row.innerHTML = `
    <div class="flex flex-col w-1/2">
    <input type="text" class="px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-background text-text focus:ring-2 focus:ring-primary focus:border-primary outline-none transition" name="first_name[]">
    </div>
    <div class="flex flex-col w-1/2">
    <input type="text" class="px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-background text-text focus:ring-2 focus:ring-primary focus:border-primary outline-none transition" name="last_name[]">
    </div>
`;
container.appendChild(row);
}

function addPassengerField() {
const container = document.getElementById('passenger-fields');
const row = document.createElement('div');
row.className = 'flex gap-2 w-full passenger-row mt-2';
row.innerHTML = `
    <div class="flex flex-col w-1/2">
    <input type="text" class="px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-background text-text focus:ring-2 focus:ring-primary focus:border-primary outline-none transition" name="first_name[]">
    </div>
    <div class="flex flex-col w-1/2">
    <input type="text" class="px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-background text-text focus:ring-2 focus:ring-primary focus:border-primary outline-none transition" name="last_name[]">
    </div>
    <button type="button" id="add-passenger" onclick="addPassengerField()">
        <img src="/U--Request/public/assets/img/plus.png" alt="Add Passenger" class="w-6 h-6">
    </button>
`;
container.appendChild(row);
}