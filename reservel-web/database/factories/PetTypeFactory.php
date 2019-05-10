<?php

use Faker\Generator as Faker;

// 予約モデルファクトリー
$factory->define(App\Models\PetType::class, function (Faker $faker) {
    return [
        'pet_type'           => $faker->randomElement([1, 2, 3]) ,
    ];
});
