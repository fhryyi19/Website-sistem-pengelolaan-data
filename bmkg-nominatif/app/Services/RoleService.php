<?php

namespace App\Services;

use App\Models\Role;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class RoleService
{
    public function __construct(
        protected RoleRepositoryInterface $roleRepository,
        protected AuditLogService $auditLogService
    ) {}

    public function getAllRoles(): Collection
    {
        return $this->roleRepository->getAll();
    }

    public function createRole(array $data): Role
    {
        return DB::transaction(function () use ($data) {
            $role = $this->roleRepository->create($data);

            $this->auditLogService->log(
                action: 'CREATE',
                description: "Menambahkan role baru: {$role->name}",
                modelType: Role::class,
                modelId: $role->id
            );

            return $role;
        });
    }

    public function updateRole(int $id, array $data): Role
    {
        return DB::transaction(function () use ($id, $data) {
            $role = $this->roleRepository->update($id, $data);

            $this->auditLogService->log(
                action: 'UPDATE',
                description: "Mengubah role: {$role->name}",
                modelType: Role::class,
                modelId: $role->id
            );

            return $role;
        });
    }

    public function deleteRole(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $role = $this->roleRepository->findById($id);

            if (!$role || $role->users_count > 0 || in_array($role->name, ['admin', 'user'])) {
                throw new \Exception('Role ini tidak dapat dihapus karena sedang digunakan atau merupakan role bawaan sistem.');
            }

            $name = $role->name;
            $result = $this->roleRepository->delete($id);

            $this->auditLogService->log(
                action: 'DELETE',
                description: "Menghapus role: {$name}",
                modelType: Role::class,
                modelId: $id
            );

            return $result;
        });
    }
}
