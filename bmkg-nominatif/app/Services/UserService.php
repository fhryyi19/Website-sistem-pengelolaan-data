<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected AuditLogService $auditLogService
    ) {}

    public function getPaginatedUsers(array $filters, int $perPage = 25): LengthAwarePaginator
    {
        return $this->userRepository->getAllPaginated($filters, $perPage);
    }

    public function createUser(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $userData = [
                'name'      => $data['name'],
                'email'     => strtolower($data['email']),
                'username'  => strtolower($data['username']),
                'nip'       => !empty($data['nip']) ? $data['nip'] : null,
                'role_id'   => $data['role_id'],
                'password'  => Hash::make($data['password']),
                'is_active' => $data['is_active'] ?? true,
            ];

            $user = $this->userRepository->create($userData);

            $this->auditLogService->log(
                action: 'CREATE',
                description: "Menambahkan user baru: {$user->name} ({$user->username})",
                modelType: User::class,
                modelId: $user->id
            );

            return $user;
        });
    }

    public function updateUser(int $id, array $data): User
    {
        return DB::transaction(function () use ($id, $data) {
            $user = $this->userRepository->findById($id);
            $oldValues = $user->toArray();

            $updateData = [
                'name'      => $data['name'],
                'email'     => strtolower($data['email']),
                'username'  => strtolower($data['username']),
                'nip'       => !empty($data['nip']) ? $data['nip'] : null,
                'role_id'   => $data['role_id'],
                'is_active' => $data['is_active'] ?? $user->is_active,
            ];

            if (!empty($data['password'])) {
                $updateData['password'] = Hash::make($data['password']);
            }

            $updatedUser = $this->userRepository->update($id, $updateData);

            $this->auditLogService->log(
                action: 'UPDATE',
                description: "Mengubah data user: {$updatedUser->name} ({$updatedUser->username})",
                modelType: User::class,
                modelId: $updatedUser->id,
                oldValues: $oldValues,
                newValues: $updatedUser->toArray()
            );

            return $updatedUser;
        });
    }

    public function resetPassword(int $id, string $newPassword): User
    {
        return DB::transaction(function () use ($id, $newPassword) {
            $user = $this->userRepository->findById($id);

            $updatedUser = $this->userRepository->update($id, [
                'password' => Hash::make($newPassword),
            ]);

            $this->auditLogService->log(
                action: 'RESET_PASSWORD',
                description: "Reset password untuk user: {$user->name} ({$user->username})",
                modelType: User::class,
                modelId: $id
            );

            return $updatedUser;
        });
    }

    public function toggleActiveStatus(int $id): User
    {
        return DB::transaction(function () use ($id) {
            $user = $this->userRepository->toggleActive($id);
            $status = $user->is_active ? 'mengaktifkan' : 'menonaktifkan';

            $this->auditLogService->log(
                action: 'UPDATE',
                description: "Berhasil {$status} user: {$user->name} ({$user->username})",
                modelType: User::class,
                modelId: $id
            );

            return $user;
        });
    }

    public function deleteUser(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $user = $this->userRepository->findById($id);
            if (!$user) {
                return false;
            }

            $name = $user->name;
            $username = $user->username;

            $result = $this->userRepository->delete($id);

            $this->auditLogService->log(
                action: 'DELETE',
                description: "Menghapus user: {$name} ({$username})",
                modelType: User::class,
                modelId: $id
            );

            return $result;
        });
    }
}
