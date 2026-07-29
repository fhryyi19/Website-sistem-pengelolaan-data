<?php

namespace App\Services;

use App\Exports\EmployeesExport;
use App\Repositories\Interfaces\EmployeeRepositoryInterface;
use Maatwebsite\Excel\Facades\Excel;

class ExportService
{
    public function __construct(
        protected EmployeeRepositoryInterface $employeeRepository,
        protected AuditLogService $auditLogService
    ) {}

    public function exportEmployeesExcel(array $filters)
    {
        $employees = $this->employeeRepository->getForExport($filters);
        $filename = 'pegawai_' . date('Y_m_d_His') . '.xlsx';

        $this->auditLogService->log(
            action: 'CREATE',
            description: "Melakukan Export Data Pegawai Excel (" . count($employees) . " data)"
        );

        return Excel::download(new EmployeesExport($employees), $filename);
    }
}
