<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // Genders
        DB::table('genders')->insertOrIgnore([
            ['name' => 'Laki-laki', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Perempuan',  'created_at' => $now, 'updated_at' => $now],
        ]);

        // Religions
        DB::table('religions')->insertOrIgnore([
            ['name' => 'Islam',     'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Kristen',   'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Katolik',   'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Hindu',     'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Buddha',    'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Konghucu',  'created_at' => $now, 'updated_at' => $now],
        ]);

        // Marital Statuses
        DB::table('marital_statuses')->insertOrIgnore([
            ['name' => 'Belum Kawin', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Kawin',       'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Cerai Hidup', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Cerai Mati',  'created_at' => $now, 'updated_at' => $now],
        ]);

        // Employment Statuses
        DB::table('employment_statuses')->insertOrIgnore([
            ['code' => 'PNS',   'name' => 'Pegawai Negeri Sipil',           'created_at' => $now, 'updated_at' => $now],
            ['code' => 'CPNS',  'name' => 'Calon Pegawai Negeri Sipil',     'created_at' => $now, 'updated_at' => $now],
            ['code' => 'PPPK',  'name' => 'Pegawai Pemerintah dengan Perjanjian Kerja', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'HONORER','name' => 'Pegawai Honorer',               'created_at' => $now, 'updated_at' => $now],
            ['code' => 'PENSIUN','name' => 'Pensiunan',                     'created_at' => $now, 'updated_at' => $now],
            ['code' => 'NONAKTIF','name' => 'Nonaktif / Lainnya',           'created_at' => $now, 'updated_at' => $now],
        ]);

        // Educations
        DB::table('educations')->insertOrIgnore([
            ['code' => 'SD',    'name' => 'Sekolah Dasar',                    'level' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'SMP',   'name' => 'Sekolah Menengah Pertama',         'level' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'SMA',   'name' => 'Sekolah Menengah Atas',            'level' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'SMK',   'name' => 'Sekolah Menengah Kejuruan',        'level' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'D1',    'name' => 'Diploma Satu',                     'level' => 4, 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'D2',    'name' => 'Diploma Dua',                      'level' => 5, 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'D3',    'name' => 'Diploma Tiga',                     'level' => 6, 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'D4',    'name' => 'Diploma Empat',                    'level' => 7, 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'S1',    'name' => 'Sarjana (S1)',                     'level' => 8, 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'S2',    'name' => 'Magister (S2)',                    'level' => 9, 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'S3',    'name' => 'Doktor (S3)',                      'level' => 10, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // Golongan/Ranks (standard PNS)
        $ranks = [
            ['code' => 'I/a',  'name' => 'Juru Muda',              'group' => 'I'],
            ['code' => 'I/b',  'name' => 'Juru Muda Tingkat I',    'group' => 'I'],
            ['code' => 'I/c',  'name' => 'Juru',                   'group' => 'I'],
            ['code' => 'I/d',  'name' => 'Juru Tingkat I',         'group' => 'I'],
            ['code' => 'II/a', 'name' => 'Pengatur Muda',          'group' => 'II'],
            ['code' => 'II/b', 'name' => 'Pengatur Muda Tingkat I','group' => 'II'],
            ['code' => 'II/c', 'name' => 'Pengatur',               'group' => 'II'],
            ['code' => 'II/d', 'name' => 'Pengatur Tingkat I',     'group' => 'II'],
            ['code' => 'III/a','name' => 'Penata Muda',            'group' => 'III'],
            ['code' => 'III/b','name' => 'Penata Muda Tingkat I',  'group' => 'III'],
            ['code' => 'III/c','name' => 'Penata',                 'group' => 'III'],
            ['code' => 'III/d','name' => 'Penata Tingkat I',       'group' => 'III'],
            ['code' => 'IV/a', 'name' => 'Pembina',                'group' => 'IV'],
            ['code' => 'IV/b', 'name' => 'Pembina Tingkat I',      'group' => 'IV'],
            ['code' => 'IV/c', 'name' => 'Pembina Utama Muda',     'group' => 'IV'],
            ['code' => 'IV/d', 'name' => 'Pembina Utama Madya',    'group' => 'IV'],
            ['code' => 'IV/e', 'name' => 'Pembina Utama',          'group' => 'IV'],
        ];

        foreach ($ranks as &$rank) {
            $rank['created_at'] = $now;
            $rank['updated_at'] = $now;
        }
        DB::table('ranks')->insertOrIgnore($ranks);

        // Work Units
        DB::table('work_units')->insertOrIgnore([
            ['code' => 'BMKG-01', 'name' => 'Stasiun Meteorologi Klas I Bandung',      'description' => 'Unit Utama', 'parent_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'BMKG-02', 'name' => 'Seksi Observasi',                          'description' => null, 'parent_id' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'BMKG-03', 'name' => 'Seksi Data dan Informasi',                 'description' => null, 'parent_id' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'BMKG-04', 'name' => 'Sub Bagian Tata Usaha',                    'description' => null, 'parent_id' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'BMKG-05', 'name' => 'Seksi Instrumentasi, Kalibrasi, dan Rekayasa Peralatan', 'description' => null, 'parent_id' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // Positions
        DB::table('positions')->insertOrIgnore([
            ['code' => 'JFT-001', 'name' => 'Pengamat Meteorologi dan Geofisika',          'level' => 'Pelaksana',      'created_at' => $now, 'updated_at' => $now],
            ['code' => 'JFT-002', 'name' => 'Teknisi Instrumentasi Meteorologi & Geofisika','level' => 'Pelaksana',     'created_at' => $now, 'updated_at' => $now],
            ['code' => 'JFT-003', 'name' => 'Analis Meteorologi',                          'level' => 'Ahli Pertama',   'created_at' => $now, 'updated_at' => $now],
            ['code' => 'JFT-004', 'name' => 'Analis Klimatologi',                          'level' => 'Ahli Pertama',   'created_at' => $now, 'updated_at' => $now],
            ['code' => 'JFT-005', 'name' => 'Analis Geofisika',                            'level' => 'Ahli Pertama',   'created_at' => $now, 'updated_at' => $now],
            ['code' => 'JFT-006', 'name' => 'Analis Meteorologi',                          'level' => 'Ahli Muda',      'created_at' => $now, 'updated_at' => $now],
            ['code' => 'JFT-007', 'name' => 'Analis Meteorologi',                          'level' => 'Ahli Madya',     'created_at' => $now, 'updated_at' => $now],
            ['code' => 'JFU-001', 'name' => 'Bendahara',                                   'level' => 'Pelaksana',      'created_at' => $now, 'updated_at' => $now],
            ['code' => 'JFU-002', 'name' => 'Pengelola Data Kepegawaian',                  'level' => 'Pelaksana',      'created_at' => $now, 'updated_at' => $now],
            ['code' => 'JFU-003', 'name' => 'Pengadministrasi Umum',                       'level' => 'Pelaksana',      'created_at' => $now, 'updated_at' => $now],
            ['code' => 'JFU-004', 'name' => 'Pengemudi',                                   'level' => 'Pelaksana',      'created_at' => $now, 'updated_at' => $now],
            ['code' => 'STR-001', 'name' => 'Kepala Stasiun',                              'level' => 'Struktural',     'created_at' => $now, 'updated_at' => $now],
            ['code' => 'STR-002', 'name' => 'Kepala Seksi Observasi',                      'level' => 'Struktural',     'created_at' => $now, 'updated_at' => $now],
            ['code' => 'STR-003', 'name' => 'Kepala Seksi Data dan Informasi',             'level' => 'Struktural',     'created_at' => $now, 'updated_at' => $now],
            ['code' => 'STR-004', 'name' => 'Kepala Sub Bagian Tata Usaha',                'level' => 'Struktural',     'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
