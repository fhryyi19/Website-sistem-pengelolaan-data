<?php

namespace App\Services;

use App\Repositories\Interfaces\AuditLogRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AuditLogService
{
    public function __construct(
        protected AuditLogRepositoryInterface $auditLogRepository
    ) {}

    public function getPaginatedLogs(array $filters, int $perPage = 25): LengthAwarePaginator
    {
        return $this->auditLogRepository->getAllPaginated($filters, $perPage);
    }

    public function log(
        string $action,
        ?string $description = null,
        ?string $modelType = null,
        ?int $modelId = null,
        ?array $oldValues = null,
        ?array $newValues = null
    ): void {
        $user = auth()->user();

        $this->auditLogRepository->create([
            'user_id'    => $user?->id,
            'user_name'  => $user?->name ?? 'System',
            'action'     => $action,
            'model_type' => $modelType,
            'model_id'   => $modelId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'description'=> $description,
        ]);
    }
}
