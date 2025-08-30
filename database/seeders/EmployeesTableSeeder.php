<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class EmployeesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('employees')->truncate();
        $faker = Faker::create();

        foreach (range(1, 100) as $i) {
            DB::table('employees')->insert([
                'company_id' => $faker->numberBetween(1, 2), // from seeded companies
                'employee_code' => 'EMP' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'name' => $faker->name(),
                'department' => $faker->randomElement(['HR', 'IT', 'Finance', 'Marketing']),
                'position' => $faker->jobTitle(),
            ]);
        }
    }
}
