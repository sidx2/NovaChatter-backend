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

    public function getUserLikes($username) {
        return Like::where("username", $username) -> get();
    }

    public function like($tweet_id) {
        $tweet = Tweet::where("id", $tweet_id) -> first();

        if (!$tweet) {
            return response(["message" => "The tweet does not exist"], 404);
        }

        $alreadyLiked = Like::where("tweet_id", $tweet_id) -> where("username", auth() -> user() -> email) -> first();

        if ($alreadyLiked) {
            return response(["message" => "You have already liked this tweet"], 400);
        }

        $liked = Like::create([
            "tweet_id" => $tweet_id,
            "username" => auth() -> user() -> email
        ]);

        return response([
            "liked" => $liked
        ], 201);
    }

    public function unlike($tweet_id) {
        $tweet = Tweet::where("id", $tweet_id) -> first();
        $username = auth() -> user() -> email;

        if (!$tweet) {
            return response(["message", "The tweet does not exist"], 404);
        }


        $alreadyLiked = Like::where("tweet_id", $tweet_id) -> where("username", $username) -> first();

        if (!$alreadyLiked) {
            return response(["message" => "You have not liked this tweet"], 400);
        }

        $alreadyLiked -> delete();

        return response([
            "message" => "You unliked the tweet"        
        ], 201);
    }
}
