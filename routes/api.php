<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GameApiController;

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


Route::prefix("games")->group(function () {
    Route::post("/start", [GameApiController::class, "store"]);
    Route::put("/end", [GameApiController::class, "update"]);
    Route::put("/score", [GameApiController::class, "score"]);
    Route::get("/leader-board", [GameApiController::class, "leaderBoard"]);
});
