<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        App\Models\User::create([
            'name' => 'develop_user',
            'email' => 'j-lee@it-craft.co.jp',
            'password' => Hash::make('password'), // この場合、「my_secure_password」でログインできる
            'remember_token' => str_random(10),
        ]);
    }
}
