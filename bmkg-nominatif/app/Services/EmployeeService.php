<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\EmployeeEducation;
use App\Models\EmployeeFamily;
use App\Models\EmployeePosition;
use App\Models\EmployeeRank;
use App\Repositories\Interfaces\EmployeeRepositoryInterface;
use App\Repositories\Interfaces\MasterDataRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EmployeeService
{
    public function __construct(
        protected EmployeeRepositoryInterface $employeeRepository,
        protected MasterDataRepositoryInterface $masterDataRepository,
        protected AuditLogService $auditLogService
    ) {}

    public function getPaginatedEmployees(array $filters, int $perPage = 25): LengthAwarePaginator
    {
        return $this->employeeRepository->getAllPaginated($filters, $perPage);
    }

    public function getEmployeeDetail(int $id): ?Employee
    {
        return $this->employeeRepository->findById($id);
    }

    public function createEmployee(array $data): Employee
    {
        return DB::transaction(function () use ($data) {
            $employeeData = [
                'nip'                  => $data['nip'],
                'full_name'            => $data['full_name'],
                'prefix_title'         => $data['prefix_title'] ?? null,
                'suffix_title'         => $data['suffix_title'] ?? null,
                'birth_place'          => $data['birth_place'],
                'birth_date'           => $data['birth_date'],
                'gender_id'            => $data['gender_id'],
                'religion_id'          => $data['religion_id'],
                'marital_status_id'    => $data['marital_status_id'],
                'address'              => $data['address'] ?? null,
                'phone'                => $data['phone'] ?? null,
                'email'                => $data['email'] ?? null,
                'employment_status_id' => $data['employment_status_id'],
                'work_unit_id'         => $data['work_unit_id'],
                'created_by'           => auth()->id(),
            ];

            $employee = $this->employeeRepository->create($employeeData);

            // Add Initial Rank if provided
            if (!empty($data['rank_id'])) {
                EmployeeRank::create([
                    'employee_id'    => $employee->id,
                    'rank_id'        => $data['rank_id'],
                    'decree_number'  => $data['rank_decree_number'] ?? null,
                    'decree_date'    => $data['rank_decree_date'] ?? null,
                    'effective_date' => $data['rank_effective_date'] ?? now(),
                    'is_current'     => true,
                ]);
            }

            // Add Initial Position if provided
            if (!empty($data['position_id'])) {
                EmployeePosition::create([
                    'employee_id'    => $employee->id,
                    'position_id'    => $data['position_id'],
                    'work_unit_id'   => $data['work_unit_id'],
                    'decree_number'  => $data['position_decree_number'] ?? null,
                    'decree_date'    => $data['position_decree_date'] ?? null,
                    'effective_date' => $data['position_effective_date'] ?? now(),
                    'is_current'     => true,
                ]);
            }

            // Add Initial Education if provided
            if (!empty($data['education_id'])) {
                EmployeeEducation::create([
                    'employee_id'        => $employee->id,
                    'education_id'       => $data['education_id'],
                    'institution_name'   => !empty($data['institution_name']) ? $data['institution_name'] : '-',
                    'major'              => $data['major'] ?? null,
                    'year_graduated'     => $data['year_graduated'] ?? null,
                    'certificate_number' => $data['certificate_number'] ?? null,
                ]);
            }

            $this->auditLogService->log(
                action: 'CREATE',
                description: "Menambahkan data pegawai baru: {$employee->full_name} (NIP: {$employee->nip})",
                modelType: Employee::class,
                modelId: $employee->id,
                newValues: $employee->toArray()
            );

            return $employee;
        });
    }

    public function updateEmployee(int $id, array $data): Employee
    {
        return DB::transaction(function () use ($id, $data) {
            $employee = $this->employeeRepository->findById($id);
            $oldValues = $employee->toArray();

            $updateData = [
                'nip'                  => $data['nip'],
                'full_name'            => $data['full_name'],
                'prefix_title'         => $data['prefix_title'] ?? null,
                'suffix_title'         => $data['suffix_title'] ?? null,
                'birth_place'          => $data['birth_place'],
                'birth_date'           => $data['birth_date'],
                'gender_id'            => $data['gender_id'],
                'religion_id'          => $data['religion_id'],
                'marital_status_id'    => $data['marital_status_id'],
                'address'              => $data['address'] ?? null,
                'phone'                => $data['phone'] ?? null,
                'email'                => $data['email'] ?? null,
                'employment_status_id' => $data['employment_status_id'],
                'work_unit_id'         => $data['work_unit_id'],
                'updated_by'           => auth()->id(),
            ];

            $updatedEmployee = $this->employeeRepository->update($id, $updateData);

            if (!empty($data['rank_id'])) {
                EmployeeRank::where('employee_id', $id)->update(['is_current' => false]);
                EmployeeRank::create([
                    'employee_id'    => $id,
                    'rank_id'        => $data['rank_id'],
                    'decree_number'  => $data['rank_decree_number'] ?? null,
                    'decree_date'    => $data['rank_decree_date'] ?? null,
                    'effective_date' => $data['rank_effective_date'] ?? now(),
                    'is_current'     => true,
                ]);
            }

            if (!empty($data['position_id'])) {
                EmployeePosition::where('employee_id', $id)->update(['is_current' => false]);
                EmployeePosition::create([
                    'employee_id'    => $id,
                    'position_id'    => $data['position_id'],
                    'work_unit_id'   => $data['work_unit_id'],
                    'decree_number'  => $data['position_decree_number'] ?? null,
                    'decree_date'    => $data['position_decree_date'] ?? null,
                    'effective_date' => $data['position_effective_date'] ?? now(),
                    'is_current'     => true,
                ]);
            }

            if (!empty($data['education_id'])) {
                EmployeeEducation::where('employee_id', $id)->delete();
                EmployeeEducation::create([
                    'employee_id'        => $id,
                    'education_id'       => $data['education_id'],
                    'institution_name'   => !empty($data['institution_name']) ? $data['institution_name'] : '-',
                    'major'              => $data['major'] ?? null,
                    'year_graduated'     => $data['year_graduated'] ?? null,
                    'certificate_number' => $data['certificate_number'] ?? null,
                ]);
            }

            $this->auditLogService->log(
                action: 'UPDATE',
                description: "Mengubah data pegawai: {$updatedEmployee->full_name} (NIP: {$updatedEmployee->nip})",
                modelType: Employee::class,
                modelId: $updatedEmployee->id,
                oldValues: $oldValues,
                newValues: $updatedEmployee->toArray()
            );

            return $updatedEmployee;
        });
    }

    public function deleteEmployee(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $employee = $this->employeeRepository->findById($id);
            if (!$employee) {
                return false;
            }

            $name = $employee->full_name;
            $nip = $employee->nip;

            $result = $this->employeeRepository->delete($id);

            $this->auditLogService->log(
                action: 'DELETE',
                description: "Menghapus data pegawai: {$name} (NIP: {$nip})",
                modelType: Employee::class,
                modelId: $id
            );

            return $result;
        });
    }

    public function addEducation(int $employeeId, array $data): EmployeeEducation
    {
        $data['employee_id'] = $employeeId;
        $edu = EmployeeEducation::create($data);

        $this->auditLogService->log(
            action: 'CREATE',
            description: "Menambahkan riwayat pendidikan untuk pegawai ID {$employeeId}",
            modelType: EmployeeEducation::class,
            modelId: $edu->id
        );

        return $edu;
    }

    public function addRank(int $employeeId, array $data): EmployeeRank
    {
        return DB::transaction(function () use ($employeeId, $data) {
            if (!empty($data['is_current'])) {
                EmployeeRank::where('employee_id', $employeeId)->update(['is_current' => false]);
            }

            $data['employee_id'] = $employeeId;
            $rank = EmployeeRank::create($data);

            $this->auditLogService->log(
                action: 'CREATE',
                description: "Menambahkan riwayat golongan/pangkat untuk pegawai ID {$employeeId}",
                modelType: EmployeeRank::class,
                modelId: $rank->id
            );

            return $rank;
        });
    }

    public function addPosition(int $employeeId, array $data): EmployeePosition
    {
        return DB::transaction(function () use ($employeeId, $data) {
            if (!empty($data['is_current'])) {
                EmployeePosition::where('employee_id', $employeeId)->update(['is_current' => false]);
            }

            $data['employee_id'] = $employeeId;
            $pos = EmployeePosition::create($data);

            $this->auditLogService->log(
                action: 'CREATE',
                description: "Menambahkan riwayat jabatan untuk pegawai ID {$employeeId}",
                modelType: EmployeePosition::class,
                modelId: $pos->id
            );

            return $pos;
        });
    }

    public function addFamily(int $employeeId, array $data): EmployeeFamily
    {
        $data['employee_id'] = $employeeId;
        $fam = EmployeeFamily::create($data);

        $this->auditLogService->log(
            action: 'CREATE',
            description: "Menambahkan data keluarga untuk pegawai ID {$employeeId}",
            modelType: EmployeeFamily::class,
            modelId: $fam->id
        );

        return $fam;
    }

    public function getDashboardStats(): array
    {
        // 1. Education Stats (SMA/SMK, D1, D2, D3, S1, S2, S3)
        $levels = [
            'SMA' => ['label' => 'SMA / SMK', 'full_label' => 'Sekolah Menengah Atas / Kejuruan', 'count' => 0, 'color_hex' => '#f97316'],
            'D1'  => ['label' => 'Diploma I (D1)', 'full_label' => 'Diploma Satu (D1)', 'count' => 0, 'color_hex' => '#06b6d4'],
            'D2'  => ['label' => 'Diploma II (D2)', 'full_label' => 'Diploma Dua (D2)', 'count' => 0, 'color_hex' => '#14b8a6'],
            'D3'  => ['label' => 'Diploma III (D3)', 'full_label' => 'Diploma Tiga (D3)', 'count' => 0, 'color_hex' => '#0284c7'],
            'S1'  => ['label' => 'Sarjana (S1)', 'full_label' => 'Sarjana (S1)', 'count' => 0, 'color_hex' => '#2563eb'],
            'S2'  => ['label' => 'Magister (S2)', 'full_label' => 'Magister (S2)', 'count' => 0, 'color_hex' => '#10b981'],
            'S3'  => ['label' => 'Doktor (S3)', 'full_label' => 'Doktor (S3)', 'count' => 0, 'color_hex' => '#7c3aed'],
        ];

        $educations = DB::table('employee_educations')
            ->join('educations', 'educations.id', '=', 'employee_educations.education_id')
            ->select('educations.name', 'educations.code')
            ->get();

        foreach ($educations as $e) {
            $n = strtoupper($e->name);
            $c = strtoupper($e->code);

            if (str_contains($n, 'S3') || str_contains($n, 'S.3') || str_contains($n, 'DOKTOR') || str_contains($n, 'STRATA 3') || $c === 'S3') {
                $levels['S3']['count']++;
            } elseif (str_contains($n, 'S2') || str_contains($n, 'S.2') || str_contains($n, 'MAGISTER') || str_contains($n, 'STRATA 2') || $c === 'S2') {
                $levels['S2']['count']++;
            } elseif (str_contains($n, 'S1') || str_contains($n, 'S.1') || str_contains($n, 'SARJANA') || str_contains($n, 'STRATA 1') || str_contains($n, 'D4') || str_contains($n, 'D.IV') || $c === 'S1' || $c === 'D4') {
                $levels['S1']['count']++;
            } elseif (str_contains($n, 'D3') || str_contains($n, 'D.III') || str_contains($n, 'DIPLOMA TIGA') || $c === 'D3') {
                $levels['D3']['count']++;
            } elseif (str_contains($n, 'D2') || str_contains($n, 'D.II') || str_contains($n, 'DIPLOMA DUA') || $c === 'D2') {
                $levels['D2']['count']++;
            } elseif (str_contains($n, 'D1') || str_contains($n, 'D.I') || str_contains($n, 'DIPLOMA SATU') || $c === 'D1') {
                $levels['D1']['count']++;
            } elseif (str_contains($n, 'SMA') || str_contains($n, 'SMK') || str_contains($n, 'STM') || str_contains($n, 'SLTA') || $c === 'SMA' || $c === 'SMK') {
                $levels['SMA']['count']++;
            }
        }

        $educationStats = array_values($levels);
        $totalEduCount = array_sum(array_column($educationStats, 'count')) ?: 1;

        foreach ($educationStats as &$eduItem) {
            $pct = round(($eduItem['count'] / $totalEduCount) * 100, 1);
            $eduItem['percentage'] = $pct;
            $eduItem['bar_width'] = $eduItem['count'] > 0 ? max(3, $pct) : 0;
        }
        unset($eduItem);

        // 2. Position Stats (Semua Jabatan)
        $rawPositions = DB::table('employee_positions')
            ->where('employee_positions.is_current', true)
            ->join('positions', 'positions.id', '=', 'employee_positions.position_id')
            ->select('positions.name', DB::raw('count(*) as total'))
            ->groupBy('positions.name')
            ->orderByDesc('total')
            ->get();

        $positionStats = [];
        $totalPosEmployees = 0;
        $hexPalette = [
            '#2563eb', '#4f46e5', '#0284c7', '#0d9488',
            '#059669', '#d97706', '#7c3aed', '#c026d3',
            '#e11d48', '#475569'
        ];

        foreach ($rawPositions as $idx => $pos) {
            $count = (int)$pos->total;
            $totalPosEmployees += $count;
            $positionStats[] = [
                'label' => $pos->name,
                'count' => $count,
                'color_hex' => $hexPalette[$idx % count($hexPalette)],
            ];
        }

        $divisorPosTotal = $totalPosEmployees ?: 1;

        foreach ($positionStats as &$posItem) {
            $pct = round(($posItem['count'] / $divisorPosTotal) * 100, 1);
            $posItem['percentage'] = $pct;
            $posItem['bar_width'] = $posItem['count'] > 0 ? max(3, $pct) : 0;
        }
        unset($posItem);

        // 3. Rank / Golongan Stats (Golongan I - IV)
        $rawRanks = DB::table('employee_ranks')
            ->where('employee_ranks.is_current', true)
            ->join('ranks', 'ranks.id', '=', 'employee_ranks.rank_id')
            ->select('ranks.code', 'ranks.name', DB::raw('count(*) as total'))
            ->groupBy('ranks.code', 'ranks.name')
            ->orderBy('ranks.code')
            ->get();

        $rankGroups = [
            'IV'  => ['label' => 'Golongan IV (Pembina)', 'count' => 0, 'color_hex' => '#7c3aed', 'items' => []],
            'III' => ['label' => 'Golongan III (Penata)', 'count' => 0, 'color_hex' => '#2563eb', 'items' => []],
            'II'  => ['label' => 'Golongan II (Pengatur)', 'count' => 0, 'color_hex' => '#0284c7', 'items' => []],
            'I'   => ['label' => 'Golongan I (Juru)', 'count' => 0, 'color_hex' => '#059669', 'items' => []],
        ];

        $totalRankEmployees = 0;
        foreach ($rawRanks as $r) {
            $code = strtoupper(trim($r->code));
            $cnt = (int)$r->total;
            $totalRankEmployees += $cnt;

            if (str_starts_with($code, 'IV') || str_starts_with($code, '4')) {
                $rankGroups['IV']['count'] += $cnt;
                $rankGroups['IV']['items'][] = ['code' => $r->code, 'name' => $r->name, 'count' => $cnt];
            } elseif (str_starts_with($code, 'III') || str_starts_with($code, '3')) {
                $rankGroups['III']['count'] += $cnt;
                $rankGroups['III']['items'][] = ['code' => $r->code, 'name' => $r->name, 'count' => $cnt];
            } elseif (str_starts_with($code, 'II') || str_starts_with($code, '2')) {
                $rankGroups['II']['count'] += $cnt;
                $rankGroups['II']['items'][] = ['code' => $r->code, 'name' => $r->name, 'count' => $cnt];
            } elseif (str_starts_with($code, 'I') || str_starts_with($code, '1')) {
                $rankGroups['I']['count'] += $cnt;
                $rankGroups['I']['items'][] = ['code' => $r->code, 'name' => $r->name, 'count' => $cnt];
            }
        }

        $divRankTotal = $totalRankEmployees ?: 1;
        foreach ($rankGroups as &$rg) {
            $pct = round(($rg['count'] / $divRankTotal) * 100, 1);
            $rg['percentage'] = $pct;
            $rg['bar_width'] = $rg['count'] > 0 ? max(4, $pct) : 0;
        }
        unset($rg);

        // 4. Gender Demographics
        $maleCount = DB::table('employees')
            ->whereNull('deleted_at')
            ->join('genders', 'genders.id', '=', 'employees.gender_id')
            ->whereRaw('LOWER(genders.name) LIKE ?', ['%laki%'])
            ->count();

        $femaleCount = DB::table('employees')
            ->whereNull('deleted_at')
            ->join('genders', 'genders.id', '=', 'employees.gender_id')
            ->whereRaw('LOWER(genders.name) LIKE ?', ['%perempuan%'])
            ->count();

        $totalGender = ($maleCount + $femaleCount) ?: 1;
        $genderStats = [
            'male_count'   => $maleCount,
            'male_pct'     => round(($maleCount / $totalGender) * 100),
            'female_count' => $femaleCount,
            'female_pct'   => round(($femaleCount / $totalGender) * 100),
            'total'        => $maleCount + $femaleCount,
        ];

        // 5. Employment Status Demographics
        $statusCounts = DB::table('employees')
            ->whereNull('deleted_at')
            ->join('employment_statuses', 'employment_statuses.id', '=', 'employees.employment_status_id')
            ->select('employment_statuses.name', DB::raw('count(*) as total'))
            ->groupBy('employment_statuses.name')
            ->orderByDesc('total')
            ->get();

        $statusStats = [];
        $totalStatusEmployees = 0;
        $statusPalette = ['#0284c7', '#10b981', '#f59e0b', '#8b5cf6', '#ef4444'];
        foreach ($statusCounts as $sidx => $st) {
            $scnt = (int)$st->total;
            $totalStatusEmployees += $scnt;
            $statusStats[] = [
                'name' => $st->name,
                'count' => $scnt,
                'color_hex' => $statusPalette[$sidx % count($statusPalette)],
            ];
        }
        $divStatusTotal = $totalStatusEmployees ?: 1;
        foreach ($statusStats as &$stItem) {
            $pct = round(($stItem['count'] / $divStatusTotal) * 100, 1);
            $stItem['percentage'] = $pct;
            $stItem['bar_width'] = $stItem['count'] > 0 ? max(4, $pct) : 0;
        }
        unset($stItem);

        return [
            'total_employees'    => $this->employeeRepository->countAll(),
            'active_employees'   => $this->employeeRepository->countActive(),
            'inactive_employees' => $this->employeeRepository->countInactive(),
            'total_users'        => \App\Models\User::count(),
            'total_salary_increases' => \App\Models\SalaryHistory::distinct('employee_id')->count('employee_id'),
            'total_positions'    => $this->masterDataRepository->countPositions(),
            'total_work_units'   => $this->masterDataRepository->countWorkUnits(),
            'total_ranks'        => $this->masterDataRepository->countRanks(),
            'education_stats'    => $educationStats,
            'position_stats'     => $positionStats,
            'rank_stats'         => $rankGroups,
            'total_rank_count'   => $totalRankEmployees,
            'gender_stats'       => $genderStats,
            'status_stats'       => $statusStats,
        ];
    }
}
