let favoriteManager = null;

document.addEventListener('DOMContentLoaded', function() {
    favoriteManager = new FavoriteManager(window.api);
});

async function toggleFavorite(favoriteId, imdbId, btn) {
    if (!favoriteManager) return;

    if (!favoriteId) {
        UIManager.showError('Favorite ID is missing. Please refresh the page.');
        return;
    }

    try {
        await favoriteManager.handleRemoveFromFavorites(favoriteId);
    } catch (error) {
        UIManager.showError(error.message || 'Failed to remove from favorites');
    }
}
