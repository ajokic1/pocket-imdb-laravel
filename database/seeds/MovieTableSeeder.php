<?php

use App\Image;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Movie;

class MovieTableSeeder extends Seeder
{
    public function run()
    {
        factory(Movie::class, 10)->create()->each(function($movie) {
            factory(Image::class, 5)->create(['movie_id' => $movie->id]);
        });
    }
}
