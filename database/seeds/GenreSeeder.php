<?php

use App\Genre;
use Illuminate\Database\Seeder;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $genres = [
            'Action',
            'Sci-fi',
            'Comedy',
            'Drama',
            'Fantasy',
            'Horror',
            'Mystery',
            'Romance',
            'Thriller',
            'Western',
        ];

        foreach ($genres as $genre) {
            Genre::create(['name' => $genre]);
        }

    }
}
