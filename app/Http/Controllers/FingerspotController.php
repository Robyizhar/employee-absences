<?php

namespace App\Http\Controllers;

use App\Repositories\FingerspotRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            // Storage::append('fingerspot.log', $data);

            // example response get_userid_list :
            // {"type":"get_userid_list","cloud_id":"C26458A457302130","trans_id":5,"data":{"total":5,"pin_arr":["1","2","3","4","5"]}}

            switch ($data->type) {
                case 'get_userid_list':
                    Storage::append('fingerspot/get_userid_list.log', $data);
                break;
                default:
                    Storage::append('fingerspot/others.log', $data);
                break;
            }

            // contoh: simpan ke database (opsional)
            // User::updateOrCreate(
            //     ['pin' => $data['data']['pin']],
            //     [
            //         'name'      => $data['data']['name'],
            //         'privilege' => $data['data']['privilege'],
            //         'rfid'      => $data['data']['rfid'],
            //         'finger'    => $data['data']['finger'],
            //         'face'      => $data['data']['face'],
            //         'vein'      => $data['data']['vein'],
            //     ]
            // );

            return response("OK", 200);
        } catch (\Exception $e) {
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
