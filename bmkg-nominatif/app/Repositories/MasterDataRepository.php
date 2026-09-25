<?php

namespace App\Repositories;

use App\Models\Education;
use App\Models\EmploymentStatus;
use App\Models\Gender;
use App\Models\MaritalStatus;
use App\Models\Position;
use App\Models\Rank;
use App\Models\Religion;
use App\Models\WorkUnit;
use App\Repositories\Interfaces\MasterDataRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class MasterDataRepository implements MasterDataRepositoryInterface
{
    public function getGenders(): Collection
    {
        return Cache::remember('master_genders', 86400, fn() => Gender::all());
    }

    public function getReligions(): Collection
    {
        return Cache::remember('master_religions', 86400, fn() => Religion::all());
    }

    public function getMaritalStatuses(): Collection
    {
        return Cache::remember('master_marital_statuses', 86400, fn() => MaritalStatus::all());
    }

    public function getEmploymentStatuses(): Collection
    {
        return Cache::remember('master_employment_statuses', 86400, fn() => EmploymentStatus::all());
    }

    public function getEducations(): Collection
    {
        return Cache::remember('master_educations', 86400, fn() => Education::orderBy('level')->get());
    }

    public function getRanks(): Collection
    {
        return Cache::remember('master_ranks', 86400, fn() => Rank::orderBy('code')->get());
    }

    public function getPositions(): Collection
    {
        return Cache::remember('master_positions', 86400, fn() => Position::orderBy('name')->get());
    }

    public function getWorkUnits(): Collection
    {
        return Cache::remember('master_work_units', 86400, fn() => WorkUnit::orderBy('name')->get());
    }

    // ── Data yang benar-benar digunakan pegawai (untuk dropdown filter) ──

    public function getUsedEducations(): Collection
    {
        return Cache::remember('filter_educations', 300, fn() =>
            Education::whereHas('employeeEducations')
                     ->orderBy('level')
                     ->get()
        );
    }

    public function getUsedRanks(): Collection
    {
        return Cache::remember('filter_ranks_current', 300, fn() =>
            Rank::whereHas('employeeRanks', fn($q) => $q->where('is_current', true))
                ->orderBy('code')
                ->get()
        );
    }

    public function getUsedRanksAll(): Collection
    {
        return Cache::remember('filter_ranks_all', 300, fn() =>
            Rank::whereHas('employeeRanks')
                ->orderBy('code')
                ->get()
        );
    }

    public function getUsedPositions(): Collection
    {
        return Cache::remember('filter_positions_current', 300, fn() =>
            Position::whereHas('employeePositions', fn($q) => $q->where('is_current', true))
                    ->orderBy('name')
                    ->get()
        );
    }

    public function getUsedPositionsAll(): Collection
    {
        return Cache::remember('filter_positions_all', 300, fn() =>
            Position::whereHas('employeePositions')
                    ->orderBy('name')
                    ->get()
        );
    }

    public function getUsedEmploymentStatuses(): Collection
    {
        return Cache::remember('filter_employment_statuses', 300, fn() =>
            EmploymentStatus::whereHas('employees')
                            ->get()
        );
    }

    public function getUsedWorkUnits(): Collection
    {
        return Cache::remember('filter_work_units', 300, fn() =>
            WorkUnit::whereHas('employees')
                    ->orderBy('name')
                    ->get()
        );
    }

    public function countPositions(): int
    {
        return Position::count();
    }

    public function countWorkUnits(): int
    {
        return WorkUnit::count();
    }

    public function countRanks(): int
    {
        return Rank::count();
    }
}
