<?php

use Faker\Generator as Faker;

// 予約モデルファクトリー
$factory->define(App\Models\Purpose::class, function (Faker $faker) {
    return [
        'purpose'           => $faker->randomElement([1, 2, 3, 99]) ,
    ];
});
