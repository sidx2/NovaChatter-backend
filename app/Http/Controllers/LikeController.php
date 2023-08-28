<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\User;
use App\Models\Tweet;
use App\Models\Like;

class LikeController extends Controller
{
    //
    /**
     * Remove the specified resource from storage.
     * @param str $tweet_id
     * @return \Illuminate\Http\Response
     */
    public function getLikes($tweet_id) {
        $likes = Like::where("tweet_id", $tweet_id) -> get();
        return response([
            "likes" => $likes
        ], 200);
    }

      /**
     * Remove the specified resource from storage.
     * @param str $tweet_id
     * @return \Illuminate\Http\Response
     */
    public function getLikesCount($tweet_id) {
        $likesCount = Like::where("tweet_id", $tweet_id) -> select($tweet_id) -> get() -> count();
        return response([
            "likes" => $likesCount
        ],200);
    }

    public function like(Request $request) {
        $fields = $request -> validate([
            "tweet_id" => "required|integer",
            "username" => "required|string"
        ]);

        $tweet = Tweet::where("id", $fields["tweet_id"]) -> first();
        $user = User::where("email", $fields["username"]) -> first();

        if (!$tweet) {
            return response(["message", "The tweet does not exist"], 404);
        }

        if (!$user) {
            return response(["message", "The user does not exist"], 404);
        }

        $alreadyLiked = Like::where("tweet_id", $fields["tweet_id"]) -> where("username", $fields["username"]) -> first();

        if ($alreadyLiked) {
            return response(["message" => "You have already liked this tweet"], 400);
        }

        $liked = Like::create([
            "tweet_id" => $fields["tweet_id"],
            "username" => $fields["username"]
        ]);

        return response([
            "liked" => $liked
        ], 201);
    }

    public function unlike(Request $request) {
        $fields = $request -> validate([
            "tweet_id" => "required|integer",
            "username" => "required|string"
        ]);
        
        $tweet = Tweet::where("id", $fields["tweet_id"]) -> first();
        $user = User::where("email", $fields["username"]) -> first();

        if (!$tweet) {
            return response(["message", "The tweet does not exist"], 404);
        }

        if (!$user) {
            return response(["message", "The user does not exist"], 404);
        }

        $alreadyLiked = Like::where("tweet_id", $fields["tweet_id"]) -> where("username", $fields["username"]) -> first();

        if (!$alreadyLiked) {
            return response(["message" => "You have not liked this tweet"], 400);
        }

        $alreadyLiked -> delete();

        return response([
            "message" => "You unliked the tweet"        
        ], 201);
    }
}
