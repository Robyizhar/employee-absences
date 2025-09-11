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
    }
}
