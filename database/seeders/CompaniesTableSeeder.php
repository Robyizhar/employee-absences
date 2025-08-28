<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompaniesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('companies')->truncate();
        DB::table('companies')->insert([
            [
                'name' => 'Tech Corp',
                'code' => 'TC001',
                'address' => 'Jakarta, Indonesia',
            ],
            [
                'name' => 'Biz Solutions',
                'code' => 'BS002',
                'address' => 'Bandung, Indonesia',
            ],
        ]);
    }
}
