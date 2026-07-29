<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Education extends Model
{
    protected $table = 'educations';

    protected $fillable = ['code', 'name', 'level'];

    protected function casts(): array
    {
        return ['level' => 'integer'];
    }

    public function employeeEducations(): HasMany
    {
        return $this->hasMany(EmployeeEducation::class, 'education_id');
    }
}
