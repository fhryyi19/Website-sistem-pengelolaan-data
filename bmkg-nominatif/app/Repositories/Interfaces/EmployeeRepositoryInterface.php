<?php

namespace App\Repositories\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface EmployeeRepositoryInterface
{
    public function getAllPaginated(array $filters, int $perPage): LengthAwarePaginator;

    public function findById(int $id): ?\App\Models\Employee;

    public function findByNip(string $nip): ?\App\Models\Employee;

    public function create(array $data): \App\Models\Employee;

    public function update(int $id, array $data): \App\Models\Employee;

    public function delete(int $id): bool;

    public function getForExport(array $filters): Collection;

    public function countActive(): int;

    public function countInactive(): int;

    public function countAll(): int;
}
