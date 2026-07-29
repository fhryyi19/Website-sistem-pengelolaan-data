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

    public function countPositions(): int;
    public function countWorkUnits(): int;
    public function countRanks(): int;
}
