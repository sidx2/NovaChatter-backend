<?php

use App\Http\Controllers\TweetController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FollwerController;
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

Route::get("/tweets", [TweetController::class, "index"]);
Route::get("tweets/search/{q}", [TweetController::class, 'search']);

Route::get("/users", [AuthController::class, 'users']);
Route::post("/signup", [AuthController::class, 'signup']);
Route::post("/login", [AuthController::class, 'login']);

Route::post("/isfollower", [FollwerController::class, 'isFollower']);
Route::post("/follow", [FollwerController::class, 'follow']);
Route::post("/unfollow", [FollwerController::class, 'unfollow']);
Route::get("/followcount/{username}", [FollwerController::class, 'getFollowCount']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(["middleware" => ['auth:sanctum']], function() {
    Route::post("/tweets", [TweetController::class, 'store']); 
    Route::put("/tweets/{id}", [TweetController::class, 'update']);
    Route::delete("/tweets/{id}", [TweetController::class, 'destroy']);
    Route::post("/logout", [AuthController::class, 'logout']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
