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

Route::get('articles', [ArticleController::class, 'index'])
    ->name('api.v1.articles.index');

Route::get('articles/{article}', [ArticleController::class, 'show'])
    ->name('api.v1.articles.show');

Route::post('articles', [ArticleController::class, 'create'])
    ->name('api.v1.articles.create');

