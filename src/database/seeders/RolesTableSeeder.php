<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('roles')->delete();
        
        \DB::table('roles')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'SUPERADMIN',
                'guard_name' => 'web',
                'created_at' => '2023-01-12 21:57:27',
                'updated_at' => '2023-01-12 21:57:27',
            ),
        ));
        
        
    }
}