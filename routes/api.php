<?php

use App\Http\Controllers\Api\ArticleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::get('articles', [ArticleController::class, 'index'])
//     ->name('api.v1.articles.index');

// Route::get('articles/{article}', [ArticleController::class, 'show'])
//     ->name('api.v1.articles.show');

// Route::post('articles', [ArticleController::class, 'store'])
//     ->name('api.v1.articles.store');

// Route::patch('articles/{article}', [ArticleController::class, 'update'])
//     ->name('api.v1.articles.update');

// Route::delete('articles/{article}', [ArticleController::class, 'destroy'])
//     ->name('api.v1.articles.destroy');

Route::apiResource('articles', ArticleController::class)
    ->names('api.v1.articles');
