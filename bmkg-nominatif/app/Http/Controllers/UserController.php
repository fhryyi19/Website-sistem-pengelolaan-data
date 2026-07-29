<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\RoleService;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService,
        protected RoleService $roleService
    ) {}

    public function index(Request $request): View
    {
        $this->authorize('viewAny', User::class);

        $filters = $request->only(['search', 'role_id', 'is_active']);
        $users   = $this->userService->getPaginatedUsers($filters, 25);
        $roles   = $this->roleService->getAllRoles();

        return view('users.index', compact('users', 'filters', 'roles'));
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $this->authorize('create', User::class);

        $user = $this->userService->createUser($request->validated());

        return redirect()->route('users.index')
            ->with('success', "User {$user->name} berhasil ditambahkan.");
    }

    public function update(UpdateUserRequest $request, int $id): RedirectResponse
    {
        $userModel = User::findOrFail($id);
        $this->authorize('update', $userModel);

        $user = $this->userService->updateUser($id, $request->validated());

        return redirect()->route('users.index')
            ->with('success', "User {$user->name} berhasil diperbarui.");
    }

    public function resetPassword(Request $request, int $id): RedirectResponse
    {
        $userModel = User::findOrFail($id);
        $this->authorize('resetPassword', $userModel);

        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'password.required'  => 'Password baru wajib diisi.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $this->userService->resetPassword($id, $request->password);

        return redirect()->route('users.index')
            ->with('success', "Password untuk user {$userModel->name} berhasil direset.");
    }

    public function toggleActive(int $id): RedirectResponse
    {
        $userModel = User::findOrFail($id);
        $this->authorize('toggleActive', $userModel);

        $user = $this->userService->toggleActiveStatus($id);
        $statusText = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('users.index')
            ->with('success', "Status user {$user->name} berhasil {$statusText}.");
    }

    public function destroy(int $id): RedirectResponse
    {
        $userModel = User::findOrFail($id);
        $this->authorize('delete', $userModel);

        $this->userService->deleteUser($id);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}
