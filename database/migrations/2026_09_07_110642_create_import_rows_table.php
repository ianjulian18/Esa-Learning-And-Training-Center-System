<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('import_rows', function (Blueprint $table) {
            $table->id();
            $table->string('batch_id');
            $table->foreign('batch_id')->references('id')->on('import_batches')->onDelete('cascade');
            $table->integer('row_number');
            $table->json('data');
            $table->enum('status', ['VALID', 'ERROR', 'WARNING'])->default('VALID');
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('import_rows'); }
};
