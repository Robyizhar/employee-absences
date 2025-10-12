<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\StoreFingerLogJob;
use App\Models\Company;
use App\Models\Employee;
use App\Services\FingerspotService; // pastikan sesuai lokasi servicenya
use Illuminate\Support\Facades\Log;

class SyncFingerspotUserCommand extends Command
{
    /**
     * Nama dan signature command.
     */
    protected $signature = 'fingerspot:sync-users';

    /**
     * Deskripsi command.
     */
    protected $description = 'Sinkronisasi user dari Fingerspot API dan update data karyawan.';

    protected $fingerspot;

    public function __construct(FingerspotService $fingerspot)
    {
        parent::__construct();
        $this->fingerspot = $fingerspot;
    }

    public function handle()
    {
        try {
            // Misal ambil data dari API fingerspot
            $data = $this->fingerspot->getUserIdList(); // sesuaikan dengan function-mu

            // Log job
            StoreFingerLogJob::dispatch('fingerspot/get_userid_list.log', $data);

            $company = \App\Models\Company::select('id', 'code')
                ->where('code', $data['cloud_id'])
                ->first();

            if (!$company) {
                Log::warning("Company not found for cloud_id: " . $data['cloud_id']);
                return;
            }

            foreach ($data['data']['pin_arr'] as $value) {
                $employee = \App\Models\Employee::firstOrCreate(
                    [
                        'employee_code' => $value,
                        'company_id' => $company->id,
                    ],
                    [
                        'is_active' => false,
                    ]
                );

                $this->fingerspot->getUserInfo([
                    'cloud_id' => $company->code,
                    'pin'      => $employee->employee_code,
                ]);
            }

            $this->info('Fingerspot user sync completed successfully.');

        } catch (\Throwable $e) {
            Log::error('Fingerspot Sync Error: ' . $e->getMessage());
            $this->error($e->getMessage());
        }
    }
}
