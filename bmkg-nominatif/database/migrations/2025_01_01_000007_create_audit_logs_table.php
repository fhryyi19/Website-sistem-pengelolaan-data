<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_name', 100)->nullable()->comment('Snapshot of user name at time of action');
            $table->string('action', 50)->comment('LOGIN, LOGOUT, CREATE, UPDATE, DELETE, RESET_PASSWORD');
            $table->string('model_type', 100)->nullable()->comment('Eloquent model class name');
            $table->unsignedBigInteger('model_id')->nullable()->comment('ID of affected record');
            $table->json('old_values')->nullable()->comment('Before state');
            $table->json('new_values')->nullable()->comment('After state');
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->string('description', 500)->nullable();
            $table->timestamp('created_at')->nullable();

            // No updated_at — audit logs are immutable
            // No soft deletes — audit logs must not be deleted

            $table->index('user_id');
            $table->index('action');
            $table->index('model_type');
            $table->index('created_at');

            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
