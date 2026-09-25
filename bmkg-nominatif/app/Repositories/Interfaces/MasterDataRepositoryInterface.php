<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface MasterDataRepositoryInterface
{
    public function getGenders(): Collection;
    public function getReligions(): Collection;
    public function getMaritalStatuses(): Collection;
    public function getEmploymentStatuses(): Collection;
    public function getEducations(): Collection;
    public function getRanks(): Collection;
    public function getPositions(): Collection;
    public function getWorkUnits(): Collection;

    // Hanya data yang benar-benar digunakan pegawai (untuk dropdown filter)
    public function getUsedEducations(): Collection;
    public function getUsedRanks(): Collection;
    public function getUsedRanksAll(): Collection;
    public function getUsedPositions(): Collection;
    public function getUsedPositionsAll(): Collection;
    public function getUsedEmploymentStatuses(): Collection;
    public function getUsedWorkUnits(): Collection;

    public function countPositions(): int;
    public function countWorkUnits(): int;
    public function countRanks(): int;
}
