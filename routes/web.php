<?php

use App\Http\Controllers\MovieController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MovieController::class, 'index'])->name('movies.index');
Route::get('/movies/create', [MovieController::class, 'create'])->name('movies.create');
Route::get('/movies/search', [MovieController::class, 'search'])->name('movies.search');
Route::post('/movies', [MovieController::class, 'store'])->name('movies.store');
Route::get('/movies/{movie}', [MovieController::class, 'show'])->name('movies.show');
Route::patch('/movies/{movie}/toggle-status', [MovieController::class, 'toggleStatus'])->name('movies.toggle-status');
Route::delete('/movies/{movie}', [MovieController::class, 'destroy'])->name('movies.destroy');
