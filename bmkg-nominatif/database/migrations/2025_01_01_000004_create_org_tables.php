<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Work Units (with self-referencing parent for hierarchy)
        Schema::create('work_units', function (Blueprint $table) {
            $table->id();
            $table->string('code', 30)->unique();
            $table->string('name', 200);
            $table->string('description', 500)->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('name');
            $table->index('parent_id');

            $table->foreign('parent_id')
                  ->references('id')
                  ->on('work_units')
                  ->nullOnDelete();
        });

        // Ranks/Golongan
        Schema::create('ranks', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique()->comment('e.g. II/a, III/b');
            $table->string('name', 100)->comment('Pangkat name e.g. Pengatur Muda');
            $table->string('group', 5)->comment('Golongan group e.g. II, III, IV');
            $table->timestamps();
            $table->softDeletes();

            $table->index('group');
        });

        // Positions/Jabatan
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->string('code', 30)->unique();
            $table->string('name', 200);
            $table->string('level', 50)->nullable()->comment('e.g. Pelaksana, Ahli Pertama, Ahli Muda, Ahli Madya');
            $table->timestamps();
            $table->softDeletes();

            $table->index('name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('positions');
        Schema::dropIfExists('ranks');
        Schema::dropIfExists('work_units');
    }
};
