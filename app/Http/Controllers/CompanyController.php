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
        $page = (int) $request->get('page', 1);
        $perPage = 10;

        $query = Company::query();

        $total = $query->count();
        $users = $query->skip(($page - 1) * $perPage)->take($perPage)->get();
        $pages = ceil($total/$perPage);

        return response()->json([
            'data' => $users,
            'total' => $total,
            'page' => $page,
            'pages' => $pages,
            'perPage' => $perPage,
            'hasMore' => ($page * $perPage) < $total
        ]);
    }
}
