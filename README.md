# Movie Watchlist (Laravel)

Simple movie tracker built with Laravel.

## Features

- Add movies manually or fetch metadata from OMDb
- Status tracking: `pending` / `watched`
- Dashboard stats: total, pending, watched, progress
- Live title suggestions on add form

## Tech

- Laravel 12
- SQLite (default)
- Blade + Tailwind + Vite
- OMDb API

## Quick start

1. Install dependencies:
   - `composer install`
   - `npm install`

2. Create env:
   - `cp .env.example .env`
   - `php artisan key:generate`

3. Create SQLite file:
   - `mkdir -p database`
   - `touch database/database.sqlite`

4. Set env values in `.env`:
   - `DB_CONNECTION=sqlite`
   - `DB_DATABASE=database/database.sqlite`
   - `SESSION_DRIVER=file`
   - `QUEUE_CONNECTION=sync`
   - `CACHE_STORE=file`
   - `OMDB_API_KEY=your_key_here`

5. Run migrations:
   - `php artisan migrate`

6. Start app:
   - `composer run dev`

App URL: `http://127.0.0.1:8000`

## Main files

- Routes: `routes/web.php`
- Controller: `app/Http/Controllers/MovieController.php`
- Model: `app/Models/Movie.php`
- Migration: `database/migrations/2026_02_12_000003_create_movies_table.php`
- Views:
  - `resources/views/movies/index.blade.php`
  - `resources/views/movies/create.blade.php`

## Deployment note

This is a Laravel server app.  
**GitHub Pages cannot run PHP** (only static files).  
Use Render, Railway, Fly.io, or a VPS for deployment.

## Security note

Do not commit `.env`.  
If an API key was exposed, rotate it in OMDb and set a new value in `.env`.
