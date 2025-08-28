<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('attendance_summary', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->date('date');
            $table->timestamp('check_in_time')->useCurrent();
            $table->timestamp('check_out_time')->useCurrent();
            $table->decimal('total_work_hours', 15, 2)->default(0);
            $table->enum('status', ['NORMAL','LATE','OVERTIME','ABSENT']);

            // Ensure only one daily recap per employee per company
            $table->unique(['company_id', 'employee_id', 'date']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('attendance_summary');
    }
};
