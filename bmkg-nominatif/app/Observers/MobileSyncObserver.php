<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class MobileSyncObserver
{
    public function created(Model $model): void { $this->notify($model); }
    public function updated(Model $model): void { $this->notify($model); }
    public function deleted(Model $model): void { $this->notify($model); }
    public function restored(Model $model): void { $this->notify($model); }

    private function notify(Model $model): void
    {
        $entity = match (class_basename($model)) {
            'Employee' => 'pegawai', 'WorkUnit' => 'divisi', 'Position' => 'jabatan',
            'Rank' => 'golongan', 'Education' => 'pendidikan', 'User' => 'pengguna',
            'SalaryHistory' => 'salary-history', default => 'riwayat',
        };
        $url = config('services.mobile_sync.url');
        $secret = config('services.mobile_sync.secret');
        if (!$url || !$secret) return;
        try {
            Http::timeout(2)->withHeader('X-Sync-Secret', $secret)->post($url, ['entity' => $entity]);
        } catch (\Throwable $e) {
            // Silently ignore mobile sync failure to prevent breaking main operations
        }
    }
}