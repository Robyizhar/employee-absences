<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('machines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('serial_number');
            $table->string('trans_id');
            $table->string('location');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();

            $table->unique(['company_id', 'serial_number']);
            $table->unique(['company_id', 'trans_id']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('machines');
    }
};
