<?php

namespace App\Http\Controllers;

use App\Services\EmployeeService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        protected EmployeeService $employeeService
    ) {}

    public function index(Request $request): View
    {
        $stats = $this->employeeService->getDashboardStats();

        return view('dashboard.index', compact('stats'));
    }
}
