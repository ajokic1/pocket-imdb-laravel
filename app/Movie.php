<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Movie extends Model
{
    protected $appends = ['genre_ids', 'likes', 'dislikes', 'like_value', 'watched', 'in_watchlist'];

    protected $fillable = ['title', 'description', 'image_url'];

    public static function search(Request $request)
    {
        $search = strtolower($request->search);
        $genre_id = $request->genre_id;
        $query = Movie::select();
        if ($genre_id) $query = $query->whereHas('genres', function (Builder $query) use ($genre_id) {
            $query->where('genres.id', $genre_id);
        });
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

    public function getRelatedAttribute()
    {
        $genre_ids = $this->genre_ids;

        return Movie::select(['movies.id', 'title'])
            ->whereHas('genres', function (Builder $query) use ($genre_ids) {
                $query->whereIn('genres.id', $genre_ids);
            })->where('movies.id', '!=', $this->id)
            ->take(10)
            ->get()
            ->makeHidden(['dislikes', 'like_value', 'watched', 'in_watchlist', 'liked_by_count']);
    }

    public function getGenreIdsAttribute()
    {
        return $this->genres()->pluck('genres.id');
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }

    public function getGenreNamesAttribute()
    {
        return $this->genres()->pluck('name');
    }
}
