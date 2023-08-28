<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use Illuminate\Http\Request;

class TweetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return Tweet::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        return Tweet::create($request -> all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        return Tweet::find($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $tweet = Tweet::find($id);
        $tweet -> update($request -> all());
        return $tweet;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        return Tweet::destroy($id);
    }
    
    /**
     * Remove the specified resource from storage.
     * @param str $q
     * @return \Illuminate\Http\Response
     */
    
    public function search(string $q)
    {
        //
        return Tweet::where('name', 'like', '%'.$q.'%') -> get();
    }
}
