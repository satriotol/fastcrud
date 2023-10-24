<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('permissions')->delete();
        
        \DB::table('permissions')->insert(array (
            0 => 
            array (
                'id' => 9,
                'name' => 'permission-index',
                'guard_name' => 'web',
                'created_at' => '2023-10-10 04:16:42',
                'updated_at' => '2023-10-10 04:19:23',
            ),
            1 => 
            array (
                'id' => 10,
                'name' => 'permission-create',
                'guard_name' => 'web',
                'created_at' => '2023-10-10 04:16:42',
                'updated_at' => '2023-10-10 04:16:42',
            ),
            2 => 
            array (
                'id' => 11,
                'name' => 'permission-edit',
                'guard_name' => 'web',
                'created_at' => '2023-10-10 04:16:42',
                'updated_at' => '2023-10-10 04:16:42',
            ),
            3 => 
            array (
                'id' => 12,
                'name' => 'permission-delete',
                'guard_name' => 'web',
                'created_at' => '2023-10-10 04:16:42',
                'updated_at' => '2023-10-10 04:16:42',
            ),
            4 => 
            array (
                'id' => 13,
                'name' => 'permission-show',
                'guard_name' => 'web',
                'created_at' => '2023-10-10 04:16:42',
                'updated_at' => '2023-10-10 04:16:42',
            ),
            5 => 
            array (
                'id' => 14,
                'name' => 'role-index',
                'guard_name' => 'web',
                'created_at' => '2023-10-10 04:34:25',
                'updated_at' => '2023-10-10 04:34:25',
            ),
            6 => 
            array (
                'id' => 15,
                'name' => 'role-create',
                'guard_name' => 'web',
                'created_at' => '2023-10-10 04:34:25',
                'updated_at' => '2023-10-10 04:34:25',
            ),
            7 => 
            array (
                'id' => 16,
                'name' => 'role-edit',
                'guard_name' => 'web',
                'created_at' => '2023-10-10 04:34:25',
                'updated_at' => '2023-10-10 04:34:25',
            ),
            8 => 
            array (
                'id' => 17,
                'name' => 'role-delete',
                'guard_name' => 'web',
                'created_at' => '2023-10-10 04:34:25',
                'updated_at' => '2023-10-10 04:34:25',
            ),
            9 => 
            array (
                'id' => 18,
                'name' => 'role-show',
                'guard_name' => 'web',
                'created_at' => '2023-10-10 04:34:25',
                'updated_at' => '2023-10-10 04:34:25',
            ),
            10 => 
            array (
                'id' => 20,
                'name' => 'user-index',
                'guard_name' => 'web',
                'created_at' => '2023-10-10 05:44:42',
                'updated_at' => '2023-10-10 05:44:42',
            ),
            11 => 
            array (
                'id' => 21,
                'name' => 'user-create',
                'guard_name' => 'web',
                'created_at' => '2023-10-10 05:44:42',
                'updated_at' => '2023-10-10 05:44:42',
            ),
            12 => 
            array (
                'id' => 22,
                'name' => 'user-edit',
                'guard_name' => 'web',
                'created_at' => '2023-10-10 05:44:42',
                'updated_at' => '2023-10-10 05:44:42',
            ),
            13 => 
            array (
                'id' => 23,
                'name' => 'user-delete',
                'guard_name' => 'web',
                'created_at' => '2023-10-10 05:44:42',
                'updated_at' => '2023-10-10 05:44:42',
            ),
            14 => 
            array (
                'id' => 24,
                'name' => 'user-show',
                'guard_name' => 'web',
                'created_at' => '2023-10-10 05:44:42',
                'updated_at' => '2023-10-10 05:44:42',
            ),
            15 => 
            array (
                'id' => 25,
                'name' => 'crud-index',
                'guard_name' => 'web',
                'created_at' => '2023-10-11 06:51:20',
                'updated_at' => '2023-10-11 06:51:20',
            ),
            16 => 
            array (
                'id' => 26,
                'name' => 'crud-create',
                'guard_name' => 'web',
                'created_at' => '2023-10-11 06:51:20',
                'updated_at' => '2023-10-11 06:51:20',
            ),
            17 => 
            array (
                'id' => 27,
                'name' => 'crud-edit',
                'guard_name' => 'web',
                'created_at' => '2023-10-11 06:51:20',
                'updated_at' => '2023-10-11 06:51:20',
            ),
            18 => 
            array (
                'id' => 28,
                'name' => 'crud-delete',
                'guard_name' => 'web',
                'created_at' => '2023-10-11 06:51:20',
                'updated_at' => '2023-10-11 06:51:20',
            ),
            19 => 
            array (
                'id' => 29,
                'name' => 'crud-show',
                'guard_name' => 'web',
                'created_at' => '2023-10-11 06:51:20',
                'updated_at' => '2023-10-11 06:51:20',
            ),
            20 => 
            array (
                'id' => 30,
                'name' => 'media-index',
                'guard_name' => 'web',
                'created_at' => '2023-10-14 04:07:40',
                'updated_at' => '2023-10-14 04:07:40',
            ),
            21 => 
            array (
                'id' => 31,
                'name' => 'media-create',
                'guard_name' => 'web',
                'created_at' => '2023-10-14 04:07:41',
                'updated_at' => '2023-10-14 04:07:41',
            ),
            22 => 
            array (
                'id' => 32,
                'name' => 'media-edit',
                'guard_name' => 'web',
                'created_at' => '2023-10-14 04:07:41',
                'updated_at' => '2023-10-14 04:07:41',
            ),
            23 => 
            array (
                'id' => 33,
                'name' => 'media-delete',
                'guard_name' => 'web',
                'created_at' => '2023-10-14 04:07:41',
                'updated_at' => '2023-10-14 04:07:41',
            ),
            24 => 
            array (
                'id' => 34,
                'name' => 'menu-index',
                'guard_name' => 'web',
                'created_at' => '2023-10-23 02:39:23',
                'updated_at' => '2023-10-23 02:39:23',
            ),
            25 => 
            array (
                'id' => 35,
                'name' => 'menu-create',
                'guard_name' => 'web',
                'created_at' => '2023-10-23 02:39:23',
                'updated_at' => '2023-10-23 02:39:23',
            ),
            26 => 
            array (
                'id' => 36,
                'name' => 'menu-edit',
                'guard_name' => 'web',
                'created_at' => '2023-10-23 02:39:23',
                'updated_at' => '2023-10-23 02:39:23',
            ),
            27 => 
            array (
                'id' => 37,
                'name' => 'menu-delete',
                'guard_name' => 'web',
                'created_at' => '2023-10-23 02:39:23',
                'updated_at' => '2023-10-23 02:39:23',
            ),
        ));
        
        
    }
}