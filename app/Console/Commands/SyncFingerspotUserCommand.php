<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\StoreFingerLogJob;
use App\Models\Company;
use App\Models\Employee;
use App\Repositories\FingerspotRepository;
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

    public function __construct(FingerspotRepository $fingerspot)
    {
        parent::__construct();
        $this->fingerspot = $fingerspot;
    }

    public function handle()
    {
        try {
            $company_id = auth()->user()->company_id ?? null;
            $query = Company::select('code');

            if (!empty($company_id))
                $query->where('id', $company_id);

            $codes = $query
                ->where('is_active', true)
                ->pluck('code')->toArray();

            foreach ($codes as $key => $code) {
                $params = [ 'cloud_id' => $code ];
                $result = $this->fingerspot->getAllPin($params);
            }

            // \Log::info($codes);
            return response()->json($codes, 200);

        } catch (\Throwable $e) {
            Log::error('Fingerspot Sync Error: ' . $e->getMessage());
            $this->error($e->getMessage());
        }
    }
}
