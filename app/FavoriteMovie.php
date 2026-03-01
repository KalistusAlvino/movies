<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FavoriteMovie extends Model
{
    protected $fillable = [
        'user_id',
        'imdb_id',
        'title',
        'year',
        'poster',
        'plot',
    ];

    protected $casts = [
        'year' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByImdbId($query, $imdbId)
    {
        return $query->where('imdb_id', $imdbId);
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
