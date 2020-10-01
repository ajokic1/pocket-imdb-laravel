<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Movie extends Model
{
    protected $appends = ['likes', 'dislikes', 'like_value', 'watched', 'in_watchlist'];

    public static function search(Request $request)
    {
        $search = strtolower($request->search);
        $genre_id = $request->genre_id;
        $query = Movie::select();
        if ($genre_id) $query = $query->where('genre_id', $genre_id);
        if ($search) $query = $query->whereRaw("lower(title) like (?)", ["%$search%"]);

        return $query;
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function getDislikesAttribute()
    {
        return $this->liked_by()->where('value', '=', -1)->count();
    }

    public function liked_by()
    {
        return $this->belongsToMany(User::class, 'likes')
            ->using(Like::class)
            ->withPivot([
                'value'
            ]);
    }

    public function getLikesAttribute()
    {
        return $this->liked_by()->where('value', '=', 1)->count();
    }

    public function getWatchedAttribute()
    {
        $user = $this->watchlisted_by()->where('user_id', auth()->user()->id)->first();
        return $user ? $user->pivot->watched : false;
    }

    public function watchlisted_by()
    {
        return $this->belongsToMany(User::class, 'watchlists')
            ->withPivot([
                'watched'
            ]);
    }

    public function getLikeValueAttribute()
    {
        $like = Like::where('movie_id', $this->id)
            ->where('user_id', auth()->user()->id)
            ->first();
        return $like ? $like->value : 0;
    }

    public function getInWatchlistAttribute()
    {
        return $this->watchlisted_by()->where('user_id', auth()->user()->id)->exists();

    }
}
