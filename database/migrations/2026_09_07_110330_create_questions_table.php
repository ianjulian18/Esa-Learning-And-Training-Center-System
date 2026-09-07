<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_bank_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('course_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('type'); // MULTIPLE_CHOICE, TRUE_FALSE, ESSAY
            $table->text('question_text');
            $table->json('options')->nullable();
            $table->text('answer_key')->nullable();
            $table->integer('points')->default(10);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('questions'); }
};
