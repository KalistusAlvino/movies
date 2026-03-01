@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <a href="{{ route('movies.index') }}"
            class="inline-flex items-center text-gray-600 hover:text-primary transition-colors duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            {{ trans('messages.movies') }}
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-[300px_1fr] gap-8 p-8">
            <div class="relative">
                <div class="aspect-[2/3] bg-gray-900 rounded-xl overflow-hidden">
                    @if ($movie['Poster'] && $movie['Poster'] !== 'N/A')
                        <img src="{{ $movie['Poster'] }}" alt="{{ $movie['Title'] }}" class="w-full h-full object-cover">
                    @else
                        <span class="flex items-center justify-center h-full text-gray-500 text-6xl">🎬</span>
                    @endif
                </div>
            </div>
            <div class="space-y-6">
                <div>
                    <h1 class="text-4xl font-extrabold text-gray-900 leading-tight mb-4">
                        {{ $movie['Title'] }}
                    </h1>
                    <div class="flex flex-wrap gap-3">
                        <span class="bg-gray-100 text-gray-700 px-4 py-1.5 rounded-full text-sm font-medium">
                            {{ $movie['Year'] }}
                        </span>
                        <span class="bg-gray-100 text-gray-700 px-4 py-1.5 rounded-full text-sm font-medium">
                            {{ $movie['Type'] }}
                        </span>
                        @if (isset($movie['imdbRating']) && $movie['imdbRating'] !== 'N/A')
                            <span class="bg-yellow-100 text-yellow-700 px-4 py-1.5 rounded-full text-sm font-medium">
                                ⭐ {{ $movie['imdbRating'] }}
                            </span>
                        @endif
                    </div>
                </div>

                <button id="btnFavorite"
                    class="inline-flex items-center gap-3 px-6 py-3 rounded-lg font-semibold transition-all duration-200 {{ $isFavorite ? 'bg-yellow-400 hover:bg-yellow-500 text-black' : 'bg-primary hover:bg-primary-dark text-white' }} shadow-md"
                    data-imdb-id="{{ $movie['imdbID'] }}" data-favorite-id="{{ $favoriteId }}"
                    data-add-text="{{ trans('messages.add_to_favorites') }}"
                    data-remove-text="{{ trans('messages.remove_from_favorites') }}">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                        <path
                            d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                    </svg>
                    <span
                        id="favoriteText">{{ $isFavorite ? trans('messages.remove_from_favorites') : trans('messages.add_to_favorites') }}</span>
                </button>

                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-3">{{ trans('messages.plot') }}</h3>
                    <p class="text-gray-700 leading-relaxed">
                        @if ($movie['Plot'] && $movie['Plot'] !== 'N/A')
                            {{ $movie['Plot'] }}
                        @else
                            No plot available.
                        @endif
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @if (isset($movie['Director']) && $movie['Director'] !== 'N/A')
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-sm text-gray-600 mb-1">{{ trans('messages.director') }}</div>
                            <div class="text-base font-semibold text-gray-900">{{ $movie['Director'] }}</div>
                        </div>
                    @endif
                    @if (isset($movie['Actors']) && $movie['Actors'] !== 'N/A')
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-sm text-gray-600 mb-1">{{ trans('messages.actors') }}</div>
                            <div class="text-base font-semibold text-gray-900">{{ $movie['Actors'] }}</div>
                        </div>
                    @endif
                    @if (isset($movie['Genre']) && $movie['Genre'] !== 'N/A')
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-sm text-gray-600 mb-1">{{ trans('messages.genre') }}</div>
                            <div class="text-base font-semibold text-gray-900">{{ $movie['Genre'] }}</div>
                        </div>
                    @endif
                    @if (isset($movie['Runtime']) && $movie['Runtime'] !== 'N/A')
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-sm text-gray-600 mb-1">{{ trans('messages.runtime') }}</div>
                            <div class="text-base font-semibold text-gray-900">{{ $movie['Runtime'] }}</div>
                        </div>
                    @endif
                    @if (isset($movie['Language']) && $movie['Language'] !== 'N/A')
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-sm text-gray-600 mb-1">{{ trans('messages.language') }}</div>
                            <div class="text-base font-semibold text-gray-900">{{ $movie['Language'] }}</div>
                        </div>
                    @endif
                    @if (isset($movie['imdbVotes']) && $movie['imdbVotes'] !== 'N/A')
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-sm text-gray-600 mb-1">IMDb Votes</div>
                            <div class="text-base font-semibold text-gray-900">{{ $movie['imdbVotes'] }}</div>
                        </div>
                    @endif
                    @if (isset($movie['Awards']) && $movie['Awards'] !== 'N/A')
                        <div class="bg-gray-50 rounded-lg p-4 sm:col-span-2 lg:col-span-3">
                            <div class="text-sm text-gray-600 mb-1">Awards</div>
                            <div class="text-base font-semibold text-gray-900">{{ $movie['Awards'] }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.isFavorite = {{ $isFavorite ? 'true' : 'false' }};
        });
    </script>
    <script src="{{ asset('js/movie-detail.js') }}"></script>
@endpush
