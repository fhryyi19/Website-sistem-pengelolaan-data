<?php

namespace App\Http\Controllers;

use App\Models\SalaryHistory;
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
        $salaryHistories = SalaryHistory::with('employee:id,full_name,prefix_title,suffix_title')
            ->latest('effective_date')
            ->latest('id')
            ->limit(5)
            ->get();

        $recentAuditLogs = \App\Models\AuditLog::latest('created_at')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact('stats', 'salaryHistories', 'recentAuditLogs'));
    }
}
