<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'defaultUser'.Str::random(5),
            'email' => Str::random(10).'@gmail.com',
            'password' => Hash::make('password'),
            'is_admin' => 0,
            'account_status' => Arr::random(['active','suspended']),
            'remember_token' => Str::random(10),
            'email_verified_at' => now(),
        ]);
    }
}
