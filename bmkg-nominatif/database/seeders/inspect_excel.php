<?php

require __DIR__ . '/../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$filePath = __DIR__ . '/../../../NOMINATIF OKTOBER 2025_022438.xlsx';

if (!file_exists($filePath)) {
    echo "File not found: $filePath\n";
    exit(1);
}

$spreadsheet = IOFactory::load($filePath);

foreach ($spreadsheet->getSheetNames() as $sheetIndex => $sheetName) {
    echo "==========================================\n";
    echo "SHEET [$sheetIndex]: $sheetName\n";
    echo "==========================================\n";

    $sheet = $spreadsheet->getSheet($sheetIndex);
    $rows = $sheet->toArray();

    echo "Total Rows: " . count($rows) . "\n";

    for ($i = 0; $i < min(15, count($rows)); $i++) {
        $filtered = array_filter($rows[$i], fn($val) => !is_null($val) && trim((string)$val) !== '');
        if (!empty($filtered)) {
            echo "Row $i: " . json_encode($filtered, JSON_UNESCAPED_UNICODE) . "\n";
        }
    }
    echo "\n";
}
