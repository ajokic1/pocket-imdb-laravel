<?php

use Faker\Generator as Faker;

$factory->define(App\Comment::class, function (Faker $faker) {
    return [
        'content' => $faker->text(500),
        'user_id' => $faker->numberBetween(0,10),
        'movie_id' => $faker->numberBetween(0,10)
    ];
});
