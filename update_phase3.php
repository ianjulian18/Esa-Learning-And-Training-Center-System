<?php
$files = glob('database/migrations/2026_09_06_1729*.php');
foreach($files as $f) {
    $c = file_get_contents($f);
    
    if (strpos($f, 'create_enrollments_table') !== false) {
        $c = str_replace(['$table->id();', '$table->timestamps();'], ['$table->id();'."\n".'            $table->foreignId(\'user_id\')->constrained(\'users\')->cascadeOnDelete();'."\n".'            $table->foreignId(\'course_id\')->constrained(\'courses\')->cascadeOnDelete();'."\n".'            $table->foreignId(\'principal_id\')->constrained(\'principals\')->cascadeOnDelete();'."\n".'            $table->enum(\'status\', [\'NOT STARTED\', \'IN PROGRESS\', \'COMPLETED\', \'FAILED\', \'EXPIRED\', \'CANCELLED\', \'PRINCIPAL INACTIVE\'])->default(\'NOT STARTED\');'."\n".'            $table->timestamp(\'enrolled_at\')->useCurrent();'."\n".'            $table->timestamp(\'expires_at\')->nullable();'."\n".'            $table->timestamp(\'completed_at\')->nullable();', '$table->timestamps();'."\n".'            $table->softDeletes();'], $c);
    }
    
    if (strpos($f, 'create_lesson_progress_table') !== false) {
        $c = str_replace(['$table->id();', '$table->timestamps();'], ['$table->id();'."\n".'            $table->foreignId(\'enrollment_id\')->constrained(\'enrollments\')->cascadeOnDelete();'."\n".'            $table->foreignId(\'lesson_id\')->constrained(\'lessons\')->cascadeOnDelete();'."\n".'            $table->enum(\'status\', [\'NOT STARTED\', \'IN PROGRESS\', \'COMPLETED\'])->default(\'NOT STARTED\');'."\n".'            $table->integer(\'percentage\')->default(0);'."\n".'            $table->integer(\'last_watched_position\')->default(0);'."\n".'            $table->timestamp(\'started_at\')->nullable();'."\n".'            $table->timestamp(\'last_access\')->nullable();'."\n".'            $table->timestamp(\'completed_at\')->nullable();', '$table->timestamps();'], $c);
    }

    if (strpos($f, 'create_learning_histories_table') !== false) {
        $c = str_replace(['$table->id();', '$table->timestamps();'], ['$table->id();'."\n".'            $table->foreignId(\'user_id\')->constrained(\'users\');'."\n".'            $table->foreignId(\'principal_id\')->constrained(\'principals\');'."\n".'            $table->foreignId(\'course_id\')->constrained(\'courses\');'."\n".'            $table->string(\'status\');'."\n".'            $table->integer(\'score\')->nullable();'."\n".'            $table->timestamp(\'completed_at\')->nullable();'."\n".'            $table->timestamp(\'archived_at\')->useCurrent();', '$table->timestamps();'], $c);
    }

    file_put_contents($f, $c);
}
