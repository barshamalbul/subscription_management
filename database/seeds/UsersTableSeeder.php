<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $dt = Carbon::now();
        // echo $dt->toDateString();
        DB::table('users')->insert([
            'name' => 'user',
            'email' => 'user.@gmail.com',
            'password' =>bcrypt('user@123'),
            'created_at' =>Carbon::now()->toDateString(),
            'updated_at' =>Carbon::now(),
        ]);
    }
}
