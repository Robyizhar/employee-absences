<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;

class CompanyController extends Controller
{
    public function index() {
        return view('company.index');
    }

    public function list(Request $request) {
        $perPage = 10;
        $lastId = $request->get('last_id', null);

        $query = Company::orderBy('id', 'asc');

        if ($lastId) {
            $query->where('id', '>', $lastId);
        }

        $users = $query->take($perPage)->get();

        return response()->json([
            'data' => $users,
            'hasMore' => $users->count() === $perPage
        ]);
    }
}
