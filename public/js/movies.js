let currentPage = 1;
let totalResults = 0;
let currentQuery = '';
let currentType = '';
let isLoading = false;

let searchInput = null;
let searchType = null;
let searchBtn = null;
let searchTitle = null;
let searchSeason = null;
let searchEpisode = null;
let episodeInputs = null;
let moviesGrid = null;
let emptyState = null;
let loadMoreContainer = null;
let loadMoreBtn = null;
let loading = null;
let errorMessage = null;
let errorMessageText = null;
let movieCardTemplate = null;
let episodeCardTemplate = null;

let favoriteManager = null;

document.addEventListener('DOMContentLoaded', function() {
    searchInput = document.getElementById('searchInput');
    searchType = document.getElementById('searchType');
    searchTitle = document.getElementById('searchTitle');
    searchSeason = document.getElementById('searchSeason');
    searchEpisode = document.getElementById('searchEpisode');
    searchBtn = document.getElementById('searchBtn');
    moviesGrid = document.getElementById('moviesGrid');
    emptyState = document.getElementById('emptyState');
    loadMoreContainer = document.getElementById('loadMoreContainer');
    loadMoreBtn = document.getElementById('loadMoreBtn');
    loading = document.getElementById('loading');
    errorMessage = document.getElementById('errorMessage');
    errorMessageText = document.getElementById('errorMessageText');
    episodeInputs = document.getElementById('episodeInputs');
    movieCardTemplate = document.getElementById('movieCardTemplate');
    episodeCardTemplate = document.getElementById('episodeCardTemplate');

    favoriteManager = new FavoriteManager(window.api);

    if (searchBtn) {
        searchBtn.addEventListener('click', performSearch);
    }
    if (searchInput) {
        searchInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') performSearch();
        });
    }
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', loadMore);
    }
});

function toggleEpisodeInputs() {
    if (!searchType || !episodeInputs) return;

    const type = searchType.value;
    if (type === 'episode') {
        episodeInputs.classList.remove('hidden');
        searchInput.classList.add('hidden');
    } else {
        episodeInputs.classList.add('hidden');
        searchInput.classList.remove('hidden');
    }
}

function performSearch() {
    if (!searchInput || !searchType || !moviesGrid || !loadMoreContainer || !errorMessage || !emptyState || !episodeInputs) return;

    const type = searchType.value;
    currentType = type;
    currentPage = 1;
    moviesGrid.innerHTML = '';
    loadMoreContainer.classList.add('hidden');
    emptyState.classList.add('hidden');
    errorMessage.classList.add('hidden');

    if (type === 'episode') {
        const title = searchTitle.value.trim();
        const season = searchSeason.value.trim();
        const episode = searchEpisode.value.trim();

        if (!title || !season || !episode) {
            UIManager.showError('Please provide series title, season, and episode number');
            return;
        }

        loadEpisode(title, season, episode);
    } else {
        const query = searchInput.value.trim();
        currentQuery = query;

        if (!currentQuery) {
            UIManager.showError('Please enter a search term');
            return;
        }

        loadMovies();
    }
}

function loadMovies() {
    if (isLoading || !loading || !loadMoreContainer) return;
    isLoading = true;
    loading.classList.remove('hidden');

    const url = `/api/movies/search?s=${encodeURIComponent(currentQuery)}&page=${currentPage}`;
    const typeParam = currentType ? `&type=${currentType}` : '';

    fetchApi(url + typeParam)
        .then(data => {
            loading.classList.add('hidden');
            isLoading = false;

            if (!data.success) {
                UIManager.showError(data.message);
                return;
            }

            totalResults = parseInt(data.totalResults);
            displayMovies(data.movies);

            const totalPages = Math.ceil(totalResults / 10);
            if (currentPage < totalPages) {
                loadMoreContainer.classList.remove('hidden');
            } else {
                loadMoreContainer.classList.add('hidden');
            }
        })
        .catch(error => {
            loading.classList.add('hidden');
            isLoading = false;
            UIManager.showError(error.message || 'Failed to load movies. Please try again.');
        });
}

function loadMore() {
    if (isLoading) return;
    currentPage++;
    loadMovies();
}

function loadEpisode(title, season, episode) {
    if (isLoading || !loading) return;
    isLoading = true;
    loading.classList.remove('hidden');

    const url = `/api/movies/episode?t=${encodeURIComponent(title)}&Season=${season}&Episode=${episode}`;

    console.log('Loading episode:', { url, title, season, episode });

    fetchApi(url)
        .then(data => {
            loading.classList.add('hidden');
            isLoading = false;

            console.log('Episode API Response:', data);

            if (!data.success) {
                UIManager.showError(data.message);
                return;
            }

            console.log('Episode data:', data.episode);
            displayEpisode(data.episode);
        })
        .catch(error => {
            loading.classList.add('hidden');
            isLoading = false;
            console.error('Episode API Error:', error);
            UIManager.showError(error.message || 'Failed to load episode. Please try again.');
        });
}

function displayEpisode(episode) {
    if (!moviesGrid) return;

    if (!episode) {
        UIManager.showError('Episode not found');
        return;
    }

    const card = createEpisodeCard(episode);
    if (card) {
        moviesGrid.appendChild(card);
    }

    loadMoreContainer.classList.add('hidden');
    emptyState.classList.add('hidden');
}

