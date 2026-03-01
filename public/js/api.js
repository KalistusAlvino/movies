function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]').content;
}

function fetchApi(url, options = {}) {
    if (!options.headers) {
        options.headers = {};
    }
    options.headers['X-Requested-With'] = 'XMLHttpRequest';
    options.headers['Accept'] = 'application/json';

    if (options.method && (options.method === 'DELETE' || options.method === 'PUT')) {
        const formData = new FormData();
        formData.append('_method', options.method);
        formData.append('_token', getCsrfToken());
        options.body = formData;
        options.method = 'POST';
    } else if (options.method === 'POST') {
        if (options.headers['Content-Type'] && options.headers['Content-Type'] === 'application/json') {
            const bodyData = typeof options.body === 'string' ? JSON.parse(options.body) : options.body;
            bodyData._token = getCsrfToken();
            options.body = JSON.stringify(bodyData);
        }
    }

    return fetch(url, options)
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.message || 'Request failed');
                });
            }
            return response.json();
        })
        .catch(error => {
            console.error('API Error:', error);
            throw error;
        });
}

function showError(message, duration = 5000) {
    const main = document.querySelector('main');
    const existingError = main.querySelector('.alert-error');

    if (existingError) {
        existingError.remove();
    }

    const alertDiv = document.createElement('div');
    alertDiv.className = 'mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-r shadow-md';
    alertDiv.innerHTML = `
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.293 12.293a1 1 0 101.414 1.414l2 2a1 1 0 001.414-1.414l-2-2z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium">${escapeHtml(message)}</p>
            </div>
        </div>
    `;

    main.insertBefore(alertDiv, main.firstChild);

    setTimeout(() => {
        alertDiv.remove();
    }, duration);
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

window.api = {
    get: (url) => fetchApi(url, { method: 'GET' }),
    post: (url, data) => fetchApi(url, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(data) }),
    put: (url, data) => fetchApi(url, { method: 'PUT', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(data) }),
    delete: (url) => fetchApi(url, { method: 'DELETE' })
};
