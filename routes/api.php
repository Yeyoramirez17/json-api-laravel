<?php

use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\CategoryController;
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

Route::name('api.v1.')->group(function () {
    Route::apiResource('articles', ArticleController::class);

    Route::apiResource('categories', CategoryController::class)
        ->only('index', 'show');
});

Route::get('articles/{article}/relationships/category', fn () => 'TODO')
    ->name('api.v1.articles.relationships.category');

Route::get('articles/{article}/category', fn () => 'TODO')
    ->name('api.v1.articles.category');
