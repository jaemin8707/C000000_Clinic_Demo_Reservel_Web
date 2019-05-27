<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Closed::class, function (Faker $faker) {
    return [
			'closed_day' => date('Y-m-d'),
			'closed_type' => ['1', '2', '3'],
    ];
});
