<?php

use App\Http\Controllers\TweetController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FollwerController;
use App\Http\Controllers\LikeController;
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

// tweet routes
Route::get("/tweets", [TweetController::class, "index"]);
Route::get("tweets/search/{q}", [TweetController::class, 'search']);

// auth routes
Route::get("/users", [AuthController::class, 'users']);
Route::post("/signup", [AuthController::class, 'signup']);
Route::post("/login", [AuthController::class, 'login']);
Route::get("/search/{q}", [AuthController::class, 'search']);
Route::get("/show/{username}", [AuthController::class, 'show']);

// follower routes
Route::get("/getfollowers/{username}", [FollwerController::class, 'getFollowers']);
Route::post("/isfollower", [FollwerController::class, 'isFollower']);
Route::get("/followcount/{username}", [FollwerController::class, 'getFollowCount']);

// like routes
Route::get("/getlikes/{tweet_id}", [LikeController::class, 'getLikes']);
Route::get("/getlikescount/{tweet_id}", [LikeController::class, 'getLikesCount']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(["middleware" => ['auth:sanctum']], function() {
    Route::get("/userinfo", [AuthController::class, 'userinfo']);
    Route::post("/logout", [AuthController::class, 'logout']);
    // tweets
    Route::post("/tweets", [TweetController::class, 'store']); 
    Route::put("/tweets/{id}", [TweetController::class, 'update']);
    Route::delete("/tweets/{id}", [TweetController::class, 'destroy']);

    // likes
    Route::post("/like", [LikeController::class, 'like']);
    Route::post("/unlike", [LikeController::class, 'unlike']);

    // follower
    Route::post("/follow", [FollwerController::class, 'follow']);
    Route::post("/unfollow", [FollwerController::class, 'unfollow']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
