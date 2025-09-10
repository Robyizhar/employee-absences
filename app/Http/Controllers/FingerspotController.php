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
            // ambil semua body JSON
            $payload = $request->getContent();

            // simpan ke file di storage/app/fingerspot/userinfo.txt
            Storage::append('fingerspot/userinfo.txt', $payload);

            // kalau mau langsung decode JSON
            $data = json_decode($payload, true);

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
                'trans_id' => 1,
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
                'trans_id'   => 1,
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
}
