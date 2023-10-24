<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RoleHasPermissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('role_has_permissions')->delete();
        
        \DB::table('role_has_permissions')->insert(array (
            0 => 
            array (
                'permission_id' => 9,
                'role_id' => 2,
            ),
            1 => 
            array (
                'permission_id' => 10,
                'role_id' => 2,
            ),
            2 => 
            array (
                'permission_id' => 11,
                'role_id' => 2,
            ),
            3 => 
            array (
                'permission_id' => 12,
                'role_id' => 2,
            ),
            4 => 
            array (
                'permission_id' => 13,
                'role_id' => 2,
            ),
            5 => 
            array (
                'permission_id' => 14,
                'role_id' => 2,
            ),
            6 => 
            array (
                'permission_id' => 15,
                'role_id' => 2,
            ),
            7 => 
            array (
                'permission_id' => 16,
                'role_id' => 2,
            ),
            8 => 
            array (
                'permission_id' => 17,
                'role_id' => 2,
            ),
            9 => 
            array (
                'permission_id' => 18,
                'role_id' => 2,
            ),
            10 => 
            array (
                'permission_id' => 20,
                'role_id' => 2,
            ),
            11 => 
            array (
                'permission_id' => 20,
                'role_id' => 4,
            ),
            12 => 
            array (
                'permission_id' => 21,
                'role_id' => 2,
            ),
            13 => 
            array (
                'permission_id' => 21,
                'role_id' => 4,
            ),
            14 => 
            array (
                'permission_id' => 22,
                'role_id' => 2,
            ),
            15 => 
            array (
                'permission_id' => 22,
                'role_id' => 4,
            ),
            16 => 
            array (
                'permission_id' => 23,
                'role_id' => 2,
            ),
            17 => 
            array (
                'permission_id' => 23,
                'role_id' => 4,
            ),
            18 => 
            array (
                'permission_id' => 24,
                'role_id' => 2,
            ),
            19 => 
            array (
                'permission_id' => 24,
                'role_id' => 4,
            ),
            20 => 
            array (
                'permission_id' => 25,
                'role_id' => 2,
            ),
            21 => 
            array (
                'permission_id' => 26,
                'role_id' => 2,
            ),
            22 => 
            array (
                'permission_id' => 27,
                'role_id' => 2,
            ),
            23 => 
            array (
                'permission_id' => 28,
                'role_id' => 2,
            ),
            24 => 
            array (
                'permission_id' => 29,
                'role_id' => 2,
            ),
            25 => 
            array (
                'permission_id' => 30,
                'role_id' => 2,
            ),
            26 => 
            array (
                'permission_id' => 31,
                'role_id' => 2,
            ),
            27 => 
            array (
                'permission_id' => 32,
                'role_id' => 2,
            ),
            28 => 
            array (
                'permission_id' => 33,
                'role_id' => 2,
            ),
            29 => 
            array (
                'permission_id' => 34,
                'role_id' => 2,
            ),
            30 => 
            array (
                'permission_id' => 35,
                'role_id' => 2,
            ),
            31 => 
            array (
                'permission_id' => 36,
                'role_id' => 2,
            ),
            32 => 
            array (
                'permission_id' => 37,
                'role_id' => 2,
            ),
        ));
        
        
    }
}