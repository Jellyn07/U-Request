// document.getElementById('search').addEventListener('input', function() {
//     const filter = this.value.toLowerCase();
//     const rows = document.querySelectorAll('#table tr');
//     rows.forEach(row => {
//     const one = row.children[0].textContent.toLowerCase();
//     const two = row.children[1].textContent.toLowerCase();
//     const three = row.children[2].textContent.toLowerCase();
//     const four = row.children[3].textContent.toLowerCase();
//     const five = row.children[4].textContent.toLowerCase();
//     row.style.display = (one.includes(filter) || two.includes(filter) || three.includes(filter) || four.includes(filter) || five.includes(filter)) ? '' : 'none';
//     });
// });

document.getElementById('search').addEventListener('input', function() {
    const filter = this.value.toLowerCase();
    const rows = document.querySelectorAll('#table tr');
    rows.forEach(row => {
        const cells = Array.from(row.children);
        const match = cells.some(cell => cell.textContent.toLowerCase().includes(filter));
        row.style.display = match ? '' : 'none';
    });
});
