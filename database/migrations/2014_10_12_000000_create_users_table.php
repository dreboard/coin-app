<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('users');
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('is_admin')->default(0);
            $table->timestamp('last_seen')->nullable();
            $table->boolean('profile_visibility')->default('1');
            $table->timestamp('banned_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        // Known users for testing
        DB::table('users')->insert([
            'name' => 'dreboard',
            'email' => 'dre.board@gmail.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_admin' => 1,
            'created_at' => '2021-11-01 02:17:51.000000'
        ]);
        DB::table('users')->insert([
            'name' => 'defaultUser',
            'email' => 'defaultUser@gmail.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_admin' => 0,
            'created_at' => now()
        ]);
        User::factory()
            ->count(3)
            ->create();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
