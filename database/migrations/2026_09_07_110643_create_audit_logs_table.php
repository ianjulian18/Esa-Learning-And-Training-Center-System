<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('action');
            $table->string('menu')->nullable();
            $table->string('record_id')->nullable();
            $table->string('field')->nullable();
            $table->text('old_value')->nullable();
            $table->text('new_value')->nullable();
            $table->string('source')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('audit_logs'); }
};
