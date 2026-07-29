<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('nip', 20)->unique()->comment('Nomor Induk Pegawai');
            $table->string('full_name', 150);
            $table->string('prefix_title', 50)->nullable()->comment('Gelar depan e.g. Dr., Ir.');
            $table->string('suffix_title', 50)->nullable()->comment('Gelar belakang e.g. S.T., M.T., Ph.D.');
            $table->string('birth_place', 100);
            $table->date('birth_date');
            $table->foreignId('gender_id')->constrained('genders')->restrictOnDelete();
            $table->foreignId('religion_id')->constrained('religions')->restrictOnDelete();
            $table->foreignId('marital_status_id')->constrained('marital_statuses')->restrictOnDelete();
            $table->text('address')->nullable();
            $table->string('phone', 25)->nullable();
            $table->string('email', 150)->unique()->nullable();
            $table->foreignId('employment_status_id')->constrained('employment_statuses')->restrictOnDelete();
            $table->foreignId('work_unit_id')->constrained('work_units')->restrictOnDelete();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes for frequent search/filter/sort operations
            $table->index('nip');
            $table->index('full_name');
            $table->index('email');
            $table->index('employment_status_id');
            $table->index('work_unit_id');
            $table->index('gender_id');
            $table->index('religion_id');

            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
