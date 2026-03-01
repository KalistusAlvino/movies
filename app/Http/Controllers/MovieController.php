<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchMovieRequest;
use App\Http\Requests\GetEpisodeRequest;
use App\Http\Requests\GetByImdbRequest;
use Illuminate\Http\Request;
use App\Services\OmdbService;
use App\FavoriteMovie;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\Log;

class MovieController extends Controller
{
    private $omdbService;

    public function __construct(OmdbService $omdbService)
    {
        $this->omdbService = $omdbService;
    }

    public function index()
    {
        try {
            return view('movies.index');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred. Please try again.');
        }
    }

    public function getEpisode(GetEpisodeRequest $request)
    {
        try {
            $validated = $request->validated();
            $title = $validated['t'];
            $season = $validated['Season'];
            $episode = $validated['Episode'];

            $result = $this->omdbService->getEpisode($title, $season, $episode);

            if (!$result['success']) {
                return ApiResponse::error($result['message']);
            }

            return response()->json([
                'success' => true,
                'episode' => $result['episode'],
            ]);
        } catch (\Exception $e) {
            Log::error('Get episode error: ' . $e->getMessage());
            return ApiResponse::error('An error occurred while fetching episode. Please try again.');
        }
    }

    public function search(SearchMovieRequest $request)
    {
        try {
            $validated = $request->validated();
            $query = $validated['s'];
            $page = $validated['page'] ?? 1;
            $type = $validated['type'] ?? '';

            $result = $this->omdbService->search($query, $page, $type);

            if (!$result['success']) {
                return ApiResponse::error($result['message']);
            }

            return response()->json([
                'success' => true,
                'movies' => $result['movies'],
                'totalResults' => $result['totalResults'],
                'currentPage' => $result['currentPage'],
            ]);
        } catch (\Exception $e) {
            \Log::error('Search error: ' . $e->getMessage());
            return ApiResponse::error('An error occurred while searching. Please try again.');
        }
    }

    public function show($id)
    {
        try {
            $result = $this->omdbService->getById($id);

            if (!$result['success']) {
                return redirect()
                    ->route('movies.index')
                    ->with('error', $result['message']);
            }

            $movie = $result['movie'];
            $favorite = $this->checkFavorite($id);

            return view('movies.show', [
                'movie' => $movie,
                'isFavorite' => $favorite !== null,
                'favoriteId' => $favorite ? $favorite->id : null,
            ]);
        } catch (\Exception $e) {
            return redirect()
                ->route('movies.index')
                ->with('error', 'An error occurred. Please try again.');
        }
    }

    public function getByImdb(GetByImdbRequest $request)
    {
        try {
            $validated = $request->validated();
            $imdbId = $validated['imdbId'];

            $result = $this->omdbService->getById($imdbId);

            if (!$result['success']) {
                return ApiResponse::error($result['message']);
            }

            return response()->json([
                'success' => true,
                'movie' => $result['movie'],
            ]);
        } catch (\Exception $e) {
            return ApiResponse::error('An error occurred. Please try again.');
        }
    }

    private function checkFavorite($imdbId)
    {
        try {
            return FavoriteMovie::where('user_id', auth()->id())
                ->where('imdb_id', $imdbId)
                ->first();
        } catch (\Exception $e) {
            return null;
        }
    }
}
