@extends('layouts.app')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900">
            {{ trans('messages.favorites') }}
        </h1>
    </div>

    @if ($favorites->count() > 0)
        <div id="moviesGrid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6 mt-8">
            @foreach ($favorites as $favorite)
                <div id="movie-card-{{ $favorite->id }}"
                    class="bg-white rounded-xl shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-105 hover:shadow-2xl group relative">
                    <div class="aspect-[2/3] bg-gray-900 relative overflow-hidden cursor-pointer"
                        onclick="window.location.href='/movies/{{ $favorite->imdb_id }}'">
                        @if ($favorite->poster && $favorite->poster !== 'N/A')
                            <img src="{{ $favorite->poster }}" alt="{{ $favorite->title }}"
                                class="w-full h-full object-cover">
                        @else
                            <span class="flex items-center justify-center h-full text-gray-500 text-4xl">🎬</span>
                        @endif
                        <button
                            class="absolute top-3 right-3 bg-yellow-400 hover:bg-yellow-500 text-black w-9 h-9 rounded-full flex items-center justify-center transition-all duration-300 hover:scale-110 z-10"
                            onclick="event.stopPropagation(); toggleFavorite({{ $favorite->id }}, '{{ $favorite->imdb_id }}', this)">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                <path
                                    d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                            </svg>
                        </button>
                    </div>
                    <div class="p-4">
                        <h3 class="text-base font-semibold text-gray-900 line-clamp-2 mb-2">{{ $favorite->title }}</h3>
                        <p class="text-sm text-gray-600">{{ $favorite->year }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-20">
            <div class="text-6xl mb-4">💔</div>
            <p class="text-xl text-gray-600 mb-6">{{ trans('messages.no_favorites') }}</p>
            <a href="{{ route('movies.index') }}"
                class="inline-flex items-center text-primary hover:text-primary-dark font-semibold transition-colors duration-200">
                {{ trans('messages.movies') }}
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
        </div>
    @endif
@endsection

@push('scripts')
    <script src="{{ asset('js/favorites.js') }}"></script>
@endpush
