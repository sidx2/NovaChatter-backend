<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Follower;
use App\Models\User;

class FollwerController extends Controller
{
    //
    public function isFollower(Request $request) {
        $fields = $request -> validate([
            "follower_username" => "required|string",
            "followee_username" => "required|string"
        ]);

        $isFollower = Follower::where("user", $fields["follower_username"]) -> where("follows", $fields["followee_username"]) -> first();

        if ($isFollower) {
            return response([
                "isFollower" => true,
                "message" => $fields["follower_username"]." follows ".$fields["followee_username"]
            ], 200);
        } else {
            return response([
                "isFollower" => false,
                "message" => $fields["follower_username"]." does not follow ".$fields["followee_username"]
            ], 200);
        }

        return "something went wrong";
    }

    public function follow(Request $request) {
        $fields = $request -> validate([
            "follower_username" => "required|string",
            "followee_username" => "required|string"
        ]);

        $follower = User::where("email", $fields["follower_username"]) -> first();
        $followee = User::where("email", $fields["followee_username"]) -> first();

        if (!$follower) {
            return response(["message" => "follower does not exist"]);
        }
        if (!$followee) {
            return response(["message" => "followee does not exist"]);
        }

        $alreadyFollower = Follower::where("user", $fields["follower_username"]) -> where("follows", $fields["followee_username"]) -> first();

        if ($alreadyFollower) {
            return $follower["email"]." Already follows ".$followee["email"];
        }

        $record = Follower::create([
            "user" => $fields["follower_username"],
            "follows" => $fields["followee_username"]
        ]);

        $response = [
            "record" => $record
        ];

        return response($response, 201);
    }

    public function unfollow(Request $request) {
        $fields = $request -> validate([
            "unfollower_username" => "required|string",
            "unfollowee_username" => "required|string"
        ]);

        $follower = User::where("email", $fields["unfollower_username"]) -> first();
        $followee = User::where("email", $fields["unfollowee_username"]) -> first();

        if (!$follower) {
            return response(["message" => "unfollower does not exist"]);
        }
        if (!$followee) {
            return response(["message" => "unfollowee does not exist"]);
        }

        $alreadyFollower = Follower::where("user", $fields["unfollower_username"]) -> where("follows", $fields["unfollowee_username"]) -> first();

        if (!$alreadyFollower) {
            return $follower["email"]." Does not follow ".$followee["email"];
        }

        $alreadyFollower -> delete();


        return response($follower["email"]." unfollowed ".$followee["email"], 201);
    }

    public function getFollowers() {

    }

    /**
     * Remove the specified resource from storage.
     * @param str $username
     * @return \Illuminate\Http\Response
     */
    public function getFollowCount(string $username) {
        $Followercount = Follower::where("follows", $username) -> select($username) -> get() -> count();
        $Followeecount = Follower::where("user", $username) -> select($username) -> get() -> count();

        return response([
            "followers" => $Followercount,
            "follows" => $Followeecount
        ],200);
    }
}
