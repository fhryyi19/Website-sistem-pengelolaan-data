<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\User;

class EmployeePolicy
{
    public function viewAny(User $user): bool
    {
        return true; // Admin and User can view all employees
    }

    public function view(User $user, Employee $employee): bool
    {
        return true; // Admin and User can view detail
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Employee $employee): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Employee $employee): bool
    {
        return $user->isAdmin();
    }

    public function export(User $user): bool
    {
        return true; // Both admin and user can export if allowed or admin only
    }
}
