<?php

namespace App\Http\Controllers\Api;

use App\Comment;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreComment;
use App\Movie;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Comment::class, 'comment');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index(Movie $movie)
    {
        return $movie->comments()->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreComment $request
     * @param Movie $movie
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreComment $request, Movie $movie)
    {
        $validated = $request->validated();
        $validated['user_id'] = auth()->user()->id;
        $validated['movie_id'] = $movie->id;
        return response()->json(Comment::create($validated), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return Comment
     */
    public function show(Comment $comment)
    {
        return $comment;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(StoreComment $request, Comment $comment)
    {
        $comment->update($request->validated());
        return response()->json($comment, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();
        return response()->json([], 200);
    }
}
