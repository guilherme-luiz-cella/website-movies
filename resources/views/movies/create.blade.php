<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Adicionar Filme • {{ config('app.name') }}</title>
    <meta name="description" content="Adicione um novo filme à sua lista">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#141414] text-white">
    <!-- Header -->
    <header class="border-b border-gray-800 bg-black/50 backdrop-blur-sm">
        <div class="container-netflix flex items-center justify-between py-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('movies.index') }}" 
                   class="flex items-center gap-2 text-gray-400 transition hover:text-white">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Voltar
                </a>
                <h1 class="text-xl font-bold text-red-600 sm:text-2xl">MEUS FILMES</h1>
            </div>
        </div>
    </header>

    <main class="container-netflix py-8 sm:py-12">
        <div class="mx-auto max-w-3xl">
            <!-- Page Title -->
            <div class="mb-8">
                <div class="mb-3 inline-flex items-center gap-2 rounded-full bg-red-600/20 px-4 py-1.5 text-sm font-medium text-red-400">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Novo Filme
                </div>
                <h2 class="mb-2 text-3xl font-bold sm:text-4xl">Adicionar à sua lista</h2>
                <p class="text-gray-400">Digite o título do filme para buscar informações automáticas</p>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="alert-error mb-6 rounded-lg border border-red-600/40 bg-red-600/20 p-4 text-red-200">
                    <div class="mb-2 flex items-center gap-2 font-semibold">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Corrija os seguintes erros:
                    </div>
                    <ul class="ml-7 list-disc space-y-1 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form Card -->
            <div class="form-card overflow-hidden rounded-xl border border-gray-800 bg-zinc-900/50 backdrop-blur-sm">
                <form action="{{ route('movies.store') }}" method="POST" class="p-6 sm:p-8" id="movieForm">
                    @csrf

                    <!-- Type Select -->
                    <div class="mb-6">
                        <label for="type" class="mb-2 block text-sm font-semibold text-gray-300">
                            Tipo
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="type-radio-card relative cursor-pointer rounded-lg border-2 border-gray-700 bg-zinc-950 p-4 transition hover:border-red-600">
                                <input type="radio" name="type" value="movie" class="peer sr-only" checked>
                                <div class="flex flex-col items-center gap-2">
                                    <span class="text-3xl">🎬</span>
                                    <span class="font-semibold text-white">Filme</span>
                                </div>
                                <div class="absolute inset-0 hidden rounded-lg border-2 border-red-600 bg-red-600/10 peer-checked:block"></div>
                            </label>
                            <label class="type-radio-card relative cursor-pointer rounded-lg border-2 border-gray-700 bg-zinc-950 p-4 transition hover:border-purple-600">
                                <input type="radio" name="type" value="series" class="peer sr-only">
                                <div class="flex flex-col items-center gap-2">
                                    <span class="text-3xl">📺</span>
                                    <span class="font-semibold text-white">Série</span>
                                </div>
                                <div class="absolute inset-0 hidden rounded-lg border-2 border-purple-600 bg-purple-600/10 peer-checked:block"></div>
                            </label>
                        </div>
                    </div>

                    <!-- Language Selector -->
                    <div class="mb-6">
                        <label for="language" class="mb-2 block text-sm font-semibold text-gray-300">
                            Idioma de Busca
                        </label>
                        <div class="relative">
                            <select
                                id="language"
                                name="language"
                                class="input-field w-full appearance-none rounded-lg border border-gray-700 bg-zinc-950 px-4 py-3 pr-10 text-white outline-none transition focus:border-red-600 focus:ring-2 focus:ring-red-600/20"
                            >
                                <option value="pt-BR" selected>🇧🇷 Português (Brasil)</option>
                                <option value="en-US">🇺🇸 English (USA)</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                        <p class="mt-2 text-xs text-gray-500">
                            🌍 Selecione o idioma para buscar filmes e séries
                        </p>
                    </div>

                    <!-- Title Input -->
                    <div class="mb-6">
                        <label for="title" class="mb-2 block text-sm font-semibold text-gray-300">
                            Título <span id="titleType">do Filme</span>
                        </label>
                        <div class="relative">
                            <input
                                type="text"
                                id="title"
                                name="title"
                                value="{{ old('title') }}"
                                placeholder="Ex: Interestelar, Matrix, Duna..."
                                required
                                autocomplete="off"
                                class="input-field w-full rounded-lg border border-gray-700 bg-zinc-950 px-4 py-3 text-white outline-none transition focus:border-red-600 focus:ring-2 focus:ring-red-600/20"
                            >
                            
                            <!-- Loading Indicator -->
                            <div id="searchLoader" class="pointer-events-none absolute right-3 top-1/2 hidden -translate-y-1/2">
                                <svg class="h-5 w-5 animate-spin text-red-600" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Suggestions Dropdown -->
                        <div id="suggestions" class="suggestions-container mt-2 hidden overflow-hidden rounded-lg border border-gray-700 bg-zinc-950 shadow-2xl"></div>
                        
                        <p class="mt-2 text-xs text-gray-500">
                            <svg class="mr-1 inline h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Sugestões aparecerão automaticamente enquanto você digita
                        </p>
                    </div>

                    <!-- Series Fields (shown only when series is selected) -->
                    <div id="seriesFields" class="mb-6 hidden space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="seasons" class="mb-2 block text-sm font-semibold text-gray-300">
                                    Temporadas
                                </label>
                                <input
                                    type="number"
                                    id="seasons"
                                    name="seasons"
                                    min="1"
                                    value="{{ old('seasons') }}"
                                    placeholder="Ex: 3"
                                    class="input-field w-full rounded-lg border border-gray-700 bg-zinc-950 px-4 py-3 text-white outline-none transition focus:border-purple-600 focus:ring-2 focus:ring-purple-600/20"
                                >
                            </div>
                            <div>
                                <label for="episodes" class="mb-2 block text-sm font-semibold text-gray-300">
                                    Episódios (Total)
                                </label>
                                <input
                                    type="number"
                                    id="episodes"
                                    name="episodes"
                                    min="1"
                                    value="{{ old('episodes') }}"
                                    placeholder="Ex: 24"
                                    class="input-field w-full rounded-lg border border-gray-700 bg-zinc-950 px-4 py-3 text-white outline-none transition focus:border-purple-600 focus:ring-2 focus:ring-purple-600/20"
                                >
                            </div>
                        </div>
                        <p class="text-xs text-gray-500">
                            💡 Campos opcionais - serão preenchidos automaticamente se disponíveis
                        </p>
                    </div>

                    <!-- Status Select -->
                    <div class="mb-8">
                        <label for="status" class="mb-2 block text-sm font-semibold text-gray-300">
                            Status Inicial
                        </label>
                        <div class="relative">
                            <select
                                id="status"
                                name="status"
                                class="input-field w-full appearance-none rounded-lg border border-gray-700 bg-zinc-950 px-4 py-3 pr-10 text-white outline-none transition focus:border-red-600 focus:ring-2 focus:ring-red-600/20"
                            >
                                <option value="pending" @selected(old('status', 'pending') === 'pending')>
                                    📋 Para Assistir
                                </option>
                                <option value="watching" @selected(old('status') === 'watching')>
                                    ▶️ Assistindo
                                </option>
                                <option value="watched" @selected(old('status') === 'watched')>
                                    ✅ Já Assistido
                                </option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <a href="{{ route('movies.index') }}" 
                           class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-700 px-6 py-3 text-sm font-semibold text-gray-300 transition hover:bg-gray-800">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="btn-primary inline-flex items-center justify-center gap-2 rounded-lg px-6 py-3 text-sm font-semibold">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span id="submitText">Adicionar Filme</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Info Box -->
            <div class="mt-6 rounded-lg border border-blue-600/30 bg-blue-600/10 p-4">
                <div class="flex gap-3">
                    <svg class="h-6 w-6 flex-shrink-0 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="text-sm text-blue-200">
                        <p class="mb-1 font-semibold">Busca Automática</p>
                        <p class="text-blue-300/80">Os dados do filme ou série (pôster, ano, sinopse) são buscados automaticamente enquanto você digita.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
