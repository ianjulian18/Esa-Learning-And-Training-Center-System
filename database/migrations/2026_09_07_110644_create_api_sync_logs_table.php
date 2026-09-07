<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('api_sync_logs', function (Blueprint $table) {
            $table->id();
            $table->string('source'); // e.g. ODOO
            $table->string('status');
            $table->timestamp('last_sync')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('api_sync_logs'); }
};
