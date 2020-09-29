<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $appends = ['likes', 'dislikes', 'like_value'];

    public function getDislikesAttribute()
    {
        return $this->liked_by()->where('value', '=', -1)->count();
    }

    public function getLikesAttribute()
    {
        return $this->liked_by()->where('value', '=', 1)->count();
    }

    public function getLikeValueAttribute()
    {
        return Like::where('movie_id', $this->id)
            ->where('user_id', auth()->user()->id)
            ->first()
            ->value;
    }

    public function liked_by()
    {
        return $this->belongsToMany(User::class, 'likes')
            ->using(Like::class)
            ->withPivot([
                'value'
            ]);
    }
}
