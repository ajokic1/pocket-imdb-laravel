<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    public $timestamps = false;

    public function movies()
    {
        return $this->belongsToMany(Movie::class);
    }

    public static function getIdsFromNames($names)
    {
        return Genre::whereIn('name', $names)->pluck('id');
    }
}
