<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeEducation extends Model
{
    protected $table = 'employee_educations';

    protected $fillable = [
        'employee_id', 'education_id', 'institution_name',
        'major', 'year_graduated', 'certificate_number',
    ];

    protected function casts(): array
    {
        return ['year_graduated' => 'integer'];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function education(): BelongsTo
    {
        return $this->belongsTo(Education::class, 'education_id');
    }
}
