<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalaryHistory extends Model
{
    protected $fillable = [
        'employee_id',
        'old_salary',
        'new_salary',
        'increase_amount',
        'increase_percentage',
        'effective_date',
        'reason',
        'notes',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'effective_date' => 'date',
            'old_salary' => 'integer',
            'new_salary' => 'integer',
            'increase_amount' => 'integer',
            'increase_percentage' => 'float',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function getFormattedEffectiveDateAttribute(): string
    {
        return $this->effective_date?->translatedFormat('d F Y') ?? '-';
    }
}
