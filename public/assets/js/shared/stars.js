// --- JS Star Rendering ---
function renderStars(rating) {
    let stars = '';
    for (let i = 1; i <= 5; i++) {
    if (rating >= i) {
        stars += `<svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.431L24 9.753l-6 5.847L19.335 24 12 19.897 4.665 24 6 15.6 0 9.753l8.332-1.735z"/></svg>`;
    } else if (rating >= i - 0.5) {
        stars += `<svg class="w-4 h-4 text-yellow-400" viewBox="0 0 24 24"><defs><linearGradient id="half-${i}"><stop offset="50%" stop-color="currentColor"/><stop offset="50%" stop-color="lightgray"/></linearGradient></defs><path fill="url(#half-${i})" d="M12 .587l3.668 7.431L24 9.753l-6 5.847L19.335 24 12 19.897 4.665 24 6 15.6 0 9.753l8.332-1.735z"/></svg>`;
    } else {
        stars += `<svg class="w-4 h-4 text-gray-300 fill-current" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.431L24 9.753l-6 5.847L19.335 24 12 19.897 4.665 24 6 15.6 0 9.753l8.332-1.735z"/></svg>`;
    }
    }
    return stars;
}
document.getElementById('averageStars').innerHTML = renderStars(4.5);
