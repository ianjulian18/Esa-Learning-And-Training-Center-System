<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->index(['user_id', 'course_id']);
            $table->index('status');
        });

        Schema::table('lesson_progress', function (Blueprint $table) {
            $table->index(['enrollment_id', 'lesson_id']);
            $table->index('status');
        });

        Schema::table('certificates', function (Blueprint $table) {
            $table->index('certificate_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'course_id']);
            $table->dropIndex(['status']);
        });

        Schema::table('lesson_progress', function (Blueprint $table) {
            $table->dropIndex(['enrollment_id', 'lesson_id']);
            $table->dropIndex(['status']);
        });

        Schema::table('certificates', function (Blueprint $table) {
            $table->dropIndex(['certificate_number']);
        });
    }
};
