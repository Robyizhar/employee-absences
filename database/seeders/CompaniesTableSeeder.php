<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use App\Models\Company;
use App\Models\Machine;

class CompaniesTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        DB::table('machines')->truncate();
        DB::table('employees')->truncate();
        DB::table('companies')->truncate();

        $kitchen = Company::updateOrCreate(
            ['code' => 'C26458A457302130'], // kondisi unik
            [
                'name' => 'Tech Corp',
                'address' => 'Jakarta, Indonesia',
                'is_active' => true,
            ]
        );

        $id = $kitchen->id;

        Machine::updateOrCreate(
            [
                'serial_number' => 'C26458A457302130',
                'company_id' => $id
            ],
            [
                'location' => 'Main Office'
            ]
        );

        // $faker = Faker::create();
        // foreach (range(1, 100) as $i) {
        //     DB::table('employees')->insert([
        //         'company_id' => 1,
        //         'employee_code' => 'EMP' . str_pad($i, 4, '0', STR_PAD_LEFT),
        //         'name' => $faker->name(),
        //         'department' => $faker->randomElement(['HR', 'IT', 'Finance', 'Marketing']),
        //         'position' => $faker->jobTitle(),
        //     ]);
        // }
    }
}
