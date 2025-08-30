<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CompaniesTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
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

        // Generate 50 data random
        $companies = [];
        for ($i = 1; $i <= 50; $i++) {
            $companies[] = [
                'name'    => $faker->company,
                'code'    => strtoupper($faker->bothify('C###')), // contoh: C123
                'address' => $faker->city . ', ' . $faker->country,
            ];
        }

        DB::table('companies')->insert($companies);
    }
}
