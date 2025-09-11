<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Employee;
use App\Repositories\FingerspotRepository;

class EmployeeController extends Controller
{
    protected FingerspotRepository $fingerspot;

    public function __construct(FingerspotRepository $fingerspot) {
        $this->fingerspot = $fingerspot;
    }

    public function index() {
        return view('employee.index');
    }

    public function list(Request $request) {
        $perPage = 10;
        $lastId = $request->get('last_id', null);
        $query = Employee::orderBy('created_at', 'desc');

        if ($lastId)
            $query->where('id', '>', $lastId);

        $data = $query->take($perPage)->get();

        return response()->json([
            'data' => $data,
            'hasMore' => $data->count() === $perPage
        ]);
    }

    public function refreshEmployees() {
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

        \Log::info($codes);
        return response()->json($codes, 200);
    }
}
