<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index() {
        // \Log::info(auth()->user()->company_id);
        return view('dashboard.index');
    }
}
