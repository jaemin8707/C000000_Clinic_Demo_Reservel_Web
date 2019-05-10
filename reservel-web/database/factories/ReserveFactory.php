<?php

use Faker\Generator as Faker;

// 予約モデルファクトリー
$factory->define(App\Models\Reserve::class, function (Faker $faker) {
    return [
        'place'           => $faker->randomElement([1, 2,]) ,
        'reception_no'    => $faker->numberBetween(1, 999) ,
        'care_type'       => $faker->randomElement([1, 2,]) ,
        'status'          => $faker->randomElement([-1, 10, 20, 30, 40,]) ,
        'name'            => $faker->name,
        'medical_card_no' => $faker->word,
        'pet_name'        => $faker->word,
        'tel'             => substr($faker->phoneNumber,0,15),
        'email'           => $faker->safeEmail,
        'conditions'      => $faker->paragraph() ,
        'cancel_token'    => str_random(10),
        'call_time'       => date('Y-m-d H:i:s', strtotime('-1 hour', time())),
    ];
});
