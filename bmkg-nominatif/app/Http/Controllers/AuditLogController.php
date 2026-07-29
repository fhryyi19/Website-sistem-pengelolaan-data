<?php

namespace App\Http\Controllers;

use App\Services\AuditLogService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    public function __construct(
        protected AuditLogService $auditLogService,
        protected UserService $userService
    ) {}

    public function index(Request $request): View
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Akses khusus Administrator.');
        }

        $filters = $request->only(['search', 'action', 'user_id', 'date_from', 'date_to']);
        $logs    = $this->auditLogService->getPaginatedLogs($filters, 25);
        $users   = $this->userService->getPaginatedUsers([], 100);

        return view('audit-logs.index', compact('logs', 'filters', 'users'));
    }
}
