<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Employee;
use App\Repositories\Interfaces\MasterDataRepositoryInterface;
use App\Services\EmployeeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function __construct(
        protected EmployeeService $employeeService,
        protected MasterDataRepositoryInterface $masterDataRepository
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->only([
            'search', 'employment_status_id', 'work_unit_id',
            'gender_id', 'religion_id', 'rank_id', 'position_id',
            'sort_by', 'sort_dir',
        ]);

        $perPage = (int) $request->get('per_page', config('nominatif.pagination.default', 25));
        $employees = $this->employeeService->getPaginatedEmployees($filters, $perPage);

        $employmentStatuses = $this->masterDataRepository->getEmploymentStatuses();
        $workUnits          = $this->masterDataRepository->getWorkUnits();
        $genders             = $this->masterDataRepository->getGenders();
        $religions           = $this->masterDataRepository->getReligions();
        $ranks               = $this->masterDataRepository->getRanks();
        $positions           = $this->masterDataRepository->getPositions();

        return view('employees.index', compact(
            'employees', 'filters', 'perPage',
            'employmentStatuses', 'workUnits', 'genders',
            'religions', 'ranks', 'positions'
        ));
    }

    public function create(): View
    {
        $this->authorize('create', Employee::class);

        $genders            = $this->masterDataRepository->getGenders();
        $religions          = $this->masterDataRepository->getReligions();
        $maritalStatuses    = $this->masterDataRepository->getMaritalStatuses();
        $employmentStatuses = $this->masterDataRepository->getEmploymentStatuses();
        $workUnits          = $this->masterDataRepository->getWorkUnits();
        $ranks              = $this->masterDataRepository->getRanks();
        $positions          = $this->masterDataRepository->getPositions();

        return view('employees.create', compact(
            'genders', 'religions', 'maritalStatuses',
            'employmentStatuses', 'workUnits', 'ranks', 'positions'
        ));
    }

    public function store(StoreEmployeeRequest $request): RedirectResponse
    {
        $employee = $this->employeeService->createEmployee($request->validated());

        return redirect()->route('employees.show', $employee->id)
            ->with('success', "Data pegawai {$employee->full_name} berhasil ditambahkan.");
    }

    public function show(int $id): View
    {
        $employee = $this->employeeService->getEmployeeDetail($id);

        if (!$employee) {
            abort(404, 'Data pegawai tidak ditemukan.');
        }

        $educationsList  = $this->masterDataRepository->getEducations();
        $ranksList       = $this->masterDataRepository->getRanks();
        $positionsList   = $this->masterDataRepository->getPositions();
        $workUnitsList   = $this->masterDataRepository->getWorkUnits();
        $gendersList     = $this->masterDataRepository->getGenders();

        return view('employees.show', compact(
            'employee', 'educationsList', 'ranksList',
            'positionsList', 'workUnitsList', 'gendersList'
        ));
    }

    public function edit(int $id): View
    {
        $employee = $this->employeeService->getEmployeeDetail($id);

        if (!$employee) {
            abort(404, 'Data pegawai tidak ditemukan.');
        }

        $this->authorize('update', $employee);

        $genders            = $this->masterDataRepository->getGenders();
        $religions          = $this->masterDataRepository->getReligions();
        $maritalStatuses    = $this->masterDataRepository->getMaritalStatuses();
        $employmentStatuses = $this->masterDataRepository->getEmploymentStatuses();
        $workUnits          = $this->masterDataRepository->getWorkUnits();

        return view('employees.edit', compact(
            'employee', 'genders', 'religions',
            'maritalStatuses', 'employmentStatuses', 'workUnits'
        ));
    }

    public function update(UpdateEmployeeRequest $request, int $id): RedirectResponse
    {
        $employee = $this->employeeService->updateEmployee($id, $request->validated());

        return redirect()->route('employees.show', $employee->id)
            ->with('success', "Data pegawai {$employee->full_name} berhasil diperbarui.");
    }

    public function destroy(int $id): RedirectResponse
    {
        $employee = Employee::findOrFail($id);
        $this->authorize('delete', $employee);

        $this->employeeService->deleteEmployee($id);

        return redirect()->route('employees.index')
            ->with('success', 'Data pegawai berhasil dihapus.');
    }

    public function storeEducation(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'education_id'       => ['required', 'exists:educations,id'],
            'institution_name'   => ['required', 'string', 'max:200'],
            'major'              => ['nullable', 'string', 'max:150'],
            'year_graduated'     => ['nullable', 'numeric', 'digits:4'],
            'certificate_number' => ['nullable', 'string', 'max:100'],
        ]);

        $this->employeeService->addEducation($id, $validated);

        return back()->with('success', 'Riwayat pendidikan berhasil ditambahkan.');
    }

    public function storeRank(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'rank_id'        => ['required', 'exists:ranks,id'],
            'decree_number'  => ['nullable', 'string', 'max:100'],
            'decree_date'    => ['nullable', 'date'],
            'effective_date' => ['required', 'date'],
            'is_current'     => ['nullable', 'boolean'],
        ]);

        $validated['is_current'] = $request->boolean('is_current');

        $this->employeeService->addRank($id, $validated);

        return back()->with('success', 'Riwayat golongan/pangkat berhasil ditambahkan.');
    }

    public function storePosition(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'position_id'    => ['required', 'exists:positions,id'],
            'work_unit_id'   => ['required', 'exists:work_units,id'],
            'decree_number'  => ['nullable', 'string', 'max:100'],
            'decree_date'    => ['nullable', 'date'],
            'effective_date' => ['required', 'date'],
            'is_current'     => ['nullable', 'boolean'],
        ]);

        $validated['is_current'] = $request->boolean('is_current');

        $this->employeeService->addPosition($id, $validated);

        return back()->with('success', 'Riwayat jabatan berhasil ditambahkan.');
    }

    public function storeFamily(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:150'],
            'relationship' => ['required', 'string', 'max:50'],
            'gender_id'    => ['required', 'exists:genders,id'],
            'birth_date'   => ['nullable', 'date'],
            'occupation'   => ['nullable', 'string', 'max:100'],
        ]);

        $this->employeeService->addFamily($id, $validated);

        return back()->with('success', 'Data anggota keluarga berhasil ditambahkan.');
    }
}
