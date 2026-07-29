<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmploymentStatus extends Model
{
    protected $table = 'employment_statuses';

    protected $fillable = ['code', 'name'];

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'employment_status_id');
    }
}
