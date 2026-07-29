<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $fillable = ['name', 'description'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function isAdmin(): bool
    {
        return $this->name === 'admin';
    }

    public function isUser(): bool
    {
        return $this->name === 'user';
    }

    public function getLabelAttribute(): string
    {
        return match ($this->name) {
            'admin' => 'Administrator',
            'user'  => 'User',
            default => ucfirst($this->name),
        };
    }
}
