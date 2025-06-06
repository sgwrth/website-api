<?php

use App\Http\Controllers\AppUserController;
use App\Http\Controllers\PostController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/appuser', [AppUserController::class, 'findAll'])->middleware('auth:sanctum');

Route::post('/register', [AppUserController::class, 'register']);

Route::post('/login', [AppUserController::class, 'login']);

Route::get('/unauthorized', function (Request $request) {
    return response()->json(['message' => 'unauthorized']);
})->name('unauthorized'); // named the route to make it available to 'route(<name of route>)' redirection

Route::get('/post', [PostController::class, 'getAllPosts'])->middleware('auth:sanctum');

Route::post('/post', [PostController::class, 'createPost'])->middleware('auth:sanctum');

Route::put('/post', [PostController::class, 'updatePost'])->middleware('auth:sanctum');
