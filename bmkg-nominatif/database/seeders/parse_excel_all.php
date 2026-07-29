<?php

require __DIR__ . '/../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$filePath = __DIR__ . '/../../NOMINATIF OKTOBER 2025_022438.xlsx';
$spreadsheet = IOFactory::load($filePath);
$sheet = $spreadsheet->getActiveSheet();
$rows = $sheet->toArray();

$employees = [];
$currentRow = 6; // Data starts at index 6

while ($currentRow < count($rows)) {
    $row = $rows[$currentRow];
    
    // Check if col 1 has a numeric NO or col 2 has a name
    $no = trim((string)($row[1] ?? ''));
    $name = trim((string)($row[2] ?? ''));

    if (is_numeric($no) && !empty($name)) {
        $nextRow1 = $rows[$currentRow + 1] ?? [];
        $nextRow2 = $rows[$currentRow + 2] ?? [];

        $nipCandidate = trim((string)($nextRow1[2] ?? ''));
        // Sanitize NIP (remove spaces or hyphens)
        $nipClean = preg_replace('/[^0-9]/', '', $nipCandidate);

        // Check if valid 18 digit NIP
        if (strlen($nipClean) === 18) {
            $employee = [
                'no'               => $no,
                'raw_name'         => $name,
                'nip'              => $nipClean,
                'karpeg'           => trim((string)($nextRow2[2] ?? '')),
                'birth_place'      => trim((string)($row[3] ?? '')),
                'birth_date_raw'   => trim((string)($nextRow1[3] ?? '')),
                'position_raw'     => trim((string)($row[4] ?? '')),
                'position_tmt_raw' => trim((string)($nextRow1[4] ?? '')),
                'gender_code'      => strtoupper(trim((string)($row[5] ?? ''))),
                'rank_code'        => trim((string)($row[8] ?? '')),
                'rank_tmt_raw'     => trim((string)($nextRow1[8] ?? '')),
                'salary_raw'       => trim((string)($row[9] ?? '')),
                'education_raw'    => trim((string)($row[10] ?? '')),
                'institution_raw'  => trim((string)($nextRow1[10] ?? '')),
                'year_grad_raw'    => trim((string)($nextRow2[10] ?? '')),
                'religion_raw'     => trim((string)($row[15] ?? '')),
                'marital_raw'      => trim((string)($row[16] ?? '')),
                'children_count'   => trim((string)($row[17] ?? '')),
            ];

            $employees[] = $employee;
        }
    }

    $currentRow++;
}

echo "Total Valid Employees Parsed: " . count($employees) . "\n";
echo "First Employee Sample:\n";
print_r($employees[0] ?? []);
echo "\nLast Employee Sample:\n";
print_r(end($employees));
