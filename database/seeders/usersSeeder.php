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
                'cc'     => '01010101',
                'name'  => 'SOPORTE ADMIN',
                'email' => 'soporte@zabal.com',
                'phone' => '010101',
                'password' => Hash::make('soporte007*'),
                'rol' => 1
            ),
            array(
                'cc'     => '01010101',
                'name'  => 'SERGIO GIRALDO ADMIN',
                'email' => 'adminsergio@zabal.com',
                'phone' => '01010101',
                'password' => Hash::make('SEgiAD654*'),
                'rol' => 1
            ),
            array(
                'cc'     => '01010101',
                'name'  => 'MANUELA ARISTIZABAL ADMIN',
                'email' => 'adminmanuela@zabal.com',
                'phone' => '010101',
                'password' => Hash::make('MAarAD654*'),
                'rol' => 1
            ),
            array(
                'cc'     => '01010101',
                'name'  => 'MAFE ADMIN',
                'email' => 'adminmafe@zabal.com',
                'phone' => '010101',
                'password' => Hash::make('MAmaAD654*'),
                'rol' => 1
            ),
            array(
                'cc'     => '01010101',
                'name'  => 'CAMILO VENDEDOR',
                'email' => 'camilovendedor@zabal.com',
                'phone' => '010101',
                'password' => Hash::make('CAcaVE098*'),
                'rol' => 2
            ),
            array(
                'cc'     => '01010101',
                'name'  => 'ISAAC VENDEDOR',
                'email' => 'isaacvendedor@zabal.com',
                'phone' => '010101',
                'password' => Hash::make('ISisVE876*'),
                'rol' => 2
            ),
      );
    }
}