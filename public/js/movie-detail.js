let btnFavorite = null;
let favoriteText = null;
let favoriteManager = null;

document.addEventListener('DOMContentLoaded', function() {
    btnFavorite = document.getElementById('btnFavorite');
    favoriteText = document.getElementById('favoriteText');

    favoriteManager = new FavoriteManager(window.api);

    if (btnFavorite) {
        btnFavorite.addEventListener('click', toggleFavorite);
    }
});

async function toggleFavorite() {
    if (!btnFavorite || !favoriteManager) return;

    const imdbId = btnFavorite.dataset.imdbId;
    const addText = btnFavorite.dataset.addText || 'Add to Favorites';
    const removeText = btnFavorite.dataset.removeText || 'Remove from Favorites';

    try {
        await favoriteManager.handleToggle(btnFavorite, imdbId, addText, removeText);
        window.isFavorite = !window.isFavorite;
    } catch (error) {
        UIManager.showError(error.message || 'Failed to update favorites');
    }
}
