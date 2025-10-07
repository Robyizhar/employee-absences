<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index() {
        return view('permission.index');
    }

    public function list(Request $request) {
        $perPage = 10;
        $lastId = $request->get('last_id', null);

        $query = Permission::orderBy('id', 'asc');

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
