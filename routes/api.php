<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GameController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('players', [UserController::class, 'store']);
Route::put('players/{id}', [UserController::class, 'update']);
Route::post('players/{id}/games', [GameController::class, 'store']);
Route::delete('players/{id}/games', [GameController::class, 'destroy']);
Route::get('players', [UserController::class, 'index']);
Route::get('players/{id}/games', [GameController::class, 'index']);
Route::get('players/ranking', [UserController::class, 'ranking']);
Route::get('players/ranking/loser', [UserController::class, 'loser']);
Route::get('players/ranking/winner', [UserController::class, 'winner']);

?>