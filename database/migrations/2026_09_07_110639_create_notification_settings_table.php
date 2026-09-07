<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('notification_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('principal_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('event');
            $table->foreignId('template_id')->constrained('notification_templates')->onDelete('cascade');
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('notification_settings'); }
};
