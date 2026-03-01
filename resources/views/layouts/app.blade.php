<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MovieApp - Your Personal Movie Database</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#e50914',
                        'primary-dark': '#b2070f',
                        secondary: '#f5f5f5',
                        dark: '#1a1a2e',
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .loader {
            border: 3px solid rgba(229, 9, 20, 0.1);
            border-top: 3px solid #e50914;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen">
    @auth
        <nav class="bg-dark text-white sticky top-0 z-50 shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center space-x-8">
                        <a href="{{ route('movies.index') }}" class="flex-shrink-0">
                            <span class="text-2xl font-extrabold text-primary">MovieApp</span>
                        </a>
                        <div class="hidden md:flex space-x-6">
                            <a href="{{ route('movies.index') }}"
                                class="text-white hover:text-primary transition-colors duration-200 font-medium">
                                {{ trans('messages.movies') }}
                            </a>
                            <a href="{{ route('favorites.index') }}"
                                class="text-white hover:text-primary transition-colors duration-200 font-medium">
                                {{ trans('messages.favorites') }}
                            </a>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <form
                            action="{{ route('language.switch', ['locale' => app()->getLocale() === 'en' ? 'id' : 'en']) }}"
                            method="POST" class="flex items-center">
                            @csrf
                            <button type="submit"
                                class="bg-gray-700 hover:bg-gray-600 text-white px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-200">
                                {{ app()->getLocale() === 'en' ? 'ID' : 'EN' }}
                            </button>
                        </form>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200">
                                {{ trans('messages.logout') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="md:hidden border-t border-gray-700">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <a href="{{ route('movies.index') }}"
                        class="block px-3 py-2 rounded-md text-base font-medium text-white hover:text-primary hover:bg-gray-700 transition-all">
                        {{ trans('messages.movies') }}
                    </a>
                    <a href="{{ route('favorites.index') }}"
                        class="block px-3 py-2 rounded-md text-base font-medium text-white hover:text-primary hover:bg-gray-700 transition-all">
                        {{ trans('messages.favorites') }}
                    </a>
                </div>
            </div>
        </nav>
    @endauth

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if (session('error'))
            <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-r shadow-md">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.293 12.293a1 1 0 101.414 1.414l2 2a1 1 0 001.414-1.414l-2-2z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif
        @if (session('success'))
            <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-r shadow-md">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.293 12.293a1 1 0 101.414 1.414l2 2a1 1 0 001.414-1.414l-2-2z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif
        @if ($errors->any())
            <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-r shadow-md">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.293 12.293a1 1 0 101.414 1.414l2 2a1 1 0 001.414-1.414l-2-2z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        @foreach ($errors->all() as $error)
                            <p class="text-sm font-medium">{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <script src="{{ asset('js/api.js') }}"></script>
    @stack('scripts')
    <script src="{{ asset('js/modules/FavoriteManager.js') }}"></script>
    <script src="{{ asset('js/modules/UIManager.js') }}"></script>
</body>

</html>
