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
