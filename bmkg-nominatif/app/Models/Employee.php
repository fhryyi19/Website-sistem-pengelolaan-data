<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nip',
        'karpeg',
        'cpns_rank',
        'cpns_tmt',
        'pns_rank',
        'pns_tmt',
        'full_name',
        'prefix_title',
        'suffix_title',
        'birth_place',
        'birth_date',
        'gender_id',
        'religion_id',
        'marital_status_id',
        'salary',
        'salary_tmt',
        'edu_dinas',
        'edu_dinas_year',
        'edu_kursus',
        'edu_kursus_year',
        'edu_ln',
        'edu_ln_year',
        'edu_penjenjangan',
        'edu_penjenjangan_year',
        'children_count',
        'family_count',
        'family_note',
        'address',
        'phone',
        'email',
        'employment_status_id',
        'work_unit_id',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'salary_tmt' => 'date',
            'cpns_tmt' => 'date',
            'pns_tmt' => 'date',
        ];
    }

    // =========================================================
    // Relationships
    // =========================================================

    public function gender(): BelongsTo
    {
        return $this->belongsTo(Gender::class);
    }

    public function religion(): BelongsTo
    {
        return $this->belongsTo(Religion::class);
    }

    public function maritalStatus(): BelongsTo
    {
        return $this->belongsTo(MaritalStatus::class);
    }

    public function employmentStatus(): BelongsTo
    {
        return $this->belongsTo(EmploymentStatus::class);
    }

    public function workUnit(): BelongsTo
    {
        return $this->belongsTo(WorkUnit::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function educations(): HasMany
    {
        return $this->hasMany(EmployeeEducation::class)->orderByDesc('year_graduated');
    }

    public function ranks(): HasMany
    {
        return $this->hasMany(EmployeeRank::class)->orderByDesc('effective_date');
    }

    public function positions(): HasMany
    {
        return $this->hasMany(EmployeePosition::class)->orderByDesc('effective_date');
    }

    public function families(): HasMany
    {
        return $this->hasMany(EmployeeFamily::class);
    }

    public function salaryHistories(): HasMany
    {
        return $this->hasMany(SalaryHistory::class)->orderByDesc('effective_date');
    }

    public function currentRank(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(EmployeeRank::class)->where('is_current', true)->with('rank');
    }

    public function currentPosition(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(EmployeePosition::class)->where('is_current', true)->with('position', 'workUnit');
    }

    // =========================================================
    // Accessors
    // =========================================================

    public function getFullNameWithTitleAttribute(): string
    {
        $name = trim(
            ($this->prefix_title ? $this->prefix_title . ' ' : '') .
            $this->full_name .
            ($this->suffix_title ? ', ' . $this->suffix_title : '')
        );

        return $name;
    }

    public function getAgeAttribute(): int
    {
        return $this->birth_date?->age ?? 0;
    }

    public function getFormattedBirthDateAttribute(): string
    {
        return $this->birth_date?->translatedFormat('d F Y') ?? '-';
    }

    // =========================================================
    // Mutators
    // =========================================================

    public function setFullNameAttribute(string $value): void
    {
        $this->attributes['full_name'] = mb_convert_case(mb_strtolower($value), MB_CASE_TITLE, 'UTF-8');
    }

    public function setEmailAttribute(?string $value): void
    {
        $this->attributes['email'] = $value ? mb_strtolower($value) : null;
    }

    // =========================================================
    // Scopes
    // =========================================================

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereHas('employmentStatus', function ($q) {
            $q->whereIn('code', ['PNS', 'CPNS', 'PPPK']);
        });
    }

    public function scopeInactive(Builder $query): Builder
    {
        return $query->whereHas('employmentStatus', function ($q) {
            $q->whereNotIn('code', ['PNS', 'CPNS', 'PPPK']);
        });
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (empty($term)) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('nip', 'like', "%{$term}%")
              ->orWhere('full_name', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%")
              ->orWhere('phone', 'like', "%{$term}%");
        });
    }
}
