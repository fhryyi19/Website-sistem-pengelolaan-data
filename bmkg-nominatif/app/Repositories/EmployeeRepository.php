<?php

namespace App\Repositories;

use App\Models\Employee;
use App\Repositories\Interfaces\EmployeeRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class EmployeeRepository implements EmployeeRepositoryInterface
{
    public function getAllPaginated(array $filters, int $perPage = 25): LengthAwarePaginator
    {
        $query = Employee::with([
            'gender',
            'religion',
            'maritalStatus',
            'employmentStatus',
            'workUnit',
            'currentRank',
            'currentPosition',
        ]);

        if (!empty($filters['search'])) {
            $query->search($filters['search']);
        }

        if (!empty($filters['employment_status_id'])) {
            $query->where('employment_status_id', $filters['employment_status_id']);
        }

        if (!empty($filters['work_unit_id'])) {
            $query->where('work_unit_id', $filters['work_unit_id']);
        }

        if (!empty($filters['gender_id'])) {
            $query->where('gender_id', $filters['gender_id']);
        }

        if (!empty($filters['religion_id'])) {
            $query->where('religion_id', $filters['religion_id']);
        }

        if (!empty($filters['rank_id'])) {
            $query->whereHas('ranks', function ($q) use ($filters) {
                $q->where('rank_id', $filters['rank_id'])->where('is_current', true);
            });
        }

        if (!empty($filters['position_id'])) {
            $query->whereHas('positions', function ($q) use ($filters) {
                $q->where('position_id', $filters['position_id'])->where('is_current', true);
            });
        }

        // ── Filter Pendidikan ──────────────────────────────────────────

        // Filter jenjang: by level di tabel educations
        if (!empty($filters['education_level'])) {
            $query->whereHas('educations', fn($q) =>
                $q->whereHas('education', fn($eq) =>
                    $eq->where('level', $filters['education_level'])
                )
            );
        }

        // Filter program studi: by education_id spesifik
        if (!empty($filters['education_id'])) {
            $query->whereHas('educations', fn($q) =>
                $q->where('education_id', $filters['education_id'])
            );
        }

        if (!empty($filters['institution_name'])) {
            $query->whereHas('educations', fn($q) =>
                $q->where('institution_name', 'like', "%{$filters['institution_name']}%")
            );
        }

        // ── Filter Riwayat Pangkat (historis, bukan hanya current) ─────

        if (!empty($filters['rank_id_history'])) {
            $query->whereHas('ranks', fn($q) =>
                $q->where('rank_id', $filters['rank_id_history'])
            );
        }

        if (!empty($filters['rank_date_from'])) {
            $query->whereHas('ranks', fn($q) =>
                $q->whereDate('effective_date', '>=', $filters['rank_date_from'])
            );
        }

        if (!empty($filters['rank_date_to'])) {
            $query->whereHas('ranks', fn($q) =>
                $q->whereDate('effective_date', '<=', $filters['rank_date_to'])
            );
        }

        // ── Filter Riwayat Jabatan (historis, bukan hanya current) ─────

        if (!empty($filters['position_id_history'])) {
            $query->whereHas('positions', fn($q) =>
                $q->where('position_id', $filters['position_id_history'])
            );
        }

        if (!empty($filters['position_date_from'])) {
            $query->whereHas('positions', fn($q) =>
                $q->whereDate('effective_date', '>=', $filters['position_date_from'])
            );
        }

        if (!empty($filters['position_date_to'])) {
            $query->whereHas('positions', fn($q) =>
                $q->whereDate('effective_date', '<=', $filters['position_date_to'])
            );
        }

        $sortColumn = $filters['sort_by'] ?? 'full_name';
        $sortDirection = $filters['sort_dir'] ?? 'asc';

        $allowedSorts = ['nip', 'full_name', 'email', 'birth_date', 'created_at'];
        if (in_array($sortColumn, $allowedSorts)) {
            $query->orderBy($sortColumn, $sortDirection);
        } else {
            $query->orderBy('full_name', 'asc');
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function findById(int $id): ?Employee
    {
        return Employee::with([
            'gender',
            'religion',
            'maritalStatus',
            'employmentStatus',
            'workUnit',
            'educations.education',
            'ranks.rank',
            'positions.position',
            'positions.workUnit',
            'families.gender',
            'salaryHistories',
            'createdBy',
            'updatedBy',
        ])->find($id);
    }

    public function findByNip(string $nip): ?Employee
    {
        return Employee::where('nip', $nip)->first();
    }

    public function create(array $data): Employee
    {
        return Employee::create($data);
    }

    public function update(int $id, array $data): Employee
    {
        $employee = Employee::findOrFail($id);
        $employee->update($data);
        return $employee->fresh();
    }

    public function delete(int $id): bool
    {
        $employee = Employee::findOrFail($id);
        return (bool) $employee->delete();
    }

    public function getForExport(array $filters): Collection
    {
        $query = Employee::with([
            'gender',
            'religion',
            'maritalStatus',
            'employmentStatus',
            'workUnit',
            'currentRank',
            'currentPosition',
            'educations.education',
        ]);

        if (!empty($filters['search'])) {
            $query->search($filters['search']);
        }

        if (!empty($filters['employment_status_id'])) {
            $query->where('employment_status_id', $filters['employment_status_id']);
        }

        if (!empty($filters['work_unit_id'])) {
            $query->where('work_unit_id', $filters['work_unit_id']);
        }

        if (!empty($filters['gender_id'])) {
            $query->where('gender_id', $filters['gender_id']);
        }

        if (!empty($filters['religion_id'])) {
            $query->where('religion_id', $filters['religion_id']);
        }

        return $query->orderBy('full_name', 'asc')->get();
    }

    public function countActive(): int
    {
        return Employee::active()->count();
    }

    public function countInactive(): int
    {
        return Employee::inactive()->count();
    }

    public function countAll(): int
    {
        return Employee::count();
    }
}
