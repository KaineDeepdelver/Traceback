<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;

Route::get('/', [SearchController::class, 'index']);
Route::post('/search', [SearchController::class, 'search']);