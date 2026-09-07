<?php
$files = glob('database/migrations/2026_09_06_1723*.php');
foreach($files as $f) {
    $c = file_get_contents($f);
    
    if (strpos($f, 'create_courses_table') !== false) {
        $c = str_replace(['$table->id();', '$table->timestamps();'], ['$table->id();'."\n".'            $table->string(\'code\')->unique();'."\n".'            $table->string(\'title\');'."\n".'            $table->text(\'description\')->nullable();'."\n".'            $table->string(\'thumbnail\')->nullable();'."\n".'            $table->integer(\'duration\')->default(0);'."\n".'            $table->integer(\'passing_grade\')->default(80);'."\n".'            $table->enum(\'status\', [\'NOT STARTED\', \'IN PROGRESS\', \'COMPLETED\', \'FAILED\', \'EXPIRED\', \'CANCELLED\', \'PRINCIPAL INACTIVE\'])->default(\'NOT STARTED\');', '$table->timestamps();'."\n".'            $table->softDeletes();'], $c);
    }
    
    if (strpos($f, 'create_modules_table') !== false) {
        $c = str_replace(['$table->id();', '$table->timestamps();'], ['$table->id();'."\n".'            $table->foreignId(\'course_id\')->constrained(\'courses\')->cascadeOnDelete();'."\n".'            $table->string(\'code\')->unique();'."\n".'            $table->string(\'title\');'."\n".'            $table->integer(\'order_number\')->default(0);', '$table->timestamps();'."\n".'            $table->softDeletes();'], $c);
    }

    if (strpos($f, 'create_lessons_table') !== false) {
        $c = str_replace(['$table->id();', '$table->timestamps();'], ['$table->id();'."\n".'            $table->foreignId(\'module_id\')->constrained(\'modules\')->cascadeOnDelete();'."\n".'            $table->string(\'code\')->unique();'."\n".'            $table->string(\'title\');'."\n".'            $table->integer(\'order_number\')->default(0);'."\n".'            $table->boolean(\'is_sequential\')->default(true);', '$table->timestamps();'."\n".'            $table->softDeletes();'], $c);
    }

    if (strpos($f, 'create_lesson_materials_table') !== false) {
        $c = str_replace(['$table->id();', '$table->timestamps();'], ['$table->id();'."\n".'            $table->foreignId(\'lesson_id\')->constrained(\'lessons\')->cascadeOnDelete();'."\n".'            $table->enum(\'type\', [\'UPLOADED_VIDEO\', \'YOUTUBE\', \'EXTERNAL_URL\', \'PDF\', \'PPT\']);'."\n".'            $table->string(\'content_url\');'."\n".'            $table->integer(\'duration\')->default(0);', '$table->timestamps();'], $c);
    }

    if (strpos($f, 'create_assignment_rules_table') !== false) {
        $c = str_replace(['$table->id();', '$table->timestamps();'], ['$table->id();'."\n".'            $table->foreignId(\'principal_id\')->constrained(\'principals\')->cascadeOnDelete();'."\n".'            $table->foreignId(\'course_id\')->constrained(\'courses\')->cascadeOnDelete();'."\n".'            $table->foreignId(\'department_id\')->nullable()->constrained(\'departments\')->cascadeOnDelete();'."\n".'            $table->date(\'effective_date\')->nullable();'."\n".'            $table->date(\'expiry_date\')->nullable();'."\n".'            $table->boolean(\'is_active\')->default(true);', '$table->timestamps();'."\n".'            $table->softDeletes();'], $c);
    }

    if (strpos($f, 'create_course_principal_table') !== false) {
        $c = str_replace(['$table->id();', '$table->timestamps();'], ['$table->foreignId(\'course_id\')->constrained(\'courses\')->cascadeOnDelete();'."\n".'            $table->foreignId(\'principal_id\')->constrained(\'principals\')->cascadeOnDelete();', ''], $c);
    }

    if (strpos($f, 'create_assignment_rule_positions_table') !== false) {
        $c = str_replace(['$table->id();', '$table->timestamps();'], ['$table->foreignId(\'assignment_rule_id\')->constrained(\'assignment_rules\')->cascadeOnDelete();'."\n".'            $table->foreignId(\'position_id\')->nullable()->constrained(\'positions\')->cascadeOnDelete();'."\n".'            // If position_id is null, it means ALL POSITIONS', ''], $c);
    }

    file_put_contents($f, $c);
}
