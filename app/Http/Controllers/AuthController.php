<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class AuthController extends Controller
{
    //

    public function users() {
        return User::all();
    }

    /**
     * Remove the specified resource from storage.
     * @param str $q
     * @return \Illuminate\Http\Response
     */
    public function show($username) {
        return User::where("email", $username) -> first();
    }

    public function signup(Request $request) {
        $fields = $request -> validate([
            "name" => "required|string",
            "username" => "required|string|unique:users,email",
            "password" => "required|string|confirmed"
        ]);

        $user = User::create([
            "name" => $fields["name"],
            "email" => $fields["username"],
            "password" => bcrypt($fields["password"]),
        ]);

        $token = $user -> createToken('secret') -> plainTextToken;

        $response = [
            "user" => $user,
            "token" => $token
        ];

        return response($response, 201);
    }

    public function login(Request $request) {
        $fields = $request -> validate([
            "username" => "required|string",
            "password" => "required|string"
        ]);

        $user = User::where("email", $fields["username"]) -> first();

        if (!$user || !Hash::check($fields["password"], $user -> password)) {
            return response(["message" => "Invalid username or password"], 401);
        }

        $token = $user -> createToken('secret') -> plainTextToken;

        $response = [
            "user" => $user,
            "token" => $token
        ];

        return response($response, 201);
    }

    public function logout() {
        auth() -> user() -> tokens() -> delete();
        return [
            "message" => "loggedout"
        ];
    }

    public function userinfo(Request $request) {
        // return "it works";
        return $request -> user();
    }

    /**
     * Remove the specified resource from storage.
     * @param str $q
     * @return \Illuminate\Http\Response
     */
    public function search($q) {
        return User::where("name", "like", '%'.$q.'%') -> get();
    }
}
