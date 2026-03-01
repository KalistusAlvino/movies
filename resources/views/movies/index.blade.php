@extends('layouts.app')

@section('content')
    <div class="mb-8">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">
                    {{ trans('messages.search_movies') }}
                </h2>
                <div class="flex flex-col sm:flex-row gap-4">
                    <input type="text" id="searchInput"
                        class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200 text-gray-900 placeholder-gray-400"
                        placeholder="{{ trans('messages.search_by_title') }}">
                    <select id="searchType"
                        class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200 text-gray-900 bg-white cursor-pointer"
                        onchange="toggleEpisodeInputs()">
                        <option value="">{{ trans('messages.search') }}</option>
                        <option value="movie">Movie</option>
                        <option value="series">Series</option>
                        <option value="episode">Episode</option>
                    </select>
                    <div id="episodeInputs" class="hidden flex gap-2">
                        <input type="text" id="searchTitle"
                            class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200 text-gray-900 placeholder-gray-400"
                            placeholder="Series Title (e.g., Friends, The Office)">
                        <input type="number" id="searchSeason"
                            class="w-24 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200 text-gray-900 placeholder-gray-400"
                            placeholder="Season" min="1">
                        <input type="number" id="searchEpisode"
                            class="w-24 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200 text-gray-900 placeholder-gray-400"
                            placeholder="Episode" min="1">
                    </div>
                    <button id="searchBtn"
                        class="bg-primary hover:bg-primary-dark text-white font-semibold px-6 py-3 rounded-lg transition-all duration-200 shadow-md">
                        {{ trans('messages.search') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="errorMessage" class="hidden mb-8 bg-red-50 border-l-4 border-red-500 text-red-700 p-6 rounded-r shadow-md">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.293 12.293a1 1 0 101.414 1.414l2 2a1 1 0 001.414-1.414l-2-2z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium" id="errorMessageText"></p>
            </div>
        </div>
    </div>

    <div id="emptyState" class="hidden text-center py-20">
        <div class="text-6xl mb-4">🎬</div>
        <p class="text-gray-600 text-lg">{{ trans('messages.no_results') }}</p>
    </div>

    <div id="moviesGrid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6 mt-8"></div>

    <div id="loadMoreContainer" class="hidden text-center mt-8">
        <button id="loadMoreBtn"
            class="bg-white hover:bg-gray-100 text-gray-900 font-semibold px-8 py-3 rounded-lg border-2 border-gray-300 transition-all duration-200 shadow-md">
            {{ trans('messages.load_more') }}
        </button>
    </div>

    <div id="loading" class="hidden text-center py-12">
        <div class="loader"></div>
    </div>

    <template id="movieCardTemplate">
        <div
            class="bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer transform transition-all duration-300 hover:scale-105 hover:shadow-2xl group relative">
            <div class="aspect-[2/3] bg-gray-900 relative overflow-hidden">
                <img src="" alt=""
                    class="w-full h-full object-cover opacity-0 transition-opacity duration-500 movie-poster">
                <span class="absolute inset-0 flex items-center justify-center text-gray-500 text-4xl placeholder">🎬</span>
                <span
                    class="absolute top-3 right-3 bg-black bg-opacity-70 text-white px-2 py-1 rounded text-xs font-bold uppercase tracking-wider movie-type"></span>
                <button
                    class="absolute bottom-3 right-3 bg-primary hover:bg-primary-dark text-white w-9 h-9 rounded-full flex items-center justify-center transition-all duration-300 hover:scale-110 z-10 btn-favorite">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                        <path
                            d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                    </svg>
                </button>
            </div>
            <div class="p-4">
                <h3 class="text-base font-semibold text-gray-900 line-clamp-2 mb-2 movie-title"></h3>
                <p class="text-sm text-gray-600 movie-year"></p>
            </div>
        </div>
    </template>

    <template id="episodeCardTemplate">
        <div
            class="bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer transform transition-all duration-300 hover:scale-105 hover:shadow-2xl group relative">
            <div class="aspect-[2/3] bg-gray-900 relative overflow-hidden">
                <img src="" alt=""
                    class="w-full h-full object-cover opacity-0 transition-opacity duration-500 episode-poster">
                <span
                    class="absolute inset-0 flex items-center justify-center text-gray-500 text-4xl placeholder hidden">🎬</span>
                <span
                    class="absolute top-3 left-3 bg-black bg-opacity-70 text-white px-2 py-1 rounded text-xs font-bold uppercase tracking-wider episode-type"></span>
                <button
                    class="absolute top-3 right-3 bg-yellow-400 hover:bg-yellow-500 text-white w-9 h-9 rounded-full flex items-center justify-center transition-all duration-300 hover:scale-110 z-10 btn-favorite">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                        <path
                            d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                    </svg>
                </button>
            </div>
            <div class="p-4">
                <h3 class="text-base font-semibold text-gray-900 line-clamp-2 mb-2 episode-title"></h3>
                <p class="text-sm text-gray-600">
                    <span class="font-semibold">S</span><span class="episode-season"></span>
                    <span class="font-semibold">E</span><span class="episode-number"></span>
                    <span class="episode-year"></span>
                </p>
            </div>
        </div>
    </template>
@endsection

@push('scripts')
    <script src="{{ asset('js/movies.js') }}"></script>
@endpush