function createEpisodeCard(episode) {
    if (!episodeCardTemplate) return null;

    const template = episodeCardTemplate.content.cloneNode(true);
    const card = template.querySelector('.group');

    if (!card) return null;

    card.onclick = () => window.location.href = `/movies/${episode.imdbID}`;

    const poster = template.querySelector('.episode-poster');
    const placeholder = template.querySelector('.placeholder');
    const isPoster = episode.Poster && episode.Poster !== 'N/A';

    if (poster) {
        if (isPoster) {
            poster.src = episode.Poster;
            poster.alt = episode.Title;
            poster.onload = () => poster.classList.add('opacity-100');
            poster.onerror = () => {
                if (placeholder) placeholder.classList.remove('hidden');
            };
        } else {
            if (placeholder) placeholder.classList.remove('hidden');
        }
    }

    const episodeType = template.querySelector('.episode-type');
    const episodeTitle = template.querySelector('.episode-title');
    const episodeSeason = template.querySelector('.episode-season');
    const episodeNumber = template.querySelector('.episode-number');
    const episodeYear = template.querySelector('.episode-year');

    if (episodeType) episodeType.textContent = episode.Type;
    if (episodeTitle) episodeTitle.textContent = episode.Title;
    if (episodeSeason) episodeSeason.textContent = episode.Season;
    if (episodeNumber) episodeNumber.textContent = episode.Episode;
    if (episodeYear) episodeYear.textContent = episode.Year;

    const btnFavorite = template.querySelector('.btn-favorite');
    if (btnFavorite) {
        btnFavorite.onclick = (e) => {
            e.stopPropagation();
            favoriteManager.handleToggle(btnFavorite, episode.imdbID, 'Add to Favorites', 'Remove from Favorites')
                .catch(error => UIManager.showError(error.message || 'Failed to update favorites'));
        };
        checkFavoriteStatus(episode.imdbID, btnFavorite);
    }

    return card;
}

function displayMovies(movies) {
    if (!moviesGrid) return;

    if (!movies || !Array.isArray(movies)) {
        UIManager.showError('No movies found');
        return;
    }

    movies.forEach(movie => {
        const card = createMovieCard(movie);
        if (card) {
            moviesGrid.appendChild(card);
        }
    });

    observeImages();
}

function createMovieCard(movie) {
    if (!movieCardTemplate) return null;

    const template = movieCardTemplate.content.cloneNode(true);
    const card = template.querySelector('.group');

    if (!card) return null;

    card.onclick = () => window.location.href = `/movies/${movie.imdbID}`;

    const poster = template.querySelector('.movie-poster');
    const isPoster = movie.Poster && movie.Poster !== 'N/A';

    if (poster) {
        if (isPoster) {
            poster.dataset.src = movie.Poster;
            poster.alt = movie.Title;
        } else {
            const placeholder = poster.querySelector('.placeholder');
            if (placeholder) {
                placeholder.classList.remove('hidden');
            }
        }
    }

    const movieType = template.querySelector('.movie-type');
    const movieTitle = template.querySelector('.movie-title');
    const movieYear = template.querySelector('.movie-year');

    if (movieType) movieType.textContent = movie.Type;
    if (movieTitle) movieTitle.textContent = movie.Title;
    if (movieYear) movieYear.textContent = movie.Year;

    const btnFavorite = template.querySelector('.btn-favorite');
    if (btnFavorite) {
        btnFavorite.onclick = (e) => {
            e.stopPropagation();
            favoriteManager.handleToggle(btnFavorite, movie.imdbID, 'Add to Favorites', 'Remove from Favorites')
                .catch(error => UIManager.showError(error.message || 'Failed to update favorites'));
        };
        checkFavoriteStatus(movie.imdbID, btnFavorite);
    }

    return card;
}

function checkFavoriteStatus(imdbId, btn) {
    if (!favoriteManager) return;

    fetchApi(`/api/favorites/check?imdb_id=${imdbId}`)
        .then(data => {
            if (data.is_favorite && btn) {
                favoriteManager.updateButtonState(btn, true, data.favorite_id);
            }
        })
        .catch(error => {
            console.error('Error checking favorite status:', error);
        });
}

function observeImages() {
    const images = document.querySelectorAll('.movie-poster[data-src]');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.onload = () => {
                    img.classList.add('opacity-100');
                    const parent = img.parentElement;
                    const placeholder = parent ? parent.querySelector('.placeholder') : null;
                    if (placeholder) {
                        placeholder.classList.add('hidden');
                    }
                };
                img.onerror = () => {
                    const parent = img.parentElement;
                    const placeholder = parent ? parent.querySelector('.placeholder') : null;
                    if (placeholder) {
                        placeholder.classList.remove('hidden');
                    }
                };
                observer.unobserve(img);
            }
        });
    }, { rootMargin: '50px' });

    images.forEach(img => observer.observe(img));
}

function showError(message) {
    if (!errorMessage || !errorMessageText) return;
    errorMessageText.textContent = message;
    errorMessage.classList.remove('hidden');
    setTimeout(() => {
        errorMessage.classList.add('hidden');
    }, 5000);
}
