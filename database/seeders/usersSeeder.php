<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class usersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table("users")->insert(
            array(
                'cc'     => '123123123',
                'name'  => 'Admin Aristizabal',
                'email' => 'admin@gmail.com',
                'phone' => '293847',
                'password' => Hash::make('123'),
                'rol' => 1
            )
      );
    }
}
