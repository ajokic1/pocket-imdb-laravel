<?php

use App\Genre;
use Illuminate\Database\Seeder;

class GenreTableSeeder extends Seeder
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
        $data = [];
        foreach ($genres as $genre) {
            array_push($data, ['name' => $genre]);
        }
        Genre::insert($data);
    }
}
