<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Certificate Templates
        Schema::create('certificate_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('background_image')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Certificate Fields
        Schema::create('certificate_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('certificate_template_id')->constrained()->cascadeOnDelete();
            $table->string('field_name'); // e.g. User Name, Course Name, Date
            $table->integer('x_position')->default(0);
            $table->integer('y_position')->default(0);
            $table->string('font_family')->default('Arial');
            $table->integer('font_size')->default(12);
            $table->string('font_color')->default('#000000');
            $table->timestamps();
        });

        // 3. Certificates
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->string('certificate_number')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('certificate_template_id')->nullable()->constrained()->nullOnDelete();
            $table->date('issue_date');
            $table->date('expiry_date')->nullable();
            $table->string('file_path')->nullable();
            $table->string('status')->default('VALID'); // VALID, REVOKED, EXPIRED
            $table->timestamps();
            $table->softDeletes();
        });

        // 4. Notification Templates
        Schema::create('notification_templates', function (Blueprint $table) {
            $table->id();
            $table->string('event_name')->unique(); // e.g. Course Assigned, Course Completed
            $table->string('channel'); // EMAIL, WHATSAPP, SYSTEM
            $table->string('subject')->nullable();
            $table->text('body');
            $table->timestamps();
        });

        // 5. Notification Settings
        Schema::create('notification_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->boolean('email_enabled')->default(true);
            $table->boolean('whatsapp_enabled')->default(true);
            $table->timestamps();
        });

        // 6. Notification Logs
        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // e.g. COURSE_ASSIGNED
            $table->string('channel'); // EMAIL, WHATSAPP
            $table->string('status'); // PENDING, SENT, FAILED, CANCELLED
            $table->text('error_message')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });

        // 7. Import Batches
        Schema::create('import_batches', function (Blueprint $table) {
            $table->id();
            $table->string('batch_id')->unique(); // e.g. IMP-2026-000001
            $table->string('import_type'); // CREATE, UPDATE
            $table->string('menu'); // Users, Courses, etc
            $table->string('file_name');
            $table->foreignId('uploaded_by')->constrained('users')->cascadeOnDelete();
            $table->integer('total_rows')->default(0);
            $table->integer('valid_rows')->default(0);
            $table->integer('error_rows')->default(0);
            $table->string('status')->default('PENDING'); // PENDING, VALIDATING, PROCESSING, COMPLETED, FAILED
            $table->timestamps();
        });

        // 8. Import Rows
        Schema::create('import_rows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('import_batch_id')->constrained()->cascadeOnDelete();
            $table->integer('row_number');
            $table->string('unique_key')->nullable();
            $table->json('data');
            $table->string('status'); // VALID, ERROR, PROCESSED
            $table->text('error_message')->nullable();
            $table->timestamps();
        });

        // 9. Audit Logs
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // Performed By
            $table->string('action'); // CREATE, UPDATE, DELETE, LOGIN
            $table->string('menu'); // User, Course, etc
            $table->string('record_id')->nullable();
            $table->string('field')->nullable();
            $table->text('old_value')->nullable();
            $table->text('new_value')->nullable();
            $table->string('source')->default('SYSTEM');
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });

        // 10. API Sync Logs
        Schema::create('api_sync_logs', function (Blueprint $table) {
            $table->id();
            $table->string('source'); // e.g. ODOO
            $table->string('endpoint');
            $table->string('status'); // SUCCESS, FAILED
            $table->integer('records_synced')->default(0);
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_sync_logs');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('import_rows');
        Schema::dropIfExists('import_batches');
        Schema::dropIfExists('notification_logs');
        Schema::dropIfExists('notification_settings');
        Schema::dropIfExists('notification_templates');
        Schema::dropIfExists('certificates');
        Schema::dropIfExists('certificate_fields');
        Schema::dropIfExists('certificate_templates');
    }
};
