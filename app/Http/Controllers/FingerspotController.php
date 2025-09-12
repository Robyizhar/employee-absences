<?php

namespace App\Http\Controllers;

use App\Repositories\FingerspotRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Company;
use App\Models\Employee;
use App\Models\AttendanceLogs;
use App\Models\Machine;
use App\Jobs\StoreFingerLogJob;
use Carbon\Carbon;

class FingerspotController extends Controller
{
    protected FingerspotRepository $fingerspot;

    public function __construct(FingerspotRepository $fingerspot) {
        $this->fingerspot = $fingerspot;
    }

    public function store(Request $request) {
        try {
            $payload = $request->getContent();
            $data = json_decode($payload, true);
            switch ($data['type'] ?? null) {
                case 'get_userid_list':
                    StoreFingerLogJob::dispatch('fingerspot/get_userid_list.log', $data);
                    $company = Company::select('id', 'code')->where('code', $data['cloud_id'])->first();
                    foreach ($data['data']['pin_arr'] as $key => $value) {
                        $employee = Employee::firstOrCreate(
                            [
                                'employee_code' => $value,
                                'company_id' => $company->id,
                            ],
                            [
                                'is_active' => false
                            ]
                        );
                        $this->fingerspot->getUserInfo([
                            'cloud_id' => $company->code,
                            'pin'      => $employee->employee_code,
                        ]);
                    }
                break;

                case 'get_userinfo':
                    StoreFingerLogJob::dispatch('fingerspot/get_userinfo.log', $data);
                    $company = Company::select('id', 'code')->where('code', $data['cloud_id'])->first();
                    $employee = Employee::updateOrCreate(
                        [
                            'employee_code' => $data['data']['pin'],
                            'company_id' => $company->id,
                        ],
                        [
                            'is_active' => true,
                            'name' => $data['data']['name'],
                            'template' => $data['data']['template']
                        ]
                    );
                    return true;
                break;
                /**
                     * Process an attlog callback payload from Fingerspot and store result.
                     *
                     * Strategy:
                     *  - map status_scan -> verification_method
                     *  - deduplicate if previous scan close in time (config threshold)
                     *  - determine IN/OUT by alternation per-day (first -> IN; next -> OUT; next->IN; ...)
                     *  - calculate late/early seconds & minutes against configured work_start/work_end
                 */
                case 'attlog':
                    StoreFingerLogJob::dispatch('fingerspot/attlog.log', $data);
                    $pin = $data['data']['pin'] ?? null;
                    $scanStr = $data['data']['scan'] ?? null;
                    $statusScan = $data['data']['status_scan'] ?? null;

                    if (!$pin || !$scanStr)
                        return null;

                    $company = Company::select('id', 'code')
                        ->where('code', $data['cloud_id'])
                        ->first();

                    $employee = Employee::where('employee_code', $pin)
                        ->where('company_id', $company->id)
                        ->first();

                    $machine = Machine::where('serial_number', $data['cloud_id'] ?? null)
                        ->first();

                    // parse time with timezone
                    $tz = config('attendance.timezone', config('app.timezone', 'Asia/Jakarta'));
                    $scan = Carbon::parse($scanStr, $tz);

                    $verificationMethod = match($statusScan) {
                        0 => 'finger',
                        1 => 'face',
                        2 => 'password',
                        3 => 'rfid',
                        default => 'other'
                    };

                     // duplicate detection
                    $dupThreshold = config('attendance.duplicate_threshold_seconds', 30);

                    $last = AttendanceLogs::where('employee_id', $employee?->id ?? null)
                        ->whereDate('scan_time', $scan->toDateString())
                        ->orderBy('scan_time', 'desc')
                        ->first();

                    if ($last && $last->scan_time->diffInSeconds($scan) <= $dupThreshold) {
                        // Save as duplicate (opsional) OR skip saving.
                        AttendanceLogs::create([
                            'company_id' => $employee?->company_id ?? null,
                            'machine_id' => $machine?->id ?? null,
                            'employee_id' => $employee?->id ?? null,
                            'scan_time' => $scan,
                            'status' => $last->status, // keep last status, or set set null
                            'raw_payload' => $payload,
                            'verification_method' => $verificationMethod,
                            'is_duplicate' => true,
                        ]);
                        return true;
                    }

                    // Determine new attendance direction (IN / OUT) by alternation per day:
                    if (!$last) {
                        $direction = 'IN';
                    } else {
                        // alternate: if last was IN -> this is OUT; if last was OUT -> this is IN
                        $direction = $last->status === 'IN' ? 'OUT' : 'IN';
                    }

                     // Work start/end for that day
                    $workStart = Carbon::parse($scan->toDateString() . ' ' . config('attendance.work_start', '08:00:00'), $tz);
                    $workEnd = Carbon::parse($scan->toDateString() . ' ' . config('attendance.work_end', '17:00:00'), $tz);

                    $lateSeconds = null;
                    $earlySeconds = null;
                    $lateMinutes = null;
                    $earlyMinutes = null;

                    if ($direction === 'IN') {
                        if ($scan->lessThanOrEqualTo($workStart->addSeconds(config('attendance.grace_seconds', 0)))) {
                            $lateSeconds = 0;
                            $lateMinutes = 0;
                        } else {
                            $lateSeconds = $scan->diffInSeconds($workStart);
                            $lateMinutes = intdiv($lateSeconds, 60);
                        }
                    } else { // OUT
                        if ($scan->greaterThanOrEqualTo($workEnd)) {
                            $earlySeconds = 0;
                            $earlyMinutes = 0;
                        } else {
                            $earlySeconds = $workEnd->diffInSeconds($scan);
                            $earlyMinutes = intdiv($earlySeconds, 60);
                        }
                    }

                    AttendanceLogs::create([
                        'company_id' => $employee?->company_id ?? null,
                        'machine_id' => $machine?->id ?? null,
                        'employee_id' => $employee?->id ?? null,
                        'scan_time' => $scan,
                        'status' => $direction, // IN or OUT
                        'raw_payload' => $payload,
                        'verification_method' => $verificationMethod,
                        'is_duplicate' => false,
                        'late_seconds' => $lateSeconds,
                        'late_minutes' => $lateMinutes,
                        'early_seconds' => $earlySeconds,
                        'early_leave_minutes' => $earlyMinutes,
                    ]);

                    return true;
                break;

                default:
                    Storage::append(
                        'fingerspot/others.log',
                        now()->toDateTimeString() . ' => ' . json_encode($data, JSON_PRETTY_PRINT)
                    );
                break;
            }

            return response("OK", 200);
        } catch (\Exception $e){
            \Log::error($e);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function userInfo(Request $request) {
        try {
            $data = $this->fingerspot->getUserInfo([
                'cloud_id' => 'C26458A457302130',
                'pin'      => 1,
            ]);

            return response()->json([
                'success' => true,
                'data'    => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function attendances(Request $request) {
        try {
            $data = $this->fingerspot->getAttendances([
                'cloud_id'   => 'C26458A457302130',
                'start_date' => '2025-09-09',
                'end_date'   => '2025-09-10',
            ]);

            return response()->json([
                'success' => true,
                'data'    => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function allPin(Request $request) {
        $params = [
            'cloud_id' => 'C26458A457302130',
        ];

        $result = $this->fingerspot->getAllPin($params);

        return response()->json($result);
    }
}
