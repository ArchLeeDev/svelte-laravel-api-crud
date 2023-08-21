<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SvelteController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('svelte/posts', [SvelteController::class, 'posts']);
Route::delete('svelte/posts/{id}', [SvelteController::class, 'delete_posts']);
Route::get('svelte/posts', [SvelteController::class, 'get_posts']);
Route::get('svelte/posts/{id}', [SvelteController::class, 'get_posts_by_id']);
Route::post('svelte/update/{id}', [SvelteController::class, 'update_posts']);
