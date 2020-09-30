<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Movie;
use Illuminate\Http\Request;

class WatchlistController extends Controller
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

    public function get() {
        return auth()->user()->watchlist()->get();
    }

    /**
     * @param Movie $movie
     */
    public function add(Movie $movie)
    {
        $watchlist = auth()->user()->watchlist();
        $watchlist->syncWithoutDetaching($movie);

        return response($watchlist->get());
    }

    /**
     * @param Movie $movie
     */
    public function remove(Movie $movie)
    {
        $watchlist = auth()->user()->watchlist();
        $watchlist->detach($movie->id);

        return response($watchlist->get());
    }

    /**
     * @param Movie $movie
     * @param $watched
     */
    public function setWatched(Movie $movie, $watched)
    {
        $watchlist = auth()->user()->watchlist();
        $watchlist->updateExistingPivot($movie->id, ['watched' => $watched]);

        return response($watchlist->get());
    }
}
