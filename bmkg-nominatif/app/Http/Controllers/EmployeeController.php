<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Education;
use App\Models\Employee;
use App\Repositories\Interfaces\MasterDataRepositoryInterface;
use App\Services\EmployeeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\URL;

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
            // Filter pendidikan
            'education_level', 'education_id', 'institution_name',
            // Filter riwayat kepangkatan & jabatan (historis)
            'rank_id_history', 'rank_date_from', 'rank_date_to',
            'position_id_history', 'position_date_from', 'position_date_to',
        ]);

        $perPage = (int) $request->get('per_page', config('nominatif.pagination.default', 25));
        $employees = $this->employeeService->getPaginatedEmployees($filters, $perPage);

        $employmentStatuses = $this->masterDataRepository->getUsedEmploymentStatuses();
        $workUnits          = $this->masterDataRepository->getUsedWorkUnits();
        $genders            = $this->masterDataRepository->getGenders();
        $religions          = $this->masterDataRepository->getReligions();
        $ranksActive        = $this->masterDataRepository->getUsedRanks();      // Golongan aktif (is_current)
        $ranksAll           = $this->masterDataRepository->getUsedRanksAll();   // Semua historis
        $positionsActive    = $this->masterDataRepository->getUsedPositions();  // Jabatan aktif (is_current)
        $positionsAll       = $this->masterDataRepository->getUsedPositionsAll(); // Semua historis
        $educations         = $this->masterDataRepository->getUsedEducations();
        // Untuk modal tambah (admin): semua data lengkap tidak di-filter
        $allRanks           = $this->masterDataRepository->getRanks();
        $allPositions       = $this->masterDataRepository->getPositions();
        $allWorkUnits       = $this->masterDataRepository->getWorkUnits();
        $allEmploymentStatuses = $this->masterDataRepository->getEmploymentStatuses();
        $allEducations      = $this->masterDataRepository->getEducations();

        return view('employees.index', compact(
            'employees', 'filters', 'perPage',
            'employmentStatuses', 'workUnits', 'genders',
            'religions',
            'ranksActive', 'ranksAll',
            'positionsActive', 'positionsAll',
            'educations',
            'allRanks', 'allPositions', 'allWorkUnits',
            'allEmploymentStatuses', 'allEducations'
        ));
    }

    public function getMajors(Request $request): JsonResponse
    {
        $level = $request->integer('level');

        $query = Education::whereHas('employeeEducations');

        if ($level > 0) {
            $query->where('level', $level);
        }

        // Regex strip prefix jenjang dari awal nama: S.1, S1, S.2, S2, S3, D.III, DIII, D3, STM, SMA, SMK, dll.
        $stripPattern = '/^(S\.?\d+|D\.?(?:IV|III|II|I|\d+)|STM|SMK?|SMP|SD)\s+/i';

        $programs = $query->orderBy('name')
            ->get(['id', 'name'])
            ->map(fn($e) => [
                'id'   => $e->id,
                'name' => trim(preg_replace($stripPattern, '', $e->name)),
            ])
            ->sortBy('name')
            ->values();

        return response()->json($programs);
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
        $educations         = $this->masterDataRepository->getEducations();

        return view('employees.create', compact(
            'genders', 'religions', 'maritalStatuses',
            'employmentStatuses', 'workUnits', 'ranks', 'positions', 'educations'
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

    public function storeSalaryHistory(Request $request, int $id): RedirectResponse
    {
        $employee = Employee::findOrFail($id);

        $validated = $request->validate([
            'new_salary'      => ['required', 'numeric', 'min:0'],
            'effective_date'  => ['required', 'date'],
            'reason'          => ['required', 'string', 'max:255'],
            'notes'           => ['nullable', 'string', 'max:1000'],
        ]);

        $oldSalary = $employee->salary ?? 0;
        $newSalary = (int) $validated['new_salary'];
        $increaseAmount = $newSalary - $oldSalary;
        $increasePercentage = $oldSalary > 0 ? round(($increaseAmount / $oldSalary) * 100, 2) : 0;

        \App\Models\SalaryHistory::create([
            'employee_id'        => $employee->id,
            'old_salary'         => $oldSalary,
            'new_salary'         => $newSalary,
            'increase_amount'    => $increaseAmount,
            'increase_percentage'=> $increasePercentage,
            'effective_date'     => $validated['effective_date'],
            'reason'             => $validated['reason'],
            'notes'              => $validated['notes'] ?? null,
            'created_by'         => auth()->user()?->name ?? 'Admin',
        ]);

        // Update employee current salary
        $employee->update([
            'salary'     => $newSalary,
            'salary_tmt' => $validated['effective_date'],
        ]);

        return back()->with('success', 'Riwayat gaji berhasil ditambahkan.');
    }

    public function destroySalaryHistory(int $employeeId, int $historyId): RedirectResponse
    {
        $history = \App\Models\SalaryHistory::where('employee_id', $employeeId)->findOrFail($historyId);
        $history->delete();

        // Update employee current salary to latest remaining salary history if available
        $latest = \App\Models\SalaryHistory::where('employee_id', $employeeId)
            ->orderBy('effective_date', 'desc')
            ->orderBy('id', 'desc')
            ->first();

        $employee = Employee::findOrFail($employeeId);
        if ($latest) {
            $employee->update([
                'salary'     => $latest->new_salary,
                'salary_tmt' => $latest->effective_date,
            ]);
        }

        return back()->with('success', 'Riwayat gaji berhasil dihapus.');
    }

    public function importSalaryPdf(Request $request, int $id): RedirectResponse
    {
        $employee = Employee::findOrFail($id);

        $request->validate([
            'pdf_file' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        $file = $request->file('pdf_file');
        $text = '';

        // 1. Try Smalot PDF Parser library
        try {
            $parser = new \Smalot\PdfParser\Parser();
            $pdf = $parser->parseFile($file->getRealPath());
            $text = $pdf->getText();
        } catch (\Throwable $e) {
            // fallback
        }

        // 2. Try pdftotext CLI (poppler-utils) if available
        if (empty(trim($text))) {
            try {
                $tmpPath = $file->getRealPath();
                $output = [];
                $ret = -1;
                exec("pdftotext " . escapeshellarg($tmpPath) . " -", $output, $ret);
                if ($ret === 0 && !empty($output)) {
                    $text = implode("\n", $output);
                }
            } catch (\Throwable $e) {
                // fallback
            }
        }

        // 3. Fallback: basic stream extraction
        if (empty(trim($text))) {
            try {
                $content = file_get_contents($file->getRealPath());
                $text = $this->extractPdfText($content);
            } catch (\Throwable $e) {
                return back()->with('error', 'Gagal membaca file PDF: ' . $e->getMessage());
            }
        }

        if (empty(trim($text))) {
            return back()->with('error', 'Tidak dapat mengekstrak teks dari PDF. Pastikan PDF berisi teks (bukan scan/gambar).');
        }

        // Parse salary data from text
        $results = $this->parseSalaryFromText($text);

        if (empty($results)) {
            return back()->with('error', 'Tidak ditemukan data gaji dalam PDF. Pastikan PDF berisi informasi gaji pokok / kenaikan gaji.');
        }

        $imported = 0;
        $currentSalary = $employee->salary ?? 0;

        foreach ($results as $row) {
            $oldSalary = (!empty($row['old_salary']) && $row['old_salary'] > 0) ? $row['old_salary'] : $currentSalary;
            $newSalary = $row['new_salary'];
            $increaseAmount = $newSalary - $oldSalary;
            $increasePercentage = $oldSalary > 0 ? round(($increaseAmount / $oldSalary) * 100, 2) : 0;

            \App\Models\SalaryHistory::create([
                'employee_id'        => $employee->id,
                'old_salary'         => $oldSalary,
                'new_salary'         => $newSalary,
                'increase_amount'    => $increaseAmount,
                'increase_percentage'=> $increasePercentage,
                'effective_date'     => $row['effective_date'],
                'reason'             => $row['reason'] ?: 'Import dari PDF',
                'notes'              => 'Data diimport dari file PDF',
                'created_by'         => auth()->user()?->name ?? 'Admin',
            ]);

            $currentSalary = $newSalary;
            $imported++;
        }

        // Update employee salary to latest
        if ($imported > 0) {
            $lastRow = end($results);
            $employee->update([
                'salary'     => $lastRow['new_salary'],
                'salary_tmt' => $lastRow['effective_date'],
            ]);
        }

        return back()->with('success', "{$imported} data riwayat gaji berhasil diimport dari PDF.");
    }

    /**
     * Basic PDF text extraction fallback.
     */
    private function extractPdfText(string $content): string
    {
        $text = '';
        if (preg_match_all('/stream[\r\n]+(.*?)endstream/s', $content, $matches)) {
            foreach ($matches[1] as $stream) {
                $cleanStream = ltrim($stream, "\r\n");
                $decoded = @gzuncompress($cleanStream);
                if ($decoded === false) {
                    $decoded = @gzinflate($cleanStream);
                }
                if ($decoded === false) {
                    $decoded = $cleanStream;
                }
                if (preg_match_all('/\((.*?)\)\s*Tj/s', $decoded, $tj)) {
                    $text .= implode(' ', $tj[1]) . ' ';
                }
                if (preg_match_all('/\[(.*?)\]\s*TJ/s', $decoded, $tjs)) {
                    foreach ($tjs[1] as $tjBlock) {
                        if (preg_match_all('/\((.*?)\)/', $tjBlock, $innerTj)) {
                            $text .= implode('', $innerTj[1]) . ' ';
                        }
                    }
                }
            }
        }
        return $text;
    }

    /**
     * Parse salary data from extracted PDF text.
     */
    private function parseSalaryFromText(string $text): array
    {
        $normalized = preg_replace('/\s+/', ' ', $text);
        $results = [];

        $cleanNumber = function(string $val): int {
            $val = trim($val);
            // Strip trailing ,00 or .00 or ,- or .-
            $val = preg_replace('/[,.]00$/', '', $val);
            $val = preg_replace('/[,-]-$/', '', $val);
            if (preg_match('/,(\d{2})$/', $val)) {
                $val = preg_replace('/,\d{2}$/', '', $val);
            }
            return (int) preg_replace('/[^\d]/', '', $val);
        };

        // Extract salaries (HANYA angka yang secara EKSPLISIT diawali dengan "Rp" atau "Rp.")
        $salaries = [];
        $pattern = '/Rp\.?\s*([1-9]\d{0,2}(?:\.\d{3})+(?:,\d{2}|,-)?|[1-9]\d{5,7}(?:,\d{2}|,-)?)/i';
        if (preg_match_all($pattern, $normalized, $m)) {
            foreach ($m[1] as $val) {
                $num = $cleanNumber($val);
                if ($num >= 500000 && $num <= 100000000 && !in_array($num, $salaries)) {
                    $salaries[] = $num;
                }
            }
        }

        // Extract dates (HANYA tanggal yang diawali kata kunci "berlakunya gaji", "TMT", "terhitung mulai tanggal", dll)
        $dates = [];
        $monthMap = [
            'januari'=>'01','februari'=>'02','maret'=>'03','april'=>'04','mei'=>'05','juni'=>'06',
            'juli'=>'07','agustus'=>'08','september'=>'09','oktober'=>'10','november'=>'11','desember'=>'12',
        ];

        $dateKeywordPattern = '/(?:terhitung\s*mulai\s*tanggal|t\.?m\.?t\.?|berlakunya\s*gaji|berlaku\s*mulai|tanggal\s*berlaku|tanggal\s*berlakunya|mulai\s*tanggal|berlaku)\s*[:=]?\s*(?:pada\s*tanggal\s*)?(\d{1,2}\s+[a-z]+\s+\d{4}|\d{1,2}[-\/\.]\d{1,2}[-\/\.]\d{4}|\d{4}[-\/\.]\d{1,2}[-\/\.]\d{1,2})/i';

        if (preg_match_all($dateKeywordPattern, $normalized, $dm)) {
            foreach ($dm[1] as $dateStr) {
                if (preg_match('/(\d{1,2})\s+(Januari|Februari|Maret|April|Mei|Juni|Juli|Agustus|September|Oktober|November|Desember)\s+(\d{4})/i', $dateStr, $m)) {
                    $d = str_pad($m[1], 2, '0', STR_PAD_LEFT);
                    $mo = $monthMap[strtolower($m[2])] ?? null;
                    $y = $m[3];
                    if ($mo && $y >= 1990 && $y <= 2030) $dates[] = "$y-$mo-$d";
                } elseif (preg_match('/(\d{1,2})[-\/\.](\d{1,2})[-\/\.](\d{4})/', $dateStr, $m)) {
                    $d = str_pad($m[1], 2, '0', STR_PAD_LEFT); $mo = str_pad($m[2], 2, '0', STR_PAD_LEFT); $y = $m[3];
                    if ($y >= 1990 && $y <= 2030 && $mo <= 12) $dates[] = "$y-$mo-$d";
                } elseif (preg_match('/(\d{4})[-\/\.](\d{1,2})[-\/\.](\d{1,2})/', $dateStr, $m)) {
                    $y = $m[1]; $mo = str_pad($m[2], 2, '0', STR_PAD_LEFT); $d = str_pad($m[3], 2, '0', STR_PAD_LEFT);
                    if ($y >= 1990 && $y <= 2030) $dates[] = "$y-$mo-$d";
                }
            }
        }

        // Fallback jika tidak ditemukan dengan kata kunci khusus TMT/Berlaku: cari tanggal format Indonesia
        if (empty($dates)) {
            if (preg_match_all('/(\d{1,2})\s+(Januari|Februari|Maret|April|Mei|Juni|Juli|Agustus|September|Oktober|November|Desember)\s+(\d{4})/i', $normalized, $dm)) {
                foreach ($dm[0] as $i => $_) {
                    $d = str_pad($dm[1][$i], 2, '0', STR_PAD_LEFT);
                    $mo = $monthMap[strtolower($dm[2][$i])] ?? null;
                    $y = $dm[3][$i];
                    if ($mo && $y >= 1990 && $y <= 2030) $dates[] = "$y-$mo-$d";
                }
            }
        }

        // Reasons
        $reasons = [];
        $reasonPats = [
            '/(?:kenaikan\s*(?:berkala|gaji|pangkat)|SK\s*(?:kenaikan|pengangkatan)|alasan)\s*[:=]?\s*([^.;\n]{5,80})/i',
            '/(?:perihal|tentang)\s*[:=]?\s*([^.;\n]{5,80})/i',
        ];
        foreach ($reasonPats as $pat) {
            if (preg_match_all($pat, $normalized, $rm)) {
                foreach ($rm[1] as $r) $reasons[] = trim($r);
            }
        }

        $uniqueSalaries = array_values(array_unique($salaries));
        $uniqueDates = array_values(array_unique($dates));
        $today = date('Y-m-d');

        if (!empty($uniqueSalaries)) {
            sort($uniqueSalaries);
            $effectiveDate = $uniqueDates[0] ?? $today;
            $reason = $reasons[0] ?? 'Kenaikan Gaji Berkala (Import PDF)';

            if (count($uniqueSalaries) > 1) {
                // Dalam 1 SK terdapat Gaji Lama (terkecil) dan Gaji Baru (terbesar)
                $oldSal = $uniqueSalaries[0];
                $newSal = end($uniqueSalaries);

                $results[] = [
                    'old_salary'     => $oldSal,
                    'new_salary'     => $newSal,
                    'effective_date' => $effectiveDate,
                    'reason'         => $reason,
                ];
            } else {
                $results[] = [
                    'old_salary'     => null,
                    'new_salary'     => $uniqueSalaries[0],
                    'effective_date' => $effectiveDate,
                    'reason'         => $reason,
                ];
            }
        }

        return $results;
    }

    /**
     * Global search: kembalikan max 8 pegawai cocok dengan query.
     * Jika hanya 1 hasil, frontend bisa langsung redirect ke show.
     */
    public function searchGlobal(Request $request): JsonResponse
    {
        $q = trim($request->get('q', ''));

        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $employees = Employee::with(['employmentStatus', 'currentPosition.position'])
            ->search($q)
            ->whereNull('deleted_at')
            ->limit(8)
            ->get(['id', 'nip', 'full_name', 'prefix_title', 'suffix_title']);

        $results = $employees->map(fn($e) => [
            'id'       => $e->id,
            'nip'      => $e->nip,
            'name'     => $e->full_name_with_title,
            'url'      => route('employees.show', $e->id),
        ]);

        return response()->json($results);
    }

    /**
     * Data pegawai dengan field penting kosong (untuk notifikasi lonceng).
     */
    public function incompleteData(Request $request): JsonResponse
    {
        $incomplete = Employee::whereNull('deleted_at')
            ->where(function ($q) {
                $q->whereNull('nip')
                  ->orWhereNull('birth_date')
                  ->orWhereNull('gender_id')
                  ->orWhereNull('employment_status_id')
                  ->orWhereNull('work_unit_id')
                  ->orWhereNull('phone')
                  ->orWhereNull('address');
            })
            ->limit(10)
            ->get(['id', 'nip', 'full_name'])
            ->map(fn($e) => [
                'id'   => $e->id,
                'name' => $e->full_name,
                'nip'  => $e->nip ?? '(NIP kosong)',
                'url'  => route('employees.show', $e->id),
            ]);

        $total = Employee::whereNull('deleted_at')
            ->where(function ($q) {
                $q->whereNull('nip')
                  ->orWhereNull('birth_date')
                  ->orWhereNull('gender_id')
                  ->orWhereNull('employment_status_id')
                  ->orWhereNull('work_unit_id')
                  ->orWhereNull('phone')
                  ->orWhereNull('address');
            })
            ->count();

        return response()->json(['total' => $total, 'items' => $incomplete]);
    }
}
