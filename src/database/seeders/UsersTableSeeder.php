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
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 3,
                'uuid' => '44099e12-ed6b-49a0-a3cb-ab8f9db4ad10',
                'name' => 'Satrio Jati Wicaksono',
                'email' => 'satriotol69@gmail.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$gKFN8VyL9On5tLd/BqoRl.HAvwrK6P57X267nkjkI5X.ZjqYg1kFG',
                'remember_token' => NULL,
                'created_at' => '2023-10-10 01:54:48',
                'updated_at' => '2023-10-13 05:46:36',
            ),
            1 => 
            array (
                'id' => 4,
                'uuid' => '6f945b8b-8233-4003-a52c-3df35ce5435a',
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$PTK2ISFLjilF3VbTF.AnbeBDWd6z32ojtcvKR4zwBTaqaWvLEtKwi',
                'remember_token' => NULL,
                'created_at' => '2023-11-06 01:44:38',
                'updated_at' => '2023-11-06 01:44:38',
            ),
        ));
        
        
    }
}