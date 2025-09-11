<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->truncate();
        DB::table('users')->insert([
            [
                'company_id' => 1,
                'username' => 'admin_techcorp',
                'email' => 'admin@techcorp.com',
                'password' => Hash::make('password'),
                'role' => 'ADMIN',
            ],
        ]);
    }
}
