<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable=['content', 'movie_id', 'user_id'];

    public function movie() {
        return $this->belongsTo(Movie::class);
    }
}
