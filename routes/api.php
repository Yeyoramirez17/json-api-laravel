<?php

use App\Http\Controllers\Api\ArticleAuthorController;
use App\Http\Controllers\Api\ArticleCategoryController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Middleware\ValidateJsonApiDocument;
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

    Route::apiResource('authors', AuthorController::class)
        ->only('index', 'show');
});

Route::get('articles/{article}/relationships/category', [ArticleCategoryController::class, 'index'])
    ->name('api.v1.articles.relationships.category');

Route::patch('articles/{article}/relationships/category', [ArticleCategoryController::class, 'update'])
    ->name('api.v1.articles.relationships.category');

Route::get('articles/{article}/category', [ArticleCategoryController::class, 'show'])
    ->name('api.v1.articles.category');

Route::get('articles/{article}/relationships/author', [ArticleAuthorController::class, 'index'])
    ->name('api.v1.articles.relationships.author');

Route::patch('articles/{article}/relationships/author', [
    ArticleAuthorController::class, 'update'
])->name('api.v1.articles.relationships.author');

// Route::post('articles/{article}/relationships/author', [
//     ArticleAuthorController::class, 'destroy'
// ])->name('api.v1.articles.relationships.author');

// Route::delete('articles/{article}/relationships/author', [
//     ArticleAuthorController::class, 'destroy'
// ])->name('api.v1.articles.relationships.author');

Route::get('articles/{article}/author', [ArticleAuthorController::class, 'show'])
    ->name('api.v1.articles.author');

Route::withoutMiddleware(ValidateJsonApiDocument::class)
    ->post('login', LoginController::class)
    ->name('api.v1.login');
