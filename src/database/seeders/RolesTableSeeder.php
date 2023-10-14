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
                'id' => 2,
                'name' => 'SUPERADMIN',
                'guard_name' => 'web',
                'created_at' => '2023-10-10 05:26:02',
                'updated_at' => '2023-10-10 05:33:22',
            ),
            1 => 
            array (
                'id' => 4,
                'name' => 'ADMIN',
                'guard_name' => 'web',
                'created_at' => '2023-10-10 05:51:35',
                'updated_at' => '2023-10-10 05:51:35',
            ),
        ));
        
        
    }
}