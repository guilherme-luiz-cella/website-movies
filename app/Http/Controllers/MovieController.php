<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Services\TmdbService;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    protected $tmdb;

    public function __construct(TmdbService $tmdb)
    {
        $this->tmdb = $tmdb;
    }

    public function index(Request $request)
    {
        $typeFilter = $request->get('type', 'all');
        $statusFilter = $request->get('filter');

        $query = Movie::query()->latest();

        // Apply type filter
        if ($typeFilter === 'movies') {
            $query->movies();
        } elseif ($typeFilter === 'series') {
            $query->series();
        }

        // Apply status filter
        if ($statusFilter === 'pending') {
            $query->pending();
        } elseif ($statusFilter === 'watching') {
            $query->watching();
        } elseif ($statusFilter === 'watched') {
            $query->watched();
        }

        $movies = $query->get();

        // Calculate statistics
        $statsQuery = Movie::query();
        if ($typeFilter === 'movies') {
            $statsQuery->movies();
        } elseif ($typeFilter === 'series') {
            $statsQuery->series();
        }

        $stats = [
            'total' => $statsQuery->count(),
            'pending' => (clone $statsQuery)->pending()->count(),
            'watching' => (clone $statsQuery)->watching()->count(),
            'watched' => (clone $statsQuery)->watched()->count(),
            'movies_count' => Movie::movies()->count(),
            'series_count' => Movie::series()->count(),
        ];

        return view('movies.index', compact('movies', 'stats', 'typeFilter'));
    }

    public function create()
    {
        return view('movies.create');
    }

    public function show(Movie $movie)
    {
        return view('movies.show', compact('movie'));
    }

    public function search(Request $request)
    {
        $request->validate([
            'q' => ['required', 'string', 'min:2', 'max:255'],
            'type' => ['nullable', 'in:movie,series'],
            'language' => ['nullable', 'in:pt-BR,en-US'],
        ]);

        $query = $request->string('q')->toString();
        $type = $request->get('type', 'movie');
        $language = $request->get('language', 'pt-BR');

        $results = $this->tmdb->search($query, $type, $language);

        return response()->json($results);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', 'in:movie,series'],
            'title' => ['required', 'string', 'max:255'],
            'status' => ['nullable', 'in:pending,watching,watched'],
            'tmdb_id' => ['nullable', 'integer'],
            'seasons' => ['nullable', 'integer', 'min:1'],
            'episodes' => ['nullable', 'integer', 'min:1'],
        ]);

        $movieData = [
            'type' => $validated['type'],
            'title' => $validated['title'],
            'status' => $validated['status'] ?? 'pending',
            'year' => null,
            'seasons' => $validated['type'] === 'series' ? ($validated['seasons'] ?? null) : null,
            'episodes' => $validated['type'] === 'series' ? ($validated['episodes'] ?? null) : null,
            'tmdb_id' => $validated['tmdb_id'] ?? null,
            'poster_url' => null,
            'overview' => null,
            'watched_at' => ($validated['status'] ?? 'pending') === 'watched' ? now() : null,
        ];

        // If we have a TMDB ID, fetch full details
        if (!empty($validated['tmdb_id'])) {
            $language = $request->get('language', 'pt-BR');
            $details = $this->tmdb->getDetails((int) $validated['tmdb_id'], $validated['type'], $language);

            if ($details) {
                $movieData = array_merge($movieData, [
                    'title' => $details['title'] ?? $movieData['title'],
                    'year' => $details['year'] ?? null,
                    'poster_url' => $details['poster_url'] ?? null,
                    'overview' => $details['overview'] ?? null,
                ]);

                if ($validated['type'] === 'series' && isset($details['seasons'])) {
                    $movieData['seasons'] = $details['seasons'];
                    $movieData['episodes'] = $details['episodes'] ?? null;
                }

                // Check if movie already exists
                $exists = Movie::where('tmdb_id', $validated['tmdb_id'])
                    ->where('type', $validated['type'])
                    ->first();

                if ($exists) {
                    return redirect()
                        ->route('movies.index')
                        ->with('status', 'Este título já está na sua lista!');
                }
            }
        }

        Movie::create($movieData);

        $message = $validated['type'] === 'movie' ? 'Filme adicionado com sucesso!' : 'Série adicionada com sucesso!';

        return redirect()
            ->route('movies.index', ['type' => $validated['type'] === 'series' ? 'series' : 'movies'])
            ->with('status', $message);
    }

    public function toggleStatus(Movie $movie)
    {
        // Cycle through: pending -> watching -> watched -> pending
        $statusCycle = [
            'pending' => 'watching',
            'watching' => 'watched',
            'watched' => 'pending',
        ];

        $newStatus = $statusCycle[$movie->status] ?? 'pending';

        $movie->update([
            'status' => $newStatus,
            'watched_at' => $newStatus === 'watched' ? now() : null,
        ]);

        return redirect()->route('movies.index');
    }

    public function destroy(Movie $movie)
    {
        $type = $movie->type;
        $movie->delete();

        $message = $type === 'movie' ? 'Filme removido com sucesso!' : 'Série removida com sucesso!';

        return redirect()
            ->route('movies.index')
            ->with('status', $message);
    }
}
