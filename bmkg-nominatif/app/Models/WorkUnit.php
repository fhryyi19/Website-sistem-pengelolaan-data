<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkUnit extends Model
{
    use SoftDeletes;

    protected $table = 'work_units';

    protected $fillable = ['code', 'name', 'description', 'parent_id'];

    protected function casts(): array
    {
        return ['parent_id' => 'integer'];
    }

    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(WorkUnit::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(WorkUnit::class, 'parent_id');
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'work_unit_id');
    }

    public function employeePositions(): HasMany
    {
        return $this->hasMany(EmployeePosition::class, 'work_unit_id');
    }
}
