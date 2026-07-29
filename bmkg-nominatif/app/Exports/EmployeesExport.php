<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EmployeesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    public function __construct(
        protected Collection $employees
    ) {}

    public function collection(): Collection
    {
        return $this->employees;
    }

    public function headings(): array
    {
        return [
            'NO',
            'NAMA LENGKAP',
            'NIP',
            'NO. KARPEG',
            'TEMPAT LAHIR',
            'TANGGAL LAHIR',
            'JABATAN SAAT INI',
            'TMT JABATAN',
            'JENIS KELAMIN',
            'GOLONGAN CPNS',
            'TMT CPNS',
            'GOLONGAN PNS',
            'TMT PNS',
            'GOLONGAN TERAKHIR',
            'TMT GOLONGAN TERAKHIR',
            'GAJI POKOK',
            'TMT GAJI POKOK',
            'PENDIDIKAN UMUM',
            'PENDIDIKAN DINAS',
            'KURSUS',
            'LUAR NEGERI',
            'PENJENJANGAN',
            'AGAMA',
            'STATUS PERKAWINAN',
            'JUMLAH ANAK',
            'JUMLAH KELUARGA',
            'KETERANGAN KELUARGA',
            'ALAMAT',
            'TELEPON',
            'EMAIL',
            'STATUS KEPEGAWAIAN',
            'UNIT KERJA',
        ];
    }

    public function map($employee): array
    {
        static $no = 0;
        $no++;

        // Get latest education details
        $latestEdu = $employee->educations->first();
        $eduUmum = $latestEdu 
            ? trim($latestEdu->education?->name . ' ' . $latestEdu->institution_name . ' (' . $latestEdu->year_graduated . ')')
            : '-';

        return [
            $no,
            $employee->full_name_with_title,
            "'" . $employee->nip, // Force string in excel
            $employee->karpeg ?? '-',
            $employee->birth_place,
            $employee->birth_date ? $employee->birth_date->format('d/m/Y') : '-',
            $employee->currentPosition?->position?->name ?? '-',
            $employee->currentPosition?->effective_date ? $employee->currentPosition->effective_date->format('d/m/Y') : '-',
            $employee->gender?->name ?? '-',
            $employee->cpns_rank ?? '-',
            $employee->cpns_tmt ? $employee->cpns_tmt->format('d/m/Y') : '-',
            $employee->pns_rank ?? '-',
            $employee->pns_tmt ? $employee->pns_tmt->format('d/m/Y') : '-',
            $employee->currentRank?->rank?->code ? ($employee->currentRank->rank->code . ' - ' . $employee->currentRank->rank->name) : '-',
            $employee->currentRank?->effective_date ? $employee->currentRank->effective_date->format('d/m/Y') : '-',
            $employee->salary ? (int)$employee->salary : '-',
            $employee->salary_tmt ? $employee->salary_tmt->format('d/m/Y') : '-',
            $eduUmum,
            $employee->edu_dinas ? ($employee->edu_dinas . ($employee->edu_dinas_year ? ' (' . $employee->edu_dinas_year . ')' : '')) : '-',
            $employee->edu_kursus ? ($employee->edu_kursus . ($employee->edu_kursus_year ? ' (' . $employee->edu_kursus_year . ')' : '')) : '-',
            $employee->edu_ln ? ($employee->edu_ln . ($employee->edu_ln_year ? ' (' . $employee->edu_ln_year . ')' : '')) : '-',
            $employee->edu_penjenjangan ? ($employee->edu_penjenjangan . ($employee->edu_penjenjangan_year ? ' (' . $employee->edu_penjenjangan_year . ')' : '')) : '-',
            $employee->religion?->name ?? '-',
            $employee->maritalStatus?->name ?? '-',
            $employee->children_count,
            $employee->family_count,
            $employee->family_note ?? '-',
            $employee->address ?? '-',
            $employee->phone ?? '-',
            $employee->email ?? '-',
            $employee->employmentStatus?->name ?? '-',
            $employee->workUnit?->name ?? '-',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '0066CC'], // Action Blue
                ],
            ],
        ];
    }
}
