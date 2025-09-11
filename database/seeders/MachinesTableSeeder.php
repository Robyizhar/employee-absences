<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MachinesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('machines')->truncate();
        DB::table('machines')->insert([
            [
                'company_id' => 1,
                'serial_number' => 'SN-001',
                'location' => 'Main Office',
            ],
            [
                'company_id' => 2,
                'serial_number' => 'SN-002',
                'location' => 'Branch Office',
            ],
        ]);
    }
}
