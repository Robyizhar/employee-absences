<?php

// php artisan make:migration add_fingerspot_fields_to_attendance_logs_table --table=attendance_logs
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFingerspotFieldsToAttendanceLogsTable extends Migration
{
    public function up() {
        Schema::table('attendance_logs', function (Blueprint $table) {
            $table->string('verification_method', 50)->nullable()->after('status'); // finger/face/password/rfid/...
            $table->boolean('is_duplicate')->default(false)->after('raw_payload');
            $table->integer('late_seconds')->nullable()->after('is_duplicate');
            $table->integer('early_seconds')->nullable()->after('late_seconds');
            $table->integer('late_minutes')->nullable()->after('late_seconds');
            $table->integer('early_leave_minutes')->nullable()->after('late_minutes');
        });
    }

    public function down() {
        Schema::table('attendance_logs', function (Blueprint $table) {
            $table->dropColumn([
                'verification_method',
                'is_duplicate',
                'late_seconds',
                'early_seconds',
                'late_minutes',
                'early_leave_minutes',
            ]);
        });
    }
}

