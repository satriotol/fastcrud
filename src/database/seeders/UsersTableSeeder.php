<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('users')->delete();

        \DB::table('users')->insert(array(
            0 =>
            array(
                'id' => 1,
                'name' => 'satriotol69@gmail.com',
                'uuid' => 'otolhandsome',
                'email' => 'satriotol69@gmail.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$bc6SgNTLZI2p5DLmX4akouazsxrqItOLn2UveOQVbYN4duPY89Jiu',
                'remember_token' => NULL,
                'created_at' => '2023-01-12 21:47:44',
                'updated_at' => '2023-01-12 21:47:44',
            ),
        ));
    }
}
