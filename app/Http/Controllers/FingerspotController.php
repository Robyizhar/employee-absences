<?php

namespace App\Http\Controllers;

use App\Repositories\FingerspotRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Company;
use App\Models\Employee;

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

                    Storage::append(
                        'fingerspot/get_userid_list.log',
                        now()->toDateTimeString() . ' => ' . json_encode($data, JSON_PRETTY_PRINT)
                    );
                break;

                case 'get_userinfo':
                    $company = Company::select('id', 'code')->where('code', $data['cloud_id'])->first();
                    $employee = Employee::updateOrCreate(
                        [
                            'employee_code' => $data['data']['pin'],
                            'company_id' => $company->id,
                        ],
                        [
                            'is_active' => true,
                            'name' => $data['data']['name']
                        ]
                    );
                    Storage::append(
                        'fingerspot/get_userinfo.log',
                        now()->toDateTimeString() . ' => ' . json_encode($data, JSON_PRETTY_PRINT)
                    );
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
