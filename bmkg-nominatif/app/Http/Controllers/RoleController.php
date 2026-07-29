<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Role;
use App\Services\RoleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function __construct(
        protected RoleService $roleService
    ) {}

    public function index(): View
    {
        $this->authorize('viewAny', Role::class);

        $roles = $this->roleService->getAllRoles();

        return view('roles.index', compact('roles'));
    }

    public function store(StoreRoleRequest $request): RedirectResponse
    {
        $this->authorize('create', Role::class);

        $role = $this->roleService->createRole($request->validated());

        return redirect()->route('roles.index')
            ->with('success', "Role {$role->name} berhasil ditambahkan.");
    }

    public function update(UpdateRoleRequest $request, int $id): RedirectResponse
    {
        $roleModel = Role::findOrFail($id);
        $this->authorize('update', $roleModel);

        $role = $this->roleService->updateRole($id, $request->validated());

        return redirect()->route('roles.index')
            ->with('success', "Role {$role->name} berhasil diperbarui.");
    }

    public function destroy(int $id): RedirectResponse
    {
        $roleModel = Role::findOrFail($id);
        $this->authorize('delete', $roleModel);

        try {
            $this->roleService->deleteRole($id);
            return redirect()->route('roles.index')->with('success', 'Role berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('roles.index')->with('error', $e->getMessage());
        }
    }
}
