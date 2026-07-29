<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Employee Educations (history)
        Schema::create('employee_educations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('education_id')->constrained('educations')->restrictOnDelete();
            $table->string('institution_name', 200)->comment('Nama sekolah/kampus');
            $table->string('major', 150)->nullable()->comment('Jurusan/Program Studi');
            $table->year('year_graduated')->nullable();
            $table->string('certificate_number', 100)->nullable()->comment('Nomor ijazah');
            $table->timestamps();

            $table->index('employee_id');
            $table->index('education_id');
        });

        // Employee Ranks/Golongan (history)
        Schema::create('employee_ranks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('rank_id')->constrained('ranks')->restrictOnDelete();
            $table->string('decree_number', 100)->nullable()->comment('Nomor SK');
            $table->date('decree_date')->nullable()->comment('Tanggal SK');
            $table->date('effective_date')->comment('TMT Golongan');
            $table->boolean('is_current')->default(false)->comment('Golongan aktif saat ini');
            $table->timestamps();

            $table->index('employee_id');
            $table->index('rank_id');
            $table->index('is_current');
        });

        // Employee Positions/Jabatan (history)
        Schema::create('employee_positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('position_id')->constrained('positions')->restrictOnDelete();
            $table->foreignId('work_unit_id')->constrained('work_units')->restrictOnDelete();
            $table->string('decree_number', 100)->nullable()->comment('Nomor SK');
            $table->date('decree_date')->nullable()->comment('Tanggal SK');
            $table->date('effective_date')->comment('TMT Jabatan');
            $table->boolean('is_current')->default(false)->comment('Jabatan aktif saat ini');
            $table->timestamps();

            $table->index('employee_id');
            $table->index('position_id');
            $table->index('is_current');
        });

        // Employee Families
        Schema::create('employee_families', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->string('name', 150);
            $table->string('relationship', 50)->comment('Suami/Istri/Anak');
            $table->foreignId('gender_id')->constrained('genders')->restrictOnDelete();
            $table->date('birth_date')->nullable();
            $table->string('occupation', 100)->nullable();
            $table->timestamps();

            $table->index('employee_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_families');
        Schema::dropIfExists('employee_positions');
        Schema::dropIfExists('employee_ranks');
        Schema::dropIfExists('employee_educations');
    }
};
