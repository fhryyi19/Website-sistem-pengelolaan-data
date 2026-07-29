<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\EmployeeEducation;
use App\Models\EmployeeFamily;
use App\Models\EmployeePosition;
use App\Models\EmployeeRank;
use App\Repositories\Interfaces\EmployeeRepositoryInterface;
use App\Repositories\Interfaces\MasterDataRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EmployeeService
{
    public function __construct(
        protected EmployeeRepositoryInterface $employeeRepository,
        protected MasterDataRepositoryInterface $masterDataRepository,
        protected AuditLogService $auditLogService
    ) {}

    public function getPaginatedEmployees(array $filters, int $perPage = 25): LengthAwarePaginator
    {
        return $this->employeeRepository->getAllPaginated($filters, $perPage);
    }

    public function getEmployeeDetail(int $id): ?Employee
    {
        return $this->employeeRepository->findById($id);
    }

    public function createEmployee(array $data): Employee
    {
        return DB::transaction(function () use ($data) {
            $employeeData = [
                'nip'                  => $data['nip'],
                'full_name'            => $data['full_name'],
                'prefix_title'         => $data['prefix_title'] ?? null,
                'suffix_title'         => $data['suffix_title'] ?? null,
                'birth_place'          => $data['birth_place'],
                'birth_date'           => $data['birth_date'],
                'gender_id'            => $data['gender_id'],
                'religion_id'          => $data['religion_id'],
                'marital_status_id'    => $data['marital_status_id'],
                'address'              => $data['address'] ?? null,
                'phone'                => $data['phone'] ?? null,
                'email'                => $data['email'] ?? null,
                'employment_status_id' => $data['employment_status_id'],
                'work_unit_id'         => $data['work_unit_id'],
                'created_by'           => auth()->id(),
            ];

            $employee = $this->employeeRepository->create($employeeData);

            // Add Initial Rank if provided
            if (!empty($data['rank_id'])) {
                EmployeeRank::create([
                    'employee_id'    => $employee->id,
                    'rank_id'        => $data['rank_id'],
                    'decree_number'  => $data['rank_decree_number'] ?? null,
                    'decree_date'    => $data['rank_decree_date'] ?? null,
                    'effective_date' => $data['rank_effective_date'] ?? now(),
                    'is_current'     => true,
                ]);
            }

            // Add Initial Position if provided
            if (!empty($data['position_id'])) {
                EmployeePosition::create([
                    'employee_id'    => $employee->id,
                    'position_id'    => $data['position_id'],
                    'work_unit_id'   => $data['work_unit_id'],
                    'decree_number'  => $data['position_decree_number'] ?? null,
                    'decree_date'    => $data['position_decree_date'] ?? null,
                    'effective_date' => $data['position_effective_date'] ?? now(),
                    'is_current'     => true,
                ]);
            }

            $this->auditLogService->log(
                action: 'CREATE',
                description: "Menambahkan data pegawai baru: {$employee->full_name} (NIP: {$employee->nip})",
                modelType: Employee::class,
                modelId: $employee->id,
                newValues: $employee->toArray()
            );

            return $employee;
        });
    }

    public function updateEmployee(int $id, array $data): Employee
    {
        return DB::transaction(function () use ($id, $data) {
            $employee = $this->employeeRepository->findById($id);
            $oldValues = $employee->toArray();

            $updateData = [
                'nip'                  => $data['nip'],
                'full_name'            => $data['full_name'],
                'prefix_title'         => $data['prefix_title'] ?? null,
                'suffix_title'         => $data['suffix_title'] ?? null,
                'birth_place'          => $data['birth_place'],
                'birth_date'           => $data['birth_date'],
                'gender_id'            => $data['gender_id'],
                'religion_id'          => $data['religion_id'],
                'marital_status_id'    => $data['marital_status_id'],
                'address'              => $data['address'] ?? null,
                'phone'                => $data['phone'] ?? null,
                'email'                => $data['email'] ?? null,
                'employment_status_id' => $data['employment_status_id'],
                'work_unit_id'         => $data['work_unit_id'],
                'updated_by'           => auth()->id(),
            ];

            $updatedEmployee = $this->employeeRepository->update($id, $updateData);

            $this->auditLogService->log(
                action: 'UPDATE',
                description: "Mengubah data pegawai: {$updatedEmployee->full_name} (NIP: {$updatedEmployee->nip})",
                modelType: Employee::class,
                modelId: $updatedEmployee->id,
                oldValues: $oldValues,
                newValues: $updatedEmployee->toArray()
            );

            return $updatedEmployee;
        });
    }

    public function deleteEmployee(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $employee = $this->employeeRepository->findById($id);
            if (!$employee) {
                return false;
            }

            $name = $employee->full_name;
            $nip = $employee->nip;

            $result = $this->employeeRepository->delete($id);

            $this->auditLogService->log(
                action: 'DELETE',
                description: "Menghapus data pegawai: {$name} (NIP: {$nip})",
                modelType: Employee::class,
                modelId: $id
            );

            return $result;
        });
    }

    public function addEducation(int $employeeId, array $data): EmployeeEducation
    {
        $data['employee_id'] = $employeeId;
        $edu = EmployeeEducation::create($data);

        $this->auditLogService->log(
            action: 'CREATE',
            description: "Menambahkan riwayat pendidikan untuk pegawai ID {$employeeId}",
            modelType: EmployeeEducation::class,
            modelId: $edu->id
        );

        return $edu;
    }

    public function addRank(int $employeeId, array $data): EmployeeRank
    {
        return DB::transaction(function () use ($employeeId, $data) {
            if (!empty($data['is_current'])) {
                EmployeeRank::where('employee_id', $employeeId)->update(['is_current' => false]);
            }

            $data['employee_id'] = $employeeId;
            $rank = EmployeeRank::create($data);

            $this->auditLogService->log(
                action: 'CREATE',
                description: "Menambahkan riwayat golongan/pangkat untuk pegawai ID {$employeeId}",
                modelType: EmployeeRank::class,
                modelId: $rank->id
            );

            return $rank;
        });
    }

    public function addPosition(int $employeeId, array $data): EmployeePosition
    {
        return DB::transaction(function () use ($employeeId, $data) {
            if (!empty($data['is_current'])) {
                EmployeePosition::where('employee_id', $employeeId)->update(['is_current' => false]);
            }

            $data['employee_id'] = $employeeId;
            $pos = EmployeePosition::create($data);

            $this->auditLogService->log(
                action: 'CREATE',
                description: "Menambahkan riwayat jabatan untuk pegawai ID {$employeeId}",
                modelType: EmployeePosition::class,
                modelId: $pos->id
            );

            return $pos;
        });
    }

    public function addFamily(int $employeeId, array $data): EmployeeFamily
    {
        $data['employee_id'] = $employeeId;
        $fam = EmployeeFamily::create($data);

        $this->auditLogService->log(
            action: 'CREATE',
            description: "Menambahkan data keluarga untuk pegawai ID {$employeeId}",
            modelType: EmployeeFamily::class,
            modelId: $fam->id
        );

        return $fam;
    }

    public function getDashboardStats(): array
    {
        return [
            'total_employees'  => $this->employeeRepository->countAll(),
            'active_employees' => $this->employeeRepository->countActive(),
            'inactive_employees' => $this->employeeRepository->countInactive(),
            'total_users'      => \App\Models\User::count(),
            'total_positions'  => $this->masterDataRepository->countPositions(),
            'total_work_units' => $this->masterDataRepository->countWorkUnits(),
            'total_ranks'      => $this->masterDataRepository->countRanks(),
        ];
    }
}
