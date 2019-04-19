<?php

use Faker\Generator as Faker;

$factory->define(App\Models\OauthClient::class, function (Faker $faker) {
    return [
'name'=>$faker->name,
'secret'=> Str::random(10),
'redirect'=>'',
'personal_access_client'=>0,
'password_client'=>0,
'revoked'=>0,
    ];
});
