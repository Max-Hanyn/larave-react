<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('roles')->insert([
            ['name' => 'Admin',
                'slug' => 'admin'],
            ['name' => 'Moderator',
                'slug' => 'moderator'],
            ['name' => 'User',
                'slug' => 'user'],
        ]);
    }
}
