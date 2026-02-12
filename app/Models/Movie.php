<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title',
        'year',
        'seasons',
        'episodes',
        'imdb_id',
        'tmdb_id',
        'poster_url',
        'overview',
        'status',
        'watched_at',
    ];

    protected $casts = [
        'watched_at' => 'datetime',
        'seasons' => 'integer',
        'episodes' => 'integer',
    ];

    // Scopes
    public function scopeMovies($query)
    {
        return $query->where('type', 'movie');
    }

    public function scopeSeries($query)
    {
        return $query->where('type', 'series');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeWatching($query)
    {
        return $query->where('status', 'watching');
    }

    public function scopeWatched($query)
    {
        return $query->where('status', 'watched');
    }

    // Helper methods
    public function isMovie(): bool
    {
        return $this->type === 'movie';
    }

    public function isSeries(): bool
    {
        return $this->type === 'series';
    }

    public function getTypeLabel(): string
    {
        return $this->type === 'movie' ? 'Filme' : 'Série';
    }

    public function getStatusLabel(): string
    {
        return match($this->status) {
            'pending' => 'Para Assistir',
            'watching' => 'Assistindo',
            'watched' => 'Assistido',
            default => 'Desconhecido'
        };
    }

    public function getStatusIcon(): string
    {
        return match($this->status) {
            'pending' => '📋',
            'watching' => '▶️',
            'watched' => '✓',
            default => '❓'
        };
    }
}
