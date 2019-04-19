<?php

use Faker\Generator as Faker;

// 設定モデルファクトリー
$factory->define(App\Models\Setting::class, function (Faker $faker) {
    return [
        'code' => $faker->word,
        'value' => $faker->word,
    ];
});
