<?php

use App\Http\Controllers\{
    CastMemberController,
    CategoryController,
    GenreController,
    VideoController
};
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('upload-file', fn() => Storage::put('content', 'tests'));

Route::apiResource('categories', CategoryController::class);
Route::apiResource('genres', GenreController::class);
Route::apiResource('cast-members', CastMemberController::class);
Route::apiResource('videos', VideoController::class);
