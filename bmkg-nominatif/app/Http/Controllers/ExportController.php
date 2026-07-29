<?php

namespace App\Http\Controllers;

use App\Services\ExportService;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function __construct(
        protected ExportService $exportService
    ) {}

    public function excel(Request $request)
    {
        $filters = $request->only([
            'search', 'employment_status_id', 'work_unit_id',
            'gender_id', 'religion_id', 'rank_id', 'position_id',
        ]);

        return $this->exportService->exportEmployeesExcel($filters);
    }
}
