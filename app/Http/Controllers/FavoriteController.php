<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddFavoriteRequest;
use App\Http\Requests\CheckFavoriteRequest;
use Illuminate\Http\Request;
use App\Services\OmdbService;
use App\FavoriteMovie;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\Log;

class FavoriteController extends Controller
{
    private $omdbService;

    public function __construct(OmdbService $omdbService)
    {
        $this->omdbService = $omdbService;
    }

    public function index()
    {
        try {
            $favorites = FavoriteMovie::forUser(auth()->id())
                ->latest()
                ->get();

            return view('favorites.index', compact('favorites'));
        } catch (\Exception $e) {
            Log::error('Load favorites error: ' . $e->getMessage());
            return back()->with('error', 'Failed to load favorites. Please try again.');
        }
    }

    public function add(AddFavoriteRequest $request)
    {
        try {
            $imdbId = $request->validated()['imdb_id'];

            $exists = FavoriteMovie::forUser(auth()->id())
                ->byImdbId($imdbId)
                ->exists();

            if ($exists) {
                return ApiResponse::error('Movie already in favorites');
            }

            $result = $this->omdbService->getById($imdbId);

            if (!$result['success']) {
                return ApiResponse::error($result['message']);
            }

            $movie = $result['movie'];

            $favorite = FavoriteMovie::create([
                'user_id' => auth()->id(),
                'imdb_id' => $movie['imdbID'],
                'title' => $movie['Title'],
                'year' => $movie['Year'],
                'poster' => $movie['Poster'],
                'plot' => $movie['Plot'] ?? '',
            ]);

            return ApiResponse::success([
                'favorite' => [
                    'id' => $favorite->id,
                    'imdb_id' => $favorite->imdb_id,
                ]
            ], trans('messages.add_to_favorites'));
        } catch (\Exception $e) {
            Log::error('Add favorite error: ' . $e->getMessage());
            return ApiResponse::error('Failed to add to favorites. Please try again.');
        }
    }

    public function remove($id)
    {
        try {
            $favorite = FavoriteMovie::forUser(auth()->id())
                ->where('id', $id)
                ->first();

            if (!$favorite) {
                return ApiResponse::error('Favorite not found', 404);
            }

            $deleted = $favorite->delete();

            if (!$deleted) {
                return ApiResponse::error('Failed to delete favorite. Please try again.');
            }

            return ApiResponse::success([], trans('messages.remove_from_favorites'));
        } catch (\Exception $e) {
            Log::error('Remove favorite error: ' . $e->getMessage());
            return ApiResponse::error('Failed to remove favorite. Please try again.');
        }
    }

    public function check(CheckFavoriteRequest $request)
    {
        try {
            $validated = $request->validated();
            $imdbId = $validated['imdb_id'] ?? null;

            if (empty($imdbId)) {
                return response()->json([
                    'is_favorite' => false,
                    'favorite_id' => null,
                ]);
            }

            $favorite = FavoriteMovie::forUser(auth()->id())
                ->byImdbId($imdbId)
                ->first();

            return response()->json([
                'is_favorite' => $favorite !== null,
                'favorite_id' => $favorite ? $favorite->id : null,
            ]);
        } catch (\Exception $e) {
            Log::error('Check favorite error: ' . $e->getMessage());
            return response()->json([
                'is_favorite' => false,
                'favorite_id' => null,
            ]);
        }
    }
}
