<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();
            $table->string('notification_id')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('type');
            $table->string('channel');
            $table->enum('status', ['PENDING', 'SENT', 'FAILED', 'CANCELLED'])->default('PENDING');
            $table->timestamp('sent_at')->nullable();
            $table->text('error')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('notification_logs'); }
};
