<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('import_batches', function (Blueprint $table) {
            $table->string('id')->primary(); // IMP-2026-000001
            $table->string('type');
            $table->string('file_name');
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->integer('total_rows')->default(0);
            $table->string('status');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('import_batches'); }
};
