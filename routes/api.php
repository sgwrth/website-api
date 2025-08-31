<?php

use App\Http\Controllers\AppUserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RedisController;
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

Route::post('/register', [AppUserController::class, 'register']);

Route::post('/login', [AppUserController::class, 'login']);

Route::get('/appusers', [AppUserController::class, 'findAll'])->middleware('auth:sanctum');

Route::get('/me', [AppUserController::class, 'getMe'])->middleware('auth:sanctum');

Route::get('/unauthorized', function (Request $request) {
    return response()->json(['message' => 'unauthorized']);
})->name('unauthorized'); // named the route to make it available to 'route(<name of route>)' redirection

Route::get('/posts', [PostController::class, 'getAllPosts'])->middleware('auth:sanctum');

Route::post('/posts', [PostController::class, 'createPost'])->middleware(['auth:sanctum', 'role:user,admin']);

Route::put('/posts/{id}', [PostController::class, 'updatePost'])->middleware('auth:sanctum', 'role:user,admin');

Route::get('/posts/{id}', [PostController::class, 'getPostById'])->middleware('auth:sanctum');

Route::delete('/posts/{id}', [PostController::class, 'deletePostById'])->middleware('auth:sanctum', 'role:user,admin');

Route::post('/redis', [RedisController::class, 'sendMessage']);
