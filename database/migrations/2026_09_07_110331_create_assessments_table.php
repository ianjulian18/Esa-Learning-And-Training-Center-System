<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['PRE_TEST', 'POST_TEST'])->default('POST_TEST');
            $table->integer('total_questions')->default(0);
            $table->integer('passing_grade')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('assessments'); }
};
