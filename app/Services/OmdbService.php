<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class OmdbService
{
    private $client;

    private $apiKey;

    private $baseUrl;

    public function __construct()
    {
        $this->client = new Client([
            'timeout' => 10,
            'connect_timeout' => 5,
        ]);
        $this->apiKey = config('omdb.api_key');
        $this->baseUrl = 'http://www.omdbapi.com/';
    }

    public function search($query, $page = 1, $type = '')
    {
        $cacheKey = "omdb_search_{$query}_{$page}_{$type}";

        try {
            return Cache::remember($cacheKey, 60, function () use ($query, $page, $type) {
                $params = [
                    'apikey' => $this->apiKey,
                    's' => $query,
                    'page' => $page,
                ];

                if (!empty($type)) {
                    $params['type'] = $type;
                }

                $response = $this->client->get($this->baseUrl, [
                    'query' => $params,
                ]);

                $data = json_decode($response->getBody()->getContents(), true);

                if ($data['Response'] === 'False') {
                    return [
                        'success' => false,
                        'message' => $data['Error'] ?? 'No results found',
                    ];
                }

                return [
                    'success' => true,
                    'movies' => $data['Search'],
                    'totalResults' => $data['totalResults'],
                    'currentPage' => $page,
                ];
            });
        } catch (GuzzleException $e) {
            Log::error('OMDB API Guzzle Error: ' . $e->getMessage(), [
                'url' => $this->baseUrl,
                'params' => $params ?? [],
                'api_key_exists' => !empty($this->apiKey),
            ]);
            return [
                'success' => false,
                'message' => 'Failed to connect to movie database. Please try again.',
            ];
        } catch (\Exception $e) {
            Log::error('OMDB API Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'An error occurred. Please try again.',
            ];
        }
    }

    public function getEpisode($title, $season, $episode)
    {
        $cacheKey = "omdb_episode_{$title}_{$season}_{$episode}";

        try {
            return Cache::remember($cacheKey, 1440, function () use ($title, $season, $episode) {
                $response = $this->client->get($this->baseUrl, [
                    'query' => [
                        'apikey' => $this->apiKey,
                        't' => $title,
                        'Season' => $season,
                        'Episode' => $episode,
                    ],
                ]);

                $data = json_decode($response->getBody()->getContents(), true);

                if ($data['Response'] === 'False') {
                    return [
                        'success' => false,
                        'message' => $data['Error'] ?? 'Episode not found',
                    ];
                }

                return [
                    'success' => true,
                    'episode' => $data,
                ];
            });
        } catch (GuzzleException $e) {
            Log::error('OMDB API Guzzle Error: ' . $e->getMessage(), [
                'url' => $this->baseUrl,
                'title' => $title,
                'season' => $season,
                'episode' => $episode,
            ]);
            return [
                'success' => false,
                'message' => 'Failed to connect to movie database. Please try again.',
            ];
        } catch (\Exception $e) {
            Log::error('OMDB API Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'An error occurred. Please try again.',
            ];
        }
    }

    public function getById($imdbId)
    {
        $cacheKey = "omdb_movie_{$imdbId}";

        try {
            return Cache::remember($cacheKey, 1440, function () use ($imdbId) {
                $response = $this->client->get($this->baseUrl, [
                    'query' => [
                        'apikey' => $this->apiKey,
                        'i' => $imdbId,
                        'plot' => 'full',
                    ],
                ]);

                $data = json_decode($response->getBody()->getContents(), true);

                if ($data['Response'] === 'False') {
                    return [
                        'success' => false,
                        'message' => $data['Error'] ?? 'Movie not found',
                    ];
                }

                return [
                    'success' => true,
                    'movie' => $data,
                ];
            });
        } catch (GuzzleException $e) {
            Log::error('OMDB API Guzzle Error: ' . $e->getMessage(), [
                'url' => $this->baseUrl,
                'imdbId' => $imdbId,
            ]);
            return [
                'success' => false,
                'message' => 'Failed to connect to movie database. Please try again.',
            ];
        } catch (\Exception $e) {
            Log::error('OMDB API Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'An error occurred. Please try again.',
            ];
        }
    }
}
