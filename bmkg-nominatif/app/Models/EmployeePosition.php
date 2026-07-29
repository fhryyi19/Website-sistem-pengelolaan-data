<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeePosition extends Model
{
    protected $table = 'employee_positions';

    protected $fillable = [
        'employee_id', 'position_id', 'work_unit_id',
        'decree_number', 'decree_date', 'effective_date', 'is_current',
    ];

    protected function casts(): array
    {
        return [
            'decree_date'    => 'date',
            'effective_date' => 'date',
            'is_current'     => 'boolean',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class, 'position_id');
    }

    public function workUnit(): BelongsTo
    {
        return $this->belongsTo(WorkUnit::class, 'work_unit_id');
    }

    public function getFormattedEffectiveDateAttribute(): string
    {
        return $this->effective_date?->translatedFormat('d F Y') ?? '-';
    }
}
