class FavoriteManager {
    constructor(api) {
        this.api = api;
    }

    async toggleFavorite(imdbId, favoriteId) {
        if (favoriteId) {
            return this.api.delete(`/api/favorites/${favoriteId}`);
        }
        return this.api.post('/api/favorites/add', { imdb_id: imdbId });
    }

    async checkFavoriteStatus(imdbId) {
        return this.api.get(`/api/favorites/check?imdb_id=${imdbId}`);
    }

    updateButtonState(btn, isFavorite, favoriteId) {
        if (!btn) return;

        if (isFavorite) {
            btn.classList.add('bg-yellow-400');
            btn.classList.add('text-black');
            btn.classList.remove('bg-primary', 'hover:bg-primary-dark', 'text-white');
            if (favoriteId) {
                btn.dataset.favoriteId = favoriteId;
            }
        } else {
            btn.classList.remove('bg-yellow-400', 'text-black');
            btn.classList.add('bg-primary', 'hover:bg-primary-dark', 'text-white');
            btn.removeAttribute('data-favorite-id');
        }
    }

    updateButtonText(btn, addText, removeText) {
        if (!btn) return;

        const textElement = btn.querySelector('span');
        if (textElement) {
            const isFavorite = btn.classList.contains('bg-yellow-400');
            textElement.textContent = isFavorite ? removeText : addText;
        }
    }

    async handleToggle(btn, imdbId, addText, removeText) {
        if (!btn) return;

        const isFavorite = btn.classList.contains('bg-yellow-400');
        const favoriteId = btn.dataset.favoriteId;

        btn.disabled = true;
        btn.style.opacity = '0.5';

        try {
            if (isFavorite) {
                await this.toggleFavorite(imdbId, favoriteId);
                this.updateButtonState(btn, false);
                this.updateButtonText(btn, addText, removeText);
            } else {
                const response = await this.toggleFavorite(imdbId);
                this.updateButtonState(btn, true, response.favorite ? response.favorite.id : null);
                this.updateButtonText(btn, addText, removeText);
            }
        } catch (error) {
            throw error;
        } finally {
            btn.disabled = false;
            btn.style.opacity = '1';
        }
    }

    async handleRemoveFromFavorites(favoriteId, cardId) {
        if (!favoriteId) return;

        try {
            await this.toggleFavorite(null, favoriteId);
            const card = document.getElementById(`movie-card-${favoriteId}`);
            if (card) {
                card.style.opacity = '0';
                card.style.transform = 'scale(0.8)';
                setTimeout(() => {
                    card.remove();
                    const remainingCards = document.querySelectorAll('.grid > div');
                    if (remainingCards.length === 0) {
                        location.reload();
                    }
                }, 300);
            }
        } catch (error) {
            throw error;
        }
    }
}
