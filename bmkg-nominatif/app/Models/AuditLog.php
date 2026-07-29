<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    public const UPDATED_AT = null; // Immutable — no updated_at

    protected $table = 'audit_logs';

    protected $fillable = [
        'user_id', 'user_name', 'action', 'model_type',
        'model_id', 'old_values', 'new_values',
        'ip_address', 'user_agent', 'description',
    ];

    protected function casts(): array
    {
        return [
            'old_values' => 'array',
            'new_values' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'LOGIN'          => 'Masuk',
            'LOGOUT'         => 'Keluar',
            'CREATE'         => 'Tambah Data',
            'UPDATE'         => 'Ubah Data',
            'DELETE'         => 'Hapus Data',
            'RESET_PASSWORD' => 'Reset Password',
            default          => $this->action,
        };
    }

    public function getActionColorAttribute(): string
    {
        return match ($this->action) {
            'LOGIN'          => 'green',
            'LOGOUT'         => 'gray',
            'CREATE'         => 'blue',
            'UPDATE'         => 'yellow',
            'DELETE'         => 'red',
            'RESET_PASSWORD' => 'orange',
            default          => 'gray',
        };
    }
}
