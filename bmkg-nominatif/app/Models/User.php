<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'role_id',
        'name',
        'email',
        'nip',
        'username',
        'password',
        'avatar',
        'last_login',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password'   => 'hashed',
            'last_login' => 'datetime',
            'is_active'  => 'boolean',
        ];
    }

    // =========================================================
    // Relationships
    // =========================================================

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    // =========================================================
    // Accessors
    // =========================================================

    public function getAvatarUrlAttribute(): string
    {
        if (!empty($this->avatar)) {
            return asset('storage/' . ltrim($this->avatar, '/'));
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=0066cc&color=ffffff&size=80';
    }

    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->name);
        $initials = '';
        foreach (array_slice($words, 0, 2) as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }
        return $initials;
    }

    // =========================================================
    // Helper methods
    // =========================================================

    public function isAdmin(): bool
    {
        return $this->role?->name === 'admin';
    }

    public function isUser(): bool
    {
        return $this->role?->name === 'user';
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }

    // =========================================================
    // Mutators
    // =========================================================

    public function setNameAttribute(string $value): void
    {
        $this->attributes['name'] = mb_convert_case(mb_strtolower($value), MB_CASE_TITLE, 'UTF-8');
    }

    public function setEmailAttribute(string $value): void
    {
        $this->attributes['email'] = mb_strtolower($value);
    }

    public function setUsernameAttribute(string $value): void
    {
        $this->attributes['username'] = mb_strtolower($value);
    }
}
