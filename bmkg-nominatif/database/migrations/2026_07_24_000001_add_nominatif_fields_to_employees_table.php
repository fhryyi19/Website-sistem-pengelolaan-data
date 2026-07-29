<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('karpeg', 50)->nullable()->after('nip')->comment('Nomor Kartu Pegawai');
            $table->string('cpns_rank', 10)->nullable()->after('karpeg')->comment('Pangkat/Golongan CPNS');
            $table->date('cpns_tmt')->nullable()->after('cpns_rank')->comment('TMT CPNS');
            $table->string('pns_rank', 10)->nullable()->after('cpns_tmt')->comment('Pangkat/Golongan PNS');
            $table->date('pns_tmt')->nullable()->after('pns_rank')->comment('TMT PNS');
            $table->unsignedBigInteger('salary')->nullable()->after('marital_status_id')->comment('Gaji Pokok');
            $table->date('salary_tmt')->nullable()->after('salary')->comment('TMT Gaji Pokok');
            
            // Pendidikan
            $table->string('edu_dinas', 150)->nullable()->after('salary_tmt');
            $table->year('edu_dinas_year')->nullable()->after('edu_dinas');
            
            $table->string('edu_kursus', 255)->nullable()->after('edu_dinas_year');
            $table->year('edu_kursus_year')->nullable()->after('edu_kursus');
            
            $table->string('edu_ln', 255)->nullable()->after('edu_kursus_year');
            $table->year('edu_ln_year')->nullable()->after('edu_ln');
            
            $table->string('edu_penjenjangan', 150)->nullable()->after('edu_ln_year');
            $table->year('edu_penjenjangan_year')->nullable()->after('edu_penjenjangan');

            // Keluarga
            $table->integer('children_count')->default(0)->after('edu_penjenjangan_year');
            $table->integer('family_count')->default(0)->after('children_count');
            $table->string('family_note', 255)->nullable()->after('family_count');
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'karpeg',
                'cpns_rank',
                'cpns_tmt',
                'pns_rank',
                'pns_tmt',
                'salary',
                'salary_tmt',
                'edu_dinas',
                'edu_dinas_year',
                'edu_kursus',
                'edu_kursus_year',
                'edu_ln',
                'edu_ln_year',
                'edu_penjenjangan',
                'edu_penjenjangan_year',
                'children_count',
                'family_count',
                'family_note',
            ]);
        });
    }
};
