<?php

namespace App\Http\Controllers\Api;

use App\Like;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Movie;
use phpDocumentor\Reflection\DocBlock\Tags\Author;

class MovieController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Movie[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index(Request $request)
    {
        return $this->search($request)->paginate(10);
    }

    private function search(Request $request) {
        $search = strtolower($request->search);

        return Movie::whereRaw("lower(title) like (?)", ["%$search%"]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function like(Movie $movie)
    {
        $this->setLike($movie, auth()->user(), 1);
        return $movie;
    }

    public function dislike(Movie $movie)
    {
        $this->setLike($movie, auth()->user(), -1);
        return $movie;
    }

    public function unlike(Movie $movie)
    {
        $this->setLike($movie, auth()->user(), 0);
        return $movie;
    }

    private function setLike(Movie $movie, User $user, $value) {
        $like = Like::where('user_id', $user->id)->where('movie_id', $movie->id)->first();
        if (!$like) $like = Like::make([
            'user_id' => $user->id,
            'movie_id' => $movie->id
        ]);
        $like->value = $value;
        $like->save();
    }
}
