<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeRank extends Model
{
    protected $table = 'employee_ranks';

    protected $fillable = [
        'employee_id', 'rank_id', 'decree_number',
        'decree_date', 'effective_date', 'is_current',
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

    public function rank(): BelongsTo
    {
        return $this->belongsTo(Rank::class, 'rank_id');
    }

    public function getFormattedEffectiveDateAttribute(): string
    {
        return $this->effective_date?->translatedFormat('d F Y') ?? '-';
    }
}
