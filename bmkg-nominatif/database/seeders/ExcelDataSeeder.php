<?php

namespace Database\Seeders;

use App\Models\Education;
use App\Models\Employee;
use App\Models\EmployeeEducation;
use App\Models\EmployeePosition;
use App\Models\EmployeeRank;
use App\Models\EmploymentStatus;
use App\Models\Gender;
use App\Models\MaritalStatus;
use App\Models\Position;
use App\Models\Rank;
use App\Models\Religion;
use App\Models\WorkUnit;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ExcelDataSeeder extends Seeder
{
    public function run(): void
    {
        $filePath = base_path('../NOMINATIF OKTOBER 2025_022438.xlsx');

        if (!file_exists($filePath)) {
            $this->command->error("File Excel tidak ditemukan di: $filePath");
            return;
        }

        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        // Default Lookups
        $workUnit = WorkUnit::firstOrCreate(
            ['code' => 'BMKG-BDG-01'],
            ['name' => 'Stasiun Geofisika Kelas I Bandung', 'description' => 'Stasiun Utama Geofisika Bandung']
        );

        $pnsStatus = EmploymentStatus::firstOrCreate(
            ['code' => 'PNS'],
            ['name' => 'Pegawai Negeri Sipil']
        );

        $genderL = Gender::firstOrCreate(['name' => 'Laki-laki']);
        $genderP = Gender::firstOrCreate(['name' => 'Perempuan']);

        $religionIslam   = Religion::firstOrCreate(['name' => 'Islam']);
        $religionKristen = Religion::firstOrCreate(['name' => 'Kristen']);
        $religionKatolik = Religion::firstOrCreate(['name' => 'Katolik']);
        $religionHindu   = Religion::firstOrCreate(['name' => 'Hindu']);
        $religionBuddha  = Religion::firstOrCreate(['name' => 'Buddha']);

        $maritalKawin      = MaritalStatus::firstOrCreate(['name' => 'Kawin']);
        $maritalBelumKawin = MaritalStatus::firstOrCreate(['name' => 'Belum Kawin']);
        $maritalCerai      = MaritalStatus::firstOrCreate(['name' => 'Cerai']);

        $countInserted = 0;
        $currentRow = 6;

        while ($currentRow < count($rows)) {
            $row = $rows[$currentRow];
            
            $no   = trim((string)($row[1] ?? ''));
            $name = trim((string)($row[2] ?? ''));

            if (is_numeric($no) && (int)$no >= 1 && (int)$no <= 50 && !empty($name) && !str_starts_with($name, '=')) {
                $nextRow1 = $rows[$currentRow + 1] ?? [];
                $nextRow2 = $rows[$currentRow + 2] ?? [];

                $nipCandidate = trim((string)($nextRow1[2] ?? ''));
                $nipClean = preg_replace('/[^0-9]/', '', $nipCandidate);

                if (strlen($nipClean) === 18 || (empty($nipClean) && (int)$no === 44)) {
                    if (empty($nipClean)) {
                        $nipClean = 'BMKG-' . str_pad($no, 4, '0', STR_PAD_LEFT);
                    }

                    // 1. Parse Name Titles (Prefix, Main Name, Suffix)
                    $titleInfo = $this->parseNameTitles($name);

                    // 2. Parse Gender
                    $genderRaw = strtoupper(trim((string)($row[5] ?? '')));
                    $genderId  = ($genderRaw === 'P') ? $genderP->id : $genderL->id;

                    // 3. Parse Religion
                    $relRaw = strtolower(trim((string)($row[15] ?? '')));
                    $religionId = match (true) {
                        str_contains($relRaw, 'kristen') => $religionKristen->id,
                        str_contains($relRaw, 'katolik') => $religionKatolik->id,
                        str_contains($relRaw, 'hindu')   => $religionHindu->id,
                        str_contains($relRaw, 'buddha')  => $religionBuddha->id,
                        default                          => $religionIslam->id,
                    };

                    // 4. Parse Marital Status
                    $marRaw = strtoupper(trim((string)($row[16] ?? '')));
                    $maritalStatusId = match (true) {
                        str_contains($marRaw, 'TK') || str_contains($marRaw, 'BELUM') => $maritalBelumKawin->id,
                        str_contains($marRaw, 'CERAI') => $maritalCerai->id,
                        default => $maritalKawin->id,
                    };

                    // 5. Parse Birth Date
                    $birthPlace   = trim((string)($row[3] ?? 'Bandung'));
                    $birthDateRaw = trim((string)($nextRow1[3] ?? ''));
                    $birthDate    = $this->parseDate($birthDateRaw) ?? Carbon::create(1985, 1, 1);

                    // 5a. Parse Karpeg
                    $karpeg = trim((string)($nextRow2[2] ?? ''));

                    // 5aa. Parse CPNS & PNS ranks & TMT
                    $cpnsRank = trim((string)($row[6] ?? ''));
                    $cpnsTmt = $this->parseDate(trim((string)($nextRow1[6] ?? '')));
                    $pnsRank = trim((string)($row[7] ?? ''));
                    $pnsTmt = $this->parseDate(trim((string)($nextRow1[7] ?? '')));

                    // 5b. Parse Salary & TMT
                    $salaryRaw = trim((string)($row[9] ?? ''));
                    $salary = !empty($salaryRaw) ? (int) preg_replace('/[^0-9]/', '', $salaryRaw) : null;
                    $salaryTmtRaw = trim((string)($nextRow1[9] ?? ''));
                    $salaryTmt = $this->parseDate($salaryTmtRaw);

                    // Helper to get text and year from multi-row column
                    $parseMultiRowCol = function($colIndex) use ($row, $nextRow1, $nextRow2) {
                        $parts = [];
                        if (!empty(trim((string)($row[$colIndex] ?? '')))) $parts[] = trim((string)$row[$colIndex]);
                        if (!empty(trim((string)($nextRow1[$colIndex] ?? '')))) $parts[] = trim((string)$nextRow1[$colIndex]);
                        if (!empty(trim((string)($nextRow2[$colIndex] ?? '')))) $parts[] = trim((string)$nextRow2[$colIndex]);
                        $text = implode(' ', $parts);
                        $year = null;
                        if (preg_match('/\b(19\d{2}|20\d{2})\b/', $text, $m)) {
                            $year = (int)$m[1];
                        }
                        return ['text' => $text ?: null, 'year' => $year];
                    };

                    // 5c. Parse Education Dinas, Kursus, LN, Penjenjangan
                    $dinasData = $parseMultiRowCol(11);
                    $kursusData = $parseMultiRowCol(12);
                    $lnData = $parseMultiRowCol(13);
                    $penjenjanganData = $parseMultiRowCol(14);

                    // 5d. Parse Family Details
                    $childrenCount = !empty(trim((string)($row[17] ?? ''))) ? (int)trim((string)$row[17]) : 0;
                    $familyCount = !empty(trim((string)($row[18] ?? ''))) ? (int)trim((string)$row[18]) : 0;
                    $familyNoteParts = [];
                    if (!empty(trim((string)($row[19] ?? '')))) $familyNoteParts[] = trim((string)$row[19]);
                    if (!empty(trim((string)($nextRow1[19] ?? '')))) $familyNoteParts[] = trim((string)$nextRow1[19]);
                    if (!empty(trim((string)($nextRow2[19] ?? '')))) $familyNoteParts[] = trim((string)$nextRow2[19]);
                    $familyNote = implode(' ', $familyNoteParts) ?: null;

                    // 6. Create or Update Employee
                    $employee = Employee::updateOrCreate(
                        ['nip' => $nipClean],
                        [
                            'full_name'            => $titleInfo['main_name'],
                            'prefix_title'         => $titleInfo['prefix'],
                            'suffix_title'         => $titleInfo['suffix'],
                            'karpeg'               => $karpeg ?: null,
                            'cpns_rank'            => $cpnsRank ?: null,
                            'cpns_tmt'             => $cpnsTmt,
                            'pns_rank'             => $pnsRank ?: null,
                            'pns_tmt'              => $pnsTmt,
                            'birth_place'          => $birthPlace,
                            'birth_date'           => $birthDate,
                            'gender_id'            => $genderId,
                            'religion_id'          => $religionId,
                            'marital_status_id'    => $maritalStatusId,
                            'salary'               => $salary,
                            'salary_tmt'           => $salaryTmt,
                            'edu_dinas'            => $dinasData['text'],
                            'edu_dinas_year'       => $dinasData['year'],
                            'edu_kursus'           => $kursusData['text'],
                            'edu_kursus_year'      => $kursusData['year'],
                            'edu_ln'               => $lnData['text'],
                            'edu_ln_year'          => $lnData['year'],
                            'edu_penjenjangan'     => $penjenjanganData['text'],
                            'edu_penjenjangan_year'=> $penjenjanganData['year'],
                            'children_count'       => $childrenCount,
                            'family_count'         => $familyCount,
                            'family_note'          => $familyNote,
                            'employment_status_id' => $pnsStatus->id,
                            'work_unit_id'         => $workUnit->id,
                        ]
                    );

                    // 7. Parse Rank/Golongan
                    $rankCode = trim((string)($row[8] ?? ''));
                    if (!empty($rankCode)) {
                        $rank = Rank::firstOrCreate(
                            ['code' => $rankCode],
                            ['name' => 'Golongan ' . $rankCode, 'group' => explode('/', $rankCode)[0] ?? 'III']
                        );

                        $rankTmtRaw = trim((string)($nextRow1[8] ?? ''));
                        $effectiveDate = $this->parseDate($rankTmtRaw) ?? now();

                        EmployeeRank::updateOrCreate(
                            ['employee_id' => $employee->id, 'rank_id' => $rank->id],
                            [
                                'effective_date' => $effectiveDate,
                                'is_current'     => true,
                            ]
                        );
                    }

                    // 8. Parse Position/Jabatan
                    $posName = trim((string)($row[4] ?? ''));
                    if (!empty($posName)) {
                        $posCode = 'POS-' . strtoupper(substr(md5($posName), 0, 5));
                        $position = Position::firstOrCreate(
                            ['name' => $posName],
                            ['code' => $posCode, 'level' => 'Pelaksana/Fungsional']
                        );

                        $posTmtRaw = trim((string)($nextRow1[4] ?? ''));
                        $posEffectiveDate = $this->parseDate($posTmtRaw) ?? now();

                        EmployeePosition::updateOrCreate(
                            ['employee_id' => $employee->id, 'position_id' => $position->id],
                            [
                                'work_unit_id'   => $workUnit->id,
                                'effective_date' => $posEffectiveDate,
                                'is_current'     => true,
                            ]
                        );
                    }

                    // 9. Parse Education
                    $eduRaw  = trim((string)($row[10] ?? ''));
                    $instRaw = trim((string)($nextRow1[10] ?? ''));
                    $yearRaw = trim((string)($nextRow2[10] ?? ''));

                    if (!empty($eduRaw)) {
                        $eduModel = Education::firstOrCreate(
                            ['name' => $eduRaw],
                            ['code' => 'EDU-' . strtoupper(substr(md5($eduRaw), 0, 4)), 'level' => 8]
                        );

                        EmployeeEducation::updateOrCreate(
                            ['employee_id' => $employee->id, 'education_id' => $eduModel->id],
                            [
                                'institution_name' => !empty($instRaw) ? $instRaw : 'Universitas/Institusi',
                                'year_graduated'   => is_numeric($yearRaw) ? (int)$yearRaw : null,
                            ]
                        );
                    }

                    $countInserted++;
                }
            }

            $currentRow++;
        }

        $this->command->info("Berhasil mengimpor $countInserted data pegawai dari NOMINATIF OKTOBER 2025_022438.xlsx!");
    }

    private function parseNameTitles(string $rawName): array
    {
        $prefix = null;
        $suffix = null;

        // Check prefixes (Dr., Drs., Ir., Hj., H.)
        if (preg_match('/^(Dr\.|Drs\.|Ir\.|Hj\.|H\.|Prof\.)\s+/i', $rawName, $matches)) {
            $prefix  = trim($matches[1]);
            $rawName = preg_replace('/^(Dr\.|Drs\.|Ir\.|Hj\.|H\.|Prof\.)\s+/i', '', $rawName);
        }

        // Check suffixes (comma separated degree titles)
        if (str_contains($rawName, ',')) {
            $parts   = explode(',', $rawName, 2);
            $mainName= trim($parts[0]);
            $suffix  = trim($parts[1]);
        } else {
            $mainName = trim($rawName);
        }

        return [
            'prefix'    => $prefix,
            'main_name' => $mainName,
            'suffix'    => $suffix,
        ];
    }

    private function parseDate(?string $rawDate): ?Carbon
    {
        if (empty($rawDate)) {
            return null;
        }

        $rawDate = str_replace('/', '-', trim($rawDate));

        try {
            if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $rawDate)) {
                return Carbon::createFromFormat('d-m-Y', $rawDate);
            }
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $rawDate)) {
                return Carbon::createFromFormat('Y-m-d', $rawDate);
            }
            return Carbon::parse($rawDate);
        } catch (\Exception $e) {
            return null;
        }
    }
}
