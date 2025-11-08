document.addEventListener('DOMContentLoaded', function() {
    const advancedSearchBtn = document.getElementById('advancedSearchBtn');
    const advancedSearchContainer = document.getElementById('advancedSearchContainer');

    // Tampilkan pop-up advanced search saat tombol diklik
    advancedSearchBtn.addEventListener('click', function() {
        advancedSearchContainer.style.display = 'block';
    });
});
document.addEventListener('DOMContentLoaded', function() {
    const closeButton = document.getElementById('closeAdvancedSearch');
    const popupContainer = document.getElementById('advancedSearchContainer');

    if (closeButton && popupContainer) {
        closeButton.addEventListener('click', function() {
            popupContainer.style.display = 'none';
        });
    }
});