<?php

use Faker\Generator as Faker;

$factory->define(App\Image::class, function (Faker $faker) {
    return [
        'image_url' => $faker->imageUrl()
    ];
});
