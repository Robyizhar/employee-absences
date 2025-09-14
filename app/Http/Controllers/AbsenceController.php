<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendanceLogs;

class AbsenceController extends Controller
{
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
}
