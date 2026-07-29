<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeFamily extends Model
{
    protected $table = 'employee_families';

    protected $fillable = [
        'employee_id', 'name', 'relationship',
        'gender_id', 'birth_date', 'occupation',
    ];

    protected function casts(): array
    {
        return ['birth_date' => 'date'];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function gender(): BelongsTo
    {
        return $this->belongsTo(Gender::class, 'gender_id');
    }

    public function getFormattedBirthDateAttribute(): string
    {
        return $this->birth_date?->translatedFormat('d F Y') ?? '-';
    }
}
