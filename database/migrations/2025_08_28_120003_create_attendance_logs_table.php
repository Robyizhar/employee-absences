<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('attendance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('machine_id')->constrained('machines')->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->timestamp('scan_time');
            $table->enum('status', ['IN','OUT','BREAK_IN','BREAK_IN_OUT']);
            $table->json('raw_payload'); // Postgres: jsonb, MySQL: json
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();

            $table->index(['employee_id', 'scan_time']);
            $table->index(['company_id', 'scan_time']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('attendance_logs');
    }
};
