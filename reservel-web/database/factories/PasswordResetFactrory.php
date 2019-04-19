<?php

use Faker\Generator as Faker;

$factory->define(App\Models\PasswordReset::class, function (Faker $faker) {
    return [
        'email' => $faker->unique()->safeEmail,
        'token'=> Str::random(10),
    ];
});
