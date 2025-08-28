<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('username');
            $table->string('email');
            $table->string('password');
            $table->enum('role', ['ADMIN','STAFF','OWNER','DEV']);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();

            $table->unique(['company_id', 'username']);
            $table->unique(['company_id', 'email']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('users');
    }
};
