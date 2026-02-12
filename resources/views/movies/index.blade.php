<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Meus Filmes • {{ config('app.name') }}</title>
    <meta name="description" content="Organize seus filmes para assistir e acompanhe o que já assistiu">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#141414] text-white">
    <!-- Header estilo Netflix -->
    <header class="fixed top-0 z-50 w-full header-container transition-all duration-300" id="header">
        <div class="container-netflix flex items-center justify-between py-4">
            <div class="flex items-center gap-8">
                <h1 class="text-2xl font-bold text-red-600 sm:text-3xl">MEUS FILMES & SÉRIES</h1>
                <nav class="hidden items-center gap-6 md:flex">
                    <a href="{{ route('movies.index') }}" 
                       class="nav-link text-sm font-medium {{ $typeFilter === 'all' ? 'text-white' : 'text-gray-300' }}">Todos</a>
                    <a href="{{ route('movies.index', ['type' => 'movies']) }}"
                        class="nav-link text-sm font-medium {{ $typeFilter === 'movies' ? 'text-white' : 'text-gray-300' }}">Filmes</a>
                    <a href="{{ route('movies.index', ['type' => 'series']) }}"
                        class="nav-link text-sm font-medium {{ $typeFilter === 'series' ? 'text-white' : 'text-gray-300' }}">Séries</a>
                </nav>
            </div>

            <a href="{{ route('movies.create') }}"
                class="btn-primary flex items-center gap-2 rounded px-4 py-2 text-sm font-semibold">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Adicionar
            </a>
        </div>
    </header>

    <!-- Hero/Banner Section -->
    <section class="hero-section relative">
        <div class="hero-overlay-left absolute inset-0"></div>
        <div class="hero-overlay-bottom absolute inset-0"></div>

        @if($movies->isNotEmpty() && $movies->first()->poster_url)
            <img src="{{ $movies->first()->poster_url }}" alt="{{ $movies->first()->title }}" class="hero-image">
        @else
            <div class="hero-gradient-bg h-full w-full"></div>
        @endif

        <div class="absolute inset-0 flex items-center">
            <div class="container-netflix">
                <div class="max-w-2xl">
                    @if($movies->isNotEmpty())
                        <h2 class="mb-4 text-4xl font-bold sm:text-5xl lg:text-6xl">{{ $movies->first()->title }}</h2>
                        @if($movies->first()->overview)
                            <p class="mb-6 line-clamp-3 text-lg text-gray-300">{{ $movies->first()->overview }}</p>
                        @endif
                        <div class="flex flex-wrap gap-3">
                            <form action="{{ route('movies.toggle-status', $movies->first()) }}" method="POST"
                                class="inline">
                                @csrf
                                @method('PATCH')
                                <button
                                    class="flex items-center gap-2 rounded bg-white px-6 py-3 font-bold text-black transition hover:bg-gray-200">
                                    @if($movies->first()->status === 'watched')
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        Assistido
                                    @else
                                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z" />
                                        </svg>
                                        Assistir
                                    @endif
                                </button>
                            </form>
                            <a href="{{ route('movies.show', $movies->first()) }}" 
                               class="btn-secondary flex items-center gap-2 rounded px-6 py-3 font-bold">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Mais informações
                            </a>
                        </div>
                    @else
                        <h2 class="mb-4 text-4xl font-bold sm:text-5xl lg:text-6xl">Bem-vindo à sua lista</h2>
                        <p class="mb-6 text-lg text-gray-300">Comece adicionando filmes e séries que você quer assistir</p>
                        <a href="{{ route('movies.create') }}"
                            class="btn-primary inline-flex items-center gap-2 rounded px-6 py-3 font-bold">
                            Adicionar Título
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Bar -->
    <section class="stats-bar py-6">
        <div class="container-netflix">
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-5">
                <div class="text-center">
                    <p class="text-3xl font-bold text-red-600">{{ $stats['total'] }}</p>
                    <p class="mt-1 text-sm text-gray-400">
                        @if($typeFilter === 'movies')
                            Filmes
                        @elseif($typeFilter === 'series')
                            Séries
                        @else
                            Total
                        @endif
                    </p>
                </div>
                <div class="text-center">
                    <p class="text-3xl font-bold text-amber-500">{{ $stats['pending'] }}</p>
                    <p class="mt-1 text-sm text-gray-400">Para Assistir</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl font-bold text-blue-500">{{ $stats['watching'] }}</p>
                    <p class="mt-1 text-sm text-gray-400">Assistindo</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl font-bold text-emerald-500">{{ $stats['watched'] }}</p>
                    <p class="mt-1 text-sm text-gray-400">Já Assistidos</p>
                </div>
                @if($typeFilter === 'all')
                <div class="text-center">
                    <p class="text-3xl font-bold text-purple-500">{{ $stats['series_count'] }}</p>
                    <p class="mt-1 text-sm text-gray-400">Séries</p>
                </div>
                @else
                <div class="text-center">
                    @php
                        $percentage = $stats['total'] > 0 ? (int) round(($stats['watched'] / $stats['total']) * 100) : 0;
                    @endphp
                    <p class="text-3xl font-bold text-blue-500">{{ $percentage }}%</p>
                    <p class="mt-1 text-sm text-gray-400">Progresso</p>
                </div>
                @endif
            </div>
        </div>
    </section>

    @if(session('status'))
        <div class="container-netflix py-4">
            <div class="alert-success">
                {{ session('status') }}
            </div>
        </div>
    @endif

    <!-- Movies Grid -->
    <main class="container-netflix py-8">
        @if($typeFilter === 'all')
            <!-- Separate Movies and Series when viewing All -->
            @php
                $pendingMovies = $movies->where('type', 'movie')->where('status', 'pending');
                $watchingMovies = $movies->where('type', 'movie')->where('status', 'watching');
                $watchedMovies = $movies->where('type', 'movie')->where('status', 'watched');
                $pendingSeries = $movies->where('type', 'series')->where('status', 'pending');
                $watchingSeries = $movies->where('type', 'series')->where('status', 'watching');
                $watchedSeries = $movies->where('type', 'series')->where('status', 'watched');
            @endphp

            <!-- Movies Section -->
            @if($pendingMovies->count() > 0 || $watchingMovies->count() > 0 || $watchedMovies->count() > 0)
                <section class="mb-12">
                    <div class="mb-6 flex items-center gap-3">
                        <span class="text-4xl">🎬</span>
                        <h2 class="text-3xl font-bold">Filmes</h2>
                        <span class="rounded-full bg-red-600/20 px-3 py-1 text-sm font-medium text-red-400">
                            {{ $stats['movies_count'] }}
                        </span>
                    </div>

                    @if($pendingMovies->count() > 0)
                        <div class="mb-8">
                            <h3 class="mb-4 text-xl font-semibold text-amber-400">📋 Para Assistir</h3>
                            <div class="movies-grid">
                                @foreach($pendingMovies as $movie)
                                    @include('movies.partials.card', ['movie' => $movie])
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($watchingMovies->count() > 0)
                        <div class="mb-8">
                            <h3 class="mb-4 text-xl font-semibold text-blue-400">▶️ Assistindo</h3>
                            <div class="movies-grid">
                                @foreach($watchingMovies as $movie)
                                    @include('movies.partials.card', ['movie' => $movie])
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($watchedMovies->count() > 0)
                        <div>
                            <h3 class="mb-4 text-xl font-semibold text-emerald-400">✅ Já Assistidos</h3>
                            <div class="movies-grid">
                                @foreach($watchedMovies as $movie)
                                    @include('movies.partials.card', ['movie' => $movie])
                                @endforeach
                            </div>
                        </div>
                    @endif
                </section>
            @endif

            <!-- Series Section -->
            @if($pendingSeries->count() > 0 || $watchingSeries->count() > 0 || $watchedSeries->count() > 0)
                <section class="mb-12">
                    <div class="mb-6 flex items-center gap-3">
                        <span class="text-4xl">📺</span>
                        <h2 class="text-3xl font-bold">Séries</h2>
                        <span class="rounded-full bg-purple-600/20 px-3 py-1 text-sm font-medium text-purple-400">
                            {{ $stats['series_count'] }}
                        </span>
                    </div>

                    @if($pendingSeries->count() > 0)
                        <div class="mb-8">
                            <h3 class="mb-4 text-xl font-semibold text-amber-400">📋 Para Assistir</h3>
                            <div class="movies-grid">
                                @foreach($pendingSeries as $movie)
                                    @include('movies.partials.card', ['movie' => $movie])
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($watchingSeries->count() > 0)
                        <div class="mb-8">
                            <h3 class="mb-4 text-xl font-semibold text-blue-400">▶️ Assistindo</h3>
                            <div class="movies-grid">
                                @foreach($watchingSeries as $movie)
                                    @include('movies.partials.card', ['movie' => $movie])
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($watchedSeries->count() > 0)
                        <div>
                            <h3 class="mb-4 text-xl font-semibold text-emerald-400">✅ Já Assistidos</h3>
                            <div class="movies-grid">
                                @foreach($watchedSeries as $movie)
                                    @include('movies.partials.card', ['movie' => $movie])
                                @endforeach
                            </div>
                        </div>
                    @endif
                </section>
            @endif
        @else
            <!-- Regular view for Movies or Series only -->
            @if($stats['pending'] > 0)
                <section class="mb-12">
                    <h3 class="mb-4 text-2xl font-bold text-amber-400">📋 Para Assistir</h3>
                    <div class="movies-grid">
                        @foreach($movies->where('status', 'pending') as $movie)
                            @include('movies.partials.card', ['movie' => $movie])
                        @endforeach
                    </div>
                </section>
            @endif

            @if($stats['watching'] > 0)
                <section class="mb-12">
                    <h3 class="mb-4 text-2xl font-bold text-blue-400">▶️ Assistindo</h3>
                    <div class="movies-grid">
                        @foreach($movies->where('status', 'watching') as $movie)
                            @include('movies.partials.card', ['movie' => $movie])
                        @endforeach
                    </div>
                </section>
            @endif

            @if($stats['watched'] > 0)
                <section>
                    <h3 class="mb-4 text-2xl font-bold text-emerald-400">✅ Já Assistidos</h3>
                    <div class="movies-grid">
                        @foreach($movies->where('status', 'watched') as $movie)
                            @include('movies.partials.card', ['movie' => $movie])
                        @endforeach
                    </div>
                </section>
            @endif
        @endif

        @if($movies->isEmpty())
            <div class="empty-state flex flex-col items-center justify-center text-center">
                <svg class="mb-4 h-24 w-24 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
                </svg>
                <h3 class="mb-2 text-2xl font-bold text-gray-300">Nenhum {{ $typeFilter === 'movies' ? 'filme' : ($typeFilter === 'series' ? 'série' : 'item') }} ainda</h3>
                <p class="mb-6 text-gray-500">Comece adicionando {{ $typeFilter === 'movies' ? 'filmes' : ($typeFilter === 'series' ? 'séries' : 'filmes e séries') }} à sua lista</p>
                <a href="{{ route('movies.create') }}"
                    class="btn-primary inline-flex items-center gap-2 rounded px-6 py-3 font-bold">
                    Adicionar {{ $typeFilter === 'movies' ? 'Filme' : ($typeFilter === 'series' ? 'Série' : 'Título') }}
                </a>
            </div>
        @endif
    </main>

    <!-- Footer -->
    <footer class="border-t border-gray-800 bg-black py-8">
        <div class="container-netflix text-center">
            <p class="text-sm text-gray-500">Minha Lista de Filmes & Séries • Feito com Laravel</p>
        </div>
    </footer>
</body>

</html>
Í
