<?php

namespace App\Http\Controllers\Api;

use App\Genre;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMovie;
use App\Image;
use App\Like;
use App\Movie;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
     * @return Movie[]|Collection
     */
    public function index(Request $request)
    {
        return Movie::search($request)->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreMovie $request
     * @return JsonResponse
     */
    public function store(StoreMovie $request)
    {
        $movie = Movie::create($request->validated());
        $movie->genres()->sync(Genre::getIdsFromNames($request->genres));
        Image::storeMovieImage($movie, $request);

        return response()->json($movie, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Movie $movie
     * @return Movie
     */
    public function show(Movie $movie)
    {
        $movie->visits++;
        $movie->save();
        $movie['genre_names'] = $movie->genre_names;
        $movie['related'] = $movie->related;

        return $movie;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreMovie $request
     * @param Movie $movie
     * @return JsonResponse
     */
    public function update(StoreMovie $request, Movie $movie)
    {
        $movie->update($request->validated());
        $movie->genres()->sync($request->genre_ids);

        return response()->json($movie, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(Movie $movie)
    {
        $movie->delete();

        return response()->json([], 200);
    }

    public function popular()
    {
        return Movie::select(['id', 'title'])
            ->withCount('liked_by')
            ->orderBy('liked_by_count', 'desc')
            ->take(10)
            ->get()
            ->makeHidden(['dislikes', 'like_value', 'watched', 'in_watchlist','liked_by_count']);
    }

    public function like(Movie $movie)
    {
        $this->setLike($movie, auth()->user(), 1);

        return $movie;
    }

    private function setLike(Movie $movie, User $user, $value)
    {
        $like = Like::where('user_id', $user->id)->where('movie_id', $movie->id)->first();
        if (!$like) $like = Like::make([
            'user_id' => $user->id,
            'movie_id' => $movie->id
        ]);
        $like->value = $value;
        $like->save();
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
}
