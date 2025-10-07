<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendanceLogs;
use App\Models\Company;
use App\Repositories\FingerspotRepository;
use Carbon\Carbon;

class AbsenceController extends Controller
{
    protected FingerspotRepository $fingerspot;

    public function __construct(FingerspotRepository $fingerspot) {
        $this->fingerspot = $fingerspot;
    }

    public function index() {
        return view('absence.logs');
    }

    public function list(Request $request) {
        $perPage = 10;
        $lastId = $request->get('last_id', null);

        $query = AttendanceLogs::with(['employee', 'machine'])
            ->orderBy('created_at', 'desc');

        if ($lastId) {
            $query->where('id', '>', $lastId);
        }

        $data = $query->take($perPage)->get();

        return response()->json([
            'data' => $data,
            'hasMore' => $data->count() === $perPage
        ]);
    }

    // public function refreshAbsences() {
    //     $company_id = auth()->user()->company_id ?? null;
    //     $query = Company::select('id', 'code');

    //     if (!empty($company_id))
    //         $query->where('id', $company_id);

    //     $codes = $query
    //         ->where('is_active', true)->get()->toArray();

    //     // \Log::warning($codes);

    //     foreach ($codes as $key => $code) {
    //         // {"trans_id":"1", "cloud_id":"C26458A457302130", "start_date":"2025-10-06", "end_date":"2025-10-08"}
    //         $tz = config('app.absence_timezone', 'Asia/Jakarta');
    //         $params = [
    //             'cloud_id' => $code['code'],
    //             'start_date' => Carbon::now($tz)->format('Y-m-d'), // 2025-10-06
    //             'end_date'   => Carbon::now($tz)->addDays(2)->format('Y-m-d'), // 2025-10-08
    //         ];
    //         $result = $this->fingerspot->getAttendances($params);
    //         // \Log::warning($params);
    //         // \Log::info($result);
    //         if (!isset($result['success']) || !$result['success'] || empty($result['data'])) {
    //             Log::warning("No attendance data for machine {$code['code']}");
    //             continue;
    //         }

    //         foreach ($result['data'] as $log) {
    //             try {
    //                 // Konversi waktu dari timezone mesin (Asia/Jakarta) ke UTC
    //                 $scanUtc = Carbon::parse($log['scan_date'], $tz)->setTimezone('UTC');

    //                 // Cek duplikat berdasarkan waktu dan pin (lebih aman)
    //                 $exists = AttendanceLogs::where('scan_time', $scanUtc)->where('company_id', $code['id'])
    //                     ->whereHas('employee', function ($q) use ($log) {
    //                         $q->where('pin', $log['pin']);
    //                     })
    //                     ->exists();

    //                 if ($exists) {
    //                     continue; // skip kalau sudah ada
    //                 }

    //                 // Dapatkan employee_id berdasarkan pin
    //                 $employeeId = Employee::where('pin', $log['pin'])->value('id');
    //                 if (!$employeeId) {
    //                     Log::warning("PIN {$log['pin']} not found in employees table");
    //                     continue;
    //                 }

    //                 switch ($log['verify']) {
    //                     case 0:
    //                         $verificationMethod = 'finger';
    //                         break;
    //                     case 1:
    //                         $verificationMethod = 'face';
    //                         break;
    //                     case 2:
    //                         $verificationMethod = 'password';
    //                         break;
    //                     case 3:
    //                         $verificationMethod = 'rfid';
    //                         break;
    //                     default:
    //                         $verificationMethod = 'other';
    //                         break;
    //                 }

    //                 AttendanceLogs::create([
    //                     'company_id'          => 1, // atau ambil dari relasi mesin
    //                     'machine_id'          => null, // isi sesuai jika ada id mesin
    //                     'employee_id'         => $employeeId,
    //                     'scan_time'           => $scanUtc,
    //                     'status'              => $this->mapStatus($log['status_scan']), // mapping 0/1 → IN/OUT
    //                     'verification_method' => $verificationMethod,
    //                     'raw_payload'         => $log,
    //                 ]);

    //             } catch (\Exception $e) {
    //                 Log::error("Error saving attendance for PIN {$log['pin']}: " . $e->getMessage());
    //             }
    //         }

    //     }

    //     // \Log::info($codes);
    //     return response()->json($codes, 200);
    // }
}
