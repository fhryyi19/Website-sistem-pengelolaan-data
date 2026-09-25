<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salary_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->unsignedBigInteger('old_salary')->default(0)->comment('Gaji sebelumnya');
            $table->unsignedBigInteger('new_salary')->notNull()->comment('Gaji baru');
            $table->bigInteger('increase_amount')->notNull()->comment('Selisih kenaikan');
            $table->float('increase_percentage')->notNull()->comment('Persentase kenaikan');
            $table->date('effective_date')->comment('TMT kenaikan gaji');
            $table->string('reason', 255)->comment('Alasan kenaikan');
            $table->text('notes')->nullable();
            $table->string('created_by', 100)->nullable()->comment('Username/ID yang mencatat');
            $table->timestamps();

            $table->index('employee_id');
            $table->index('effective_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salary_histories');
    }
};
