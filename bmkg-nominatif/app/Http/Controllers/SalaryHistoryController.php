<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\SalaryHistory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SalaryHistoryController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $year = $request->query('year');
        $reason = $request->query('reason');
        $sortBy = $request->query('sort', 'latest_effective');

        $query = SalaryHistory::with([
            'employee' => function ($q) {
                $q->select('id', 'full_name', 'nip', 'prefix_title', 'suffix_title', 'employment_status_id', 'work_unit_id')
                  ->with([
                      'currentPosition.position',
                      'currentRank.rank',
                      'workUnit',
                  ]);
            }
        ]);

        if ($search) {
            $query->whereHas('employee', function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%");
            })->orWhere('reason', 'like', "%{$search}%")
              ->orWhere('notes', 'like', "%{$search}%");
        }

        if ($year) {
            $query->whereYear('effective_date', $year);
        }

        if ($reason) {
            $query->where('reason', 'like', "%{$reason}%");
        }

        switch ($sortBy) {
            case 'oldest_effective':
                $query->orderBy('effective_date', 'asc')->orderBy('id', 'asc');
                break;
            case 'highest_increase':
                $query->orderByDesc('increase_amount')->orderByDesc('effective_date');
                break;
            case 'highest_salary':
                $query->orderByDesc('new_salary')->orderByDesc('effective_date');
                break;
            case 'latest_effective':
            default:
                $query->orderByDesc('effective_date')->orderByDesc('id');
                break;
        }

        $salaryHistories = $query->paginate(15)->withQueryString();

        // Summary Statistics
        $totalRecords = SalaryHistory::count();
        $uniqueEmployees = SalaryHistory::distinct('employee_id')->count('employee_id');
        $totalNominalIncrease = (int) SalaryHistory::sum('increase_amount');
        $avgIncrease = $totalRecords > 0 ? (int) SalaryHistory::avg('increase_amount') : 0;
        $maxIncrease = (int) SalaryHistory::max('increase_amount');

        // Distinct years for filter
        $availableYears = SalaryHistory::whereNotNull('effective_date')
            ->get(['effective_date'])
            ->pluck('effective_date')
            ->map(fn ($date) => (int) \Illuminate\Support\Carbon::parse($date)->format('Y'))
            ->unique()
            ->values()
            ->sortDesc();

        return view('salary-history.index', compact(
            'salaryHistories',
            'totalRecords',
            'uniqueEmployees',
            'totalNominalIncrease',
            'avgIncrease',
            'maxIncrease',
            'availableYears',
            'search',
            'year',
            'reason',
            'sortBy'
        ));
    }
}
