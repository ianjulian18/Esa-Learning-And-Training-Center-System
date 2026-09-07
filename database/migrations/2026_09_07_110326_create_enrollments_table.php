<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('principal_id')->constrained()->onDelete('cascade');
            $table->foreignId('assignment_rule_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('status', ['NOT STARTED', 'IN PROGRESS', 'COMPLETED', 'FAILED', 'EXPIRED', 'CANCELLED', 'PRINCIPAL INACTIVE'])->default('NOT STARTED');
            $table->integer('score')->nullable();
            $table->timestamp('completion_date')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('enrollments'); }
};
