<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Genders
        Schema::create('genders', function (Blueprint $table) {
            $table->id();
            $table->string('name', 30)->unique();
            $table->timestamps();
        });

        // Religions
        Schema::create('religions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->timestamps();
        });

        // Marital Statuses
        Schema::create('marital_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->timestamps();
        });

        // Employment Statuses
        Schema::create('employment_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name', 100)->unique();
            $table->timestamps();
        });

        // Educations
        Schema::create('educations', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name', 100)->unique();
            $table->tinyInteger('level')->comment('Numeric level: 1=SD, 2=SMP, 3=SMA/SMK, 4=D1, 5=D2, 6=D3, 7=D4, 8=S1, 9=S2, 10=S3');
            $table->timestamps();

            $table->index('level');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('educations');
        Schema::dropIfExists('employment_statuses');
        Schema::dropIfExists('marital_statuses');
        Schema::dropIfExists('religions');
        Schema::dropIfExists('genders');
    }
};
