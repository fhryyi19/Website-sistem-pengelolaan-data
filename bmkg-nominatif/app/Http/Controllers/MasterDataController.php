<?php

namespace App\Http\Controllers;

use App\Models\Education;
use App\Models\EmploymentStatus;
use App\Models\Position;
use App\Models\Rank;
use App\Models\WorkUnit;
use App\Repositories\Interfaces\MasterDataRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class MasterDataController extends Controller
{
    public function __construct(
        protected MasterDataRepositoryInterface $masterDataRepository
    ) {}

    public function index(): View
    {
        $genders            = $this->masterDataRepository->getGenders();
        $religions          = $this->masterDataRepository->getReligions();
        $maritalStatuses    = $this->masterDataRepository->getMaritalStatuses();
        $employmentStatuses = $this->masterDataRepository->getEmploymentStatuses();
        $educations         = $this->masterDataRepository->getEducations();
        $ranks              = $this->masterDataRepository->getRanks();
        $positions          = $this->masterDataRepository->getPositions();
        $workUnits          = $this->masterDataRepository->getWorkUnits();

        return view('master.index', compact(
            'genders', 'religions', 'maritalStatuses',
            'employmentStatuses', 'educations', 'ranks',
            'positions', 'workUnits'
        ));
    }

    // ── Quick-add master data dari halaman filter (Admin only) ──

    public function storeEducation(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code'  => ['required', 'string', 'max:20', 'unique:educations,code'],
            'name'  => ['required', 'string', 'max:100', 'unique:educations,name'],
            'level' => ['required', 'integer', 'min:1', 'max:10'],
        ]);

        Education::create($validated);
        Cache::forget('master_educations');
        Cache::forget('filter_educations');

        return redirect($request->input('redirect_to', route('employees.index')))
            ->with('success', "Jenjang pendidikan \"{$validated['name']}\" berhasil ditambahkan.");
    }

    public function storeRank(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code'  => ['required', 'string', 'max:10', 'unique:ranks,code'],
            'name'  => ['required', 'string', 'max:100'],
            'group' => ['required', 'string', 'max:5'],
        ]);

        Rank::create($validated);
        Cache::forget('master_ranks');
        Cache::forget('filter_ranks_current');
        Cache::forget('filter_ranks_all');

        return redirect($request->input('redirect_to', route('employees.index')))
            ->with('success', "Pangkat/Golongan \"{$validated['code']} - {$validated['name']}\" berhasil ditambahkan.");
    }

    public function storePosition(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code'  => ['required', 'string', 'max:30', 'unique:positions,code'],
            'name'  => ['required', 'string', 'max:200'],
            'level' => ['nullable', 'string', 'max:50'],
        ]);

        Position::create($validated);
        Cache::forget('master_positions');
        Cache::forget('filter_positions_current');
        Cache::forget('filter_positions_all');

        return redirect($request->input('redirect_to', route('employees.index')))
            ->with('success', "Jabatan \"{$validated['name']}\" berhasil ditambahkan.");
    }

    public function storeWorkUnit(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:30', 'unique:work_units,code'],
            'name' => ['required', 'string', 'max:200'],
        ]);

        WorkUnit::create($validated);
        Cache::forget('master_work_units');
        Cache::forget('filter_work_units');

        return redirect($request->input('redirect_to', route('employees.index')))
            ->with('success', "Unit Kerja \"{$validated['name']}\" berhasil ditambahkan.");
    }

    public function storeEmploymentStatus(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:20', 'unique:employment_statuses,code'],
            'name' => ['required', 'string', 'max:100', 'unique:employment_statuses,name'],
        ]);

        EmploymentStatus::create($validated);
        Cache::forget('master_employment_statuses');
        Cache::forget('filter_employment_statuses');

        return redirect($request->input('redirect_to', route('employees.index')))
            ->with('success', "Status kepegawaian \"{$validated['name']}\" berhasil ditambahkan.");
    }
}
