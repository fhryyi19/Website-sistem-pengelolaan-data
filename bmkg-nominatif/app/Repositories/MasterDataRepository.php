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
